<?php

namespace App\Actions\Labs\KidneyTransplantHLATyping;

use App\Extensions\Auth\AvatarUser;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReportUpdateAction extends ReportAction
{
    public function __invoke(string $hashedKey, array $data, User|AvatarUser $user)
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $report = $this->getReport($hashedKey);

        if ($user->cannot('update', $report)) {
            abort(403, 'You are not allowed to update this report.');
        }

        $rules = [
            'diagnosis' => ['nullable', 'string', 'max:255'],
            'clinician' => ['nullable', 'string', 'max:255'],
            'date_report' => ['nullable', 'date'],
            'reporter' => ['nullable', 'string', 'max:255'],
            'approver' => ['nullable', 'string', 'max:255'],
            'recipient_is' => ['nullable', 'string', Rule::in($this->RECIPIENT_IS_OPTIONS)],
            'donor_is' => ['nullable', 'string', Rule::in($this->DONOR_IS_OPTIONS[$data['recipient_is']] ?? [])],
            'scanned_report' => ['nullable', 'string', 'max:255'],
            'remark' => ['nullable', 'string', 'max:512'],
        ];

        foreach (['patient', 'donor'] as $type) {
            if ($data["{$type}_hla_note"]) {
                $rules["{$type}_hla_note"] = ['required', 'array'];
                $rules["{$type}_hla_note.*"] = ['nullable', 'string', 'max:30'];
                $rules["{$type}_hla_note.date_hla_typing"] = ['nullable', 'date'];
                $rules["{$type}_hla_note.abo"] = ['nullable', 'string', Rule::in($this->ABO_OPTIONS)];
                $rules["{$type}_hla_note.rh"] = ['nullable', 'string', Rule::in($this->RH_OPTIONS)];
            }
            if ($data["{$type}_cxm_note"]) {
                $rules["{$type}_cxm_note"] = ['required', 'array'];
                $rules["{$type}_cxm_note.date_cross_matching"] = ['nullable', 'date'];
                $this->addCrossmatchShareRules($rules, $type);
            }
            if ($data["{$type}_addition_tissue_typing_note"]) {
                $rules["{$type}_addition_tissue_typing_note"] = ['required', 'array'];
                $rules["{$type}_addition_tissue_typing_note.*"] = ['nullable', 'string', 'max:30'];
                $rules["{$type}_addition_tissue_typing_note.date_addition_tissue_typing"] = ['nullable', 'date'];
            }
        }

        $validated = Validator::validate($data, $rules);

        $tempNotes = [];
        $reportForm = [];
        $this->splitForm($validated, $reportForm, $tempNotes);

        // update note if there is any change
        if (count($this->formJsonDiff($report->form, $reportForm))) {
            $report->update(['form' => $reportForm]);
        }

        // update sub notes
        $this->updateSubNotes($tempNotes, $report);

        return ['report' => $validated, 'tempNotes' => $tempNotes];
    }
}
