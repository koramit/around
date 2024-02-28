<?php

namespace App\Actions\Labs\KidneyTransplantHLATyping;

use App\Models\Note;
use App\Models\Notes\KidneyTransplantAdditionTissueTypingNote;
use App\Models\Notes\KidneyTransplantCrossmatchNote;
use App\Models\Notes\KidneyTransplantHLATypingNote;
use App\Models\Notes\KidneyTransplantHLATypingReportNote;
use App\Models\Resources\Registry;
use App\Models\User;
use App\Traits\AvatarLinkable;
use App\Traits\ChangesComparable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class ReportAction
{
    use AvatarLinkable, ChangesComparable;

    protected int $REGISTRY_ID;

    protected array $RECIPIENT_IS_OPTIONS = ['น้อง', 'พี่', 'บุตร', 'ภรรยา', 'สามี', 'มารดา', 'บิดา', 'หลาน', 'ป้า', 'น้า', 'อา', 'ลุง'];

    protected array $MALE_RECIPIENT_IS_OPTIONS = ['น้อง', 'พี่', 'บุตร', 'สามี', 'บิดา', 'หลาน', 'น้า', 'อา', 'ลุง'];

    protected array $FEMALE_RECIPIENT_IS_OPTIONS = ['น้อง', 'พี่', 'บุตร', 'ภรรยา', 'มารดา',  'หลาน', 'ป้า', 'น้า', 'อา'];

    protected array $DONOR_IS_OPTIONS = [
        'น้อง' => ['พี่'],
        'พี่' => ['น้อง'],
        'บุตร' => ['บิดา', 'มารดา'],
        'ภรรยา' => ['สามี'],
        'สามี' => ['ภรรยา'],
        'มารดา' => ['บุตร'],
        'บิดา' => ['บุตร'],
        'หลาน' => ['ป้า', 'น้า', 'อา', 'ลุง'],
        'ป้า' => ['หลาน'],
        'น้า' => ['หลาน'],
        'อา' => ['หลาน'],
        'ลุง' => ['หลาน'],
    ];

    protected array $ABO_OPTIONS = ['A', 'B', 'AB', 'O'];

    protected array $RH_OPTIONS = ['positive', 'negative'];

    protected array $LYMPHOCYTE_CROSSMATCH_OPTIONS = ['N', 'WkP', 'P'];

    protected array $PATIENT_TYPES = [
        'Patient', 'Recipient with LD',
    ];

    protected array $STATUS_ACTION = [
        'published' => 'publish',
        'canceled' => 'cancel',
        'edited' => 'change',
    ];

    public function __construct()
    {
        $this->REGISTRY_ID = cache()->rememberForever(
            'registry-id-kt_hla_typing',
            fn () => Registry::query()->where('name', 'kt_hla_typing')->first()->id
        );
    }

    protected function getReport(string $hashedKey): Note|KidneyTransplantHLATypingReportNote|ModelNotFoundException
    {
        return KidneyTransplantHLATypingReportNote::query()
            ->findByUnhashKey($hashedKey)
            ->firstOrFail();
    }

    protected function addCrossmatchShareRules(&$rules, $type): void
    {
        $rules["{$type}_cxm_note.t_lymphocyte_cdc_neat_rt"] = ['nullable', 'string', Rule::in($this->LYMPHOCYTE_CROSSMATCH_OPTIONS)];
        $rules["{$type}_cxm_note.t_lymphocyte_cdc_neat_37_degree_celsius"] = ['nullable', 'string', Rule::in($this->LYMPHOCYTE_CROSSMATCH_OPTIONS)];
        $rules["{$type}_cxm_note.t_lymphocyte_cdc_dtt_rt"] = ['nullable', 'string', Rule::in($this->LYMPHOCYTE_CROSSMATCH_OPTIONS)];
        $rules["{$type}_cxm_note.t_lymphocyte_cdc_dtt_37_degree_celsius"] = ['nullable', 'string', Rule::in($this->LYMPHOCYTE_CROSSMATCH_OPTIONS)];
        $rules["{$type}_cxm_note.t_lymphocyte_cdc_ahg_neat_rt"] = ['nullable', 'string', Rule::in($this->LYMPHOCYTE_CROSSMATCH_OPTIONS)];
        $rules["{$type}_cxm_note.t_lymphocyte_cdc_ahg_dtt_rt"] = ['nullable', 'string', Rule::in($this->LYMPHOCYTE_CROSSMATCH_OPTIONS)];
        $rules["{$type}_cxm_note.b_lymphocyte_cdc_neat_37_degree_celsius"] = ['nullable', 'string', Rule::in($this->LYMPHOCYTE_CROSSMATCH_OPTIONS)];
        $rules["{$type}_cxm_note.b_lymphocyte_cdc_dtt_37_degree_celsius"] = ['nullable', 'string', Rule::in($this->LYMPHOCYTE_CROSSMATCH_OPTIONS)];
        $rules["{$type}_cxm_note.b_lymphocyte_cdc_ahg_neat_37_degree_celsius"] = ['nullable', 'string', Rule::in($this->LYMPHOCYTE_CROSSMATCH_OPTIONS)];
        $rules["{$type}_cxm_note.b_lymphocyte_cdc_ahg_dtt_37_degree_celsius"] = ['nullable', 'string', Rule::in($this->LYMPHOCYTE_CROSSMATCH_OPTIONS)];
        $rules["{$type}_cxm_note.t_lymphocyte_crossmatch"] = ['nullable', 'string', 'max:128'];
        $rules["{$type}_cxm_note.b_lymphocyte_crossmatch"] = ['nullable', 'string', 'max:128'];
    }

    protected function splitForm(array &$validated, array &$reportForm, array &$tempNotes): void
    {
        foreach (['patient', 'donor'] as $type) {
            foreach (['hla', 'cxm', 'addition_tissue_typing'] as $note) {
                if ($validated["{$type}_{$note}_note"] ?? null) {
                    $tempNotes["{$type}_{$note}_note"] = $validated["{$type}_{$note}_note"];
                    unset($validated["{$type}_{$note}_note"]);
                }
            }
        }

        $reportForm = $validated;
    }

    protected function updateSubNotes(array &$tempNotes, KidneyTransplantHLATypingReportNote $report, ?string $status = null, ?User $user = null): void
    {
        foreach (['patient', 'donor'] as $type) {
            foreach (['hla', 'cxm', 'addition_tissue_typing'] as $note) {
                if ($tempNotes["{$type}_{$note}_note"] ?? null) {
                    $noteModel = match ($note) {
                        'hla' => KidneyTransplantHLATypingNote::query()->findByUnhashKey($report->meta["{$type}_{$note}_note_key"])->firstOrFail(),
                        'cxm' => KidneyTransplantCrossmatchNote::query()->findByUnhashKey($report->meta["{$type}_{$note}_note_key"])->firstOrFail(),
                        'addition_tissue_typing' => KidneyTransplantAdditionTissueTypingNote::query()->findByUnhashKey($report->meta["{$type}_{$note}_note_key"])->firstOrFail(),
                    };

                    $changes = $this->formJsonDiff($noteModel->form, $tempNotes["{$type}_{$note}_note"]);
                    if (! count($changes)) {
                        continue;
                    }

                    if ($status) {
                        $noteModel->update([
                            'form' => $tempNotes["{$type}_{$note}_note"],
                            'status' => $status,
                        ]);

                        $logData = [
                            'actor_id' => $user->id,
                            'action' => $this->STATUS_ACTION[$status],
                        ];

                        if ($status === 'edited') {
                            $logData['payload'] = $changes;
                        }

                        $noteModel->actionLogs()->create($logData);
                    } else {
                        $noteModel->update(['form' => $tempNotes["{$type}_{$note}_note"]]);
                    }
                }
            }
        }
    }

    protected function getActionMenu(KidneyTransplantHLATypingReportNote $report, User $user, array $actions = []): array
    {
        return collect([
            // [
            //     'label' => 'View',
            //     'icon' => 'eye',
            //     'url' => route('labs.kt-hla-typing.reports.edit', $report->hashed_key),
            //     'can' => $user->can('view', $report),
            // ],
            [
                'label' => 'Edit',
                'as' => 'link',
                'icon' => 'edit',
                'theme' => 'accent',
                'route' => route('labs.kt-hla-typing.reports.edit', $report->hashed_key),
                'can' => ($user->can('edit', $report) || $user->can('addendum', $report))
                    && (! count($actions) || in_array('edit', $actions)),
            ],
            [
                'label' => 'Publish',
                'as' => 'button',
                'icon' => 'box-archive',
                'name' => 'publish-report',
                'route' => route('labs.kt-hla-typing.reports.publish', $report->hashed_key),
                'can' => $user->can('publish', $report)
                    && (! count($actions) || in_array('publish', $actions)),
            ],
            [
                'label' => 'Delete',
                'as' => 'button',
                'icon' => 'trash',
                'theme' => 'danger',
                'route' => route('labs.kt-hla-typing.reports.destroy', $report->hashed_key),
                'name' => 'destroy-report',
                'config' => [
                    'heading' => 'Delete Report',
                    'confirmText' => $report->title,
                    'requireReason' => false,
                ],
                'can' => $user->can('destroy', $report)
                    && (! count($actions) || in_array('destroy', $actions)),
            ],
            [
                'label' => 'Addendum',
                'as' => 'button',
                'icon' => 'edit',
                'name' => 'addendum-report',
                'route' => route('labs.kt-hla-typing.reports.addendum', $report->hashed_key),
                'can' => $user->can('addendum', $report)
                    && (! count($actions) || in_array('addendum', $actions)),
            ],
            [
                'label' => 'Cancel',
                'as' => 'button',
                'icon' => 'trash-x-mark',
                'theme' => 'warning',
                'route' => route('labs.kt-hla-typing.reports.cancel', $report->hashed_key),
                'name' => 'cancel-report',
                'config' => [
                    'heading' => 'Cancel Report',
                    'confirmText' => $report->title,
                    'requireReason' => true,
                ],
                'can' => $user->can('cancel', $report)
                    && (! count($actions) || in_array('cancel', $actions)),
            ],
        ])->filter(fn ($action) => $action['can'])->values()->all();
    }
}
