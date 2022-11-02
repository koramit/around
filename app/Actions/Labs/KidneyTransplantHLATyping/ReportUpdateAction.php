<?php

namespace App\Actions\Labs\KidneyTransplantHLATyping;

use App\Models\Notes\KidneyTransplantCrossmatchNote;
use App\Models\Notes\KidneyTransplantHLATypingNote;
use App\Models\Notes\KidneyTransplantHLATypingReportNote;
use App\Traits\AvatarLinkable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReportUpdateAction extends ReportAction
{
    use AvatarLinkable;

    public function __invoke(string $hashedKey, array $data, mixed $user)
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $report = KidneyTransplantHLATypingReportNote::query()->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('update', $report)) {
            abort(403);
        }

        $rules = [
            'diagnosis' => ['nullable', 'string', 'max:255'],
            'clinician' => ['nullable', 'string', 'max:255'],
            'date_report' => ['nullable', 'date'],
            'reporter' => ['nullable', 'string', 'max:255'],
            'approver' => ['nullable', 'string', 'max:255'],
            'recipient_is' => ['nullable', 'string', Rule::in($this->RECIPIENT_IS_OPTIONS)],
            'donor_is' => ['nullable', 'string', Rule::in($this->DONOR_IS_OPTIONS[$data['recipient_is']] ?? [])],
        ];

        foreach (['patient', 'donor'] as $type) {
            if ($data["{$type}_hla_note"]) {
                $rules["{$type}_hla_note"] = ['nullable', 'array'];
                $rules["{$type}_hla_note.*"] = ['nullable', 'string', 'max:30'];
                $rules["{$type}_hla_note.date_hla_typing"] = ['nullable', 'date'];
                $rules["{$type}_hla_note.abo"] = ['nullable', 'string', Rule::in($this->ABO_OPTIONS)];
                $rules["{$type}_hla_note.rh"] = ['nullable', 'string', Rule::in($this->RH_OPTIONS)];
            }
            if ($data["{$type}_cxm_note"]) {
                $rules["{$type}_cxm_note"] = ['nullable', 'array'];
                $rules["{$type}_cxm_note.*"] = ['nullable', 'string', Rule::in($this->LYMPHOCYTE_CROSSMATCH_OPTIONS)];
            }
        }

        $validated = Validator::validate($data, $rules);

        $tempNotes = [];
        foreach (['patient', 'donor'] as $type) {
            foreach (['hla', 'cxm'] as $note) {
                if ($validated["{$type}_{$note}_note"] ?? null) {
                    $tempNotes["{$type}_{$note}_note"] = $validated["{$type}_{$note}_note"];
                    unset($validated["{$type}_{$note}_note"]);
                }
            }
        }

        // update note if there is any change
        if (array_diff($validated, [...$report->form])) {
            $report->update(['form' => $validated]);
        }
        foreach (['patient', 'donor'] as $type) {
            foreach (['hla', 'cxm'] as $note) {
                if ($tempNotes["{$type}_{$note}_note"] ?? null) {
                    $modelClassname = $note === 'hla' ? KidneyTransplantHLATypingNote::class : KidneyTransplantCrossmatchNote::class;
                    $noteModel = $modelClassname::query()->findByUnhashKey($report->meta["{$type}_{$note}_note_key"])->firstOrFail();
                    $noteModel->update(['form' => $tempNotes["{$type}_{$note}_note"]]);
                }
            }
        }

        return ['report' => $validated, 'tempNotes' => $tempNotes];
    }
}
