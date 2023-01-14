<?php

namespace App\Actions\Labs\KidneyTransplantHLATyping;

use App\Extensions\Auth\AvatarUser;
use App\Models\User;
use App\Traits\AvatarLinkable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReportPublishAction extends ReportAction
{
    use AvatarLinkable;

    public function __invoke(string $hashedKey, array $data, User|AvatarUser $user)
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $report = $this->getReport($hashedKey);

        if (in_array($report->status, ['published', 'edited'])) {
            if ($user->cannot('addendum', $report)) {
                abort(403, 'You are not allowed to publish this addendum.');
            }
            $status = 'edited';
        } else {
            if ($user->cannot('update', $report)) {
                abort(403, 'You are not allowed to publish this report.');
            }
            $status = 'published';
        }

        $rules = [
            'diagnosis' => ['nullable', 'string', 'max:255'],
            'clinician' => ['required', 'string', 'max:255'],
            'date_report' => ['required', 'date'],
            'reporter' => ['required', 'string', 'max:255'],
            'approver' => ['required', 'string', 'max:255'],
            'recipient_is' => ['nullable', Rule::requiredIf($report->meta['donor_hn']), 'string', Rule::in($this->RECIPIENT_IS_OPTIONS)],
            'donor_is' => ['nullable', Rule::requiredIf($report->meta['donor_hn']), 'string', Rule::in($this->DONOR_IS_OPTIONS[$data['recipient_is']] ?? [])],
            'scanned_report' => ['required', 'string', 'max:255'],
            'remark' => ['nullable', 'string', 'max:512'],
        ];

        foreach (['patient', 'donor'] as $type) {
            if ($data["{$type}_hla_note"]) {
                $rules["{$type}_hla_note"] = ['required', 'array'];
                $rules["{$type}_hla_note.*"] = ['nullable', 'string', 'max:30'];
                $rules["{$type}_hla_note.date_hla_typing"] = ['required', 'date'];
                $rules["{$type}_hla_note.abo"] = ['required', 'string', Rule::in($this->ABO_OPTIONS)];
                $rules["{$type}_hla_note.rh"] = ['required', 'string', Rule::in($this->RH_OPTIONS)];
            }
            if ($data["{$type}_cxm_note"]) {
                $rules["{$type}_cxm_note"] = ['required', 'array'];
                $rules["{$type}_cxm_note.date_cross_matching"] = ['required', 'date'];
                $this->addCrossmatchShareRules($rules, $type);
            }
            if ($data["{$type}_addition_tissue_typing_note"]) {
                $rules["{$type}_addition_tissue_typing_note"] = ['required', 'array'];
                $rules["{$type}_addition_tissue_typing_note.*"] = ['nullable', 'string', 'max:30'];
                $rules["{$type}_addition_tissue_typing_note.date_addition_tissue_typing"] = ['required', 'date'];
            }
        }

        $validated = Validator::validate($data, $rules);

        $tempNotes = [];
        $reportForm = [];
        $this->splitForm($validated, $reportForm, $tempNotes);

        $this->updateSubNotes($tempNotes, $report, $status, $user);

        $changes = $this->formJsonDiff($report->form, $reportForm);
        if (count($changes)) {
            $report->form = $reportForm;
        }
        $report->status = $status;
        $report->save(); // always save report to update updated_at

        $action = $this->STATUS_ACTION[$status];
        $logData = [
            'actor_id' => $user->id,
            'action' => $action,
        ];

        if ($status === 'edited') {
            $logData['payload'] = $changes;
        }

        $report->actionLogs()->create($logData);

        return [
            'message' => [
                'type' => 'success',
                'title' => ucfirst($action).' successful.',
                'message' => $report->title,
            ],
        ];
    }
}
