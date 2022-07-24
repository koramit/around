<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\User;
use App\Rules\NameExistsInWards;
use App\Traits\AcuteHemodialysis\OrderShareValidatable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SlotAvailableDatesAction extends AcuteHemodialysisAction
{
    use OrderShareValidatable;

    public function __invoke(array $data, User $user): array
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        $validated = Validator::make($data, [
            'dialysis_at' => ['required', 'string', 'max:255', new NameExistsInWards],
            'dialysis_type' => ['required', 'string', Rule::in($this->getAllDialysisType())],
            'covid_case' => 'required|boolean',
        ])->validate();

        // possible dates
        $dates = $this->getPossibleDates();

        return collect($dates)->transform(function ($d) use ($validated) {
            $data = [...$validated];
            $data['date_note'] = $d->format('Y-m-d');
            $label = $d->format('d M').' | ';
            $label .= ($data['date_note'] === $this->TODAY ? 'Today' : $d->dayName).' | ';

            if ($validated['covid_case']) {
                return ['value' => $d->format('Y-m-d'), 'label' => $label.'Approval needed'];
            }

            $slot = (new SlotAvailableAction)($data);

            if ($data['date_note'] === $this->TODAY && (! $slot['available'] || $d->is($this->UNIT_DAY_OFF))) {
                return ['value' => $d->format('Y-m-d'), 'label' => $label.'Approval and extra slot needed'];
            }

            if ($data['date_note'] === $this->TODAY && $slot['available']) {
                return ['value' => $d->format('Y-m-d'), 'label' => $label.'Approval needed'];
            }

            if ($data['date_note'] !== $this->TODAY && $slot['available']) {
                return ['value' => $d->format('Y-m-d'), 'label' => $label.'Available'];
            }

            return ['value' => $d->format('Y-m-d'), 'label' => $label.'Unavailable', 'error' => $slot['reply']]; // next day and no slot
        })->all();
    }
}
