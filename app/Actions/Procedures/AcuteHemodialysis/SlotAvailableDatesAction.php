<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Rules\FieldValueExists;
use App\Traits\AcuteHemodialysis\OrderShareValidatable;
use App\Traits\AvatarLinkable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SlotAvailableDatesAction extends AcuteHemodialysisAction
{
    use OrderShareValidatable, AvatarLinkable;

    public function __invoke(array $data, mixed $user): array
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
        }

        $validated = Validator::make($data, [
            'dialysis_at' => ['required', 'string', 'max:255', new FieldValueExists('App\Models\Resources\Ward', 'name')],
            'dialysis_type' => ['required', 'string', Rule::in($this->getAllDialysisType())],
            'covid_case' => 'required|boolean',
        ])->validate();

        // possible dates
        $dates = $this->getPossibleDates();

        return collect($dates)->transform(function ($d) use ($validated, $user) {
            $data = [...$validated];
            $data['date_note'] = $d->format('Y-m-d');
            $label = $d->format('d M').' | ';
            $label .= ($data['date_note'] === $this->TODAY ? 'Today' : $d->dayName).' | ';

            $slot = (new SlotAvailableAction)(data: $data, user: $user);

            if ($validated['covid_case'] && $slot['available']) {
                return ['value' => $d->format('Y-m-d'), 'label' => $label.'Approval needed'];
            }

            if ($data['date_note'] === $this->TODAY && (! $slot['available'] || $d->is($this->UNIT_DAY_OFF))) {
                return ['value' => $d->format('Y-m-d'), 'label' => $label.'Approval and extra slot needed'];
            }

            if ($data['date_note'] === $this->TODAY && $slot['available']) {
                return ['value' => $d->format('Y-m-d'), 'label' => $label.'Approval needed'];
            }

            if ($this->zombieHours($data['date_note']) && $slot['available']) {
                return ['value' => $d->format('Y-m-d'), 'label' => $label.'Approval needed (ZH)'];
            }

            if ($data['date_note'] !== $this->TODAY && $slot['available']) {
                return ['value' => $d->format('Y-m-d'), 'label' => $label.'Available'];
            }

            return ['value' => $d->format('Y-m-d'), 'label' => $label.'Unavailable', 'error' => $slot['reply']]; // next day and no slot
        })->all();
    }
}
