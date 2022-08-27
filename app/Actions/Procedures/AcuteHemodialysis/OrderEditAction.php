<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\Resources\Ward;
use App\Models\User;
use App\Traits\AcuteHemodialysis\OrderFormConfigsShareable;
use App\Traits\Subscribable;
use Hashids\Hashids;

class OrderEditAction extends AcuteHemodialysisAction
{
    use OrderFormConfigsShareable, Subscribable;

    public function __invoke(string $hashedKey, User $user): array
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        $note = AcuteHemodialysisOrderNote::query()->withPlaceName(Ward::class)->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('edit', $note)) {
            abort(403);
        }

        $can = [
            'update' => $user->can('update', $note),
            'reschedule' => $user->can('reschedule', $note),
            'copy' => ! $note->meta['submitted'],
        ];

        $flash = [
            'page-title' => 'Acute '.$note->meta['dialysis_type'].' '.$note->patient->profile['first_name'].' @ '.$note->date_note->format('d M Y'),
            'hn' => $note->patient->hn,
            'main-menu-links' => [
                ['icon' => 'slack-hash', 'label' => 'Prescription', 'type' => '#', 'route' => '#prescription', 'can' => true],
                ['icon' => 'slack-hash', 'label' => 'Predialysis', 'type' => '#', 'route' => '#predialysis-evaluation', 'can' => true],
                ['icon' => 'slack-hash', 'label' => 'Monitoring', 'type' => '#', 'route' => '#monitoring', 'can' => true],
                ['icon' => 'slack-hash', 'label' => 'Discussion', 'type' => '#', 'route' => '#discussion', 'can' => true],
                ['icon' => 'patient', 'label' => 'Patients', 'route' => route('patients'), 'can' => true],
                ['icon' => 'clinic', 'label' => 'Clinics', 'route' => route('clinics'), 'can' => true],
                ['icon' => 'procedure', 'label' => 'Procedures', 'route' => route('procedures.index'), 'can' => true],
            ],
            'action-menu' => [
                ['icon' => 'paper-plain', 'action' => 'submit', 'label' => 'Submit', 'can' => $user->can('submit', $note)],
                $this->getSubscriptionActionMenu($note, $user),
            ],
            'breadcrumbs' => $this->getBreadcrumbs([
                ['label' => 'Case Record', 'route' => route('procedures.acute-hemodialysis.edit', app(Hashids::class)->encode($note->case_record_id))],
            ]),
        ];

        if (! $can['update']) {
            /** @TODO override message from another route */
            /** @TODO allow delete on edit page */
            $flash['message'] = [
                'type' => 'warning',
                'title' => 'Autosave disabled.',
                'message' => 'Please submit to save changes.',
            ];
        }

        return [
            'orderForm' => $note->form,
            'flash' => $flash,
            'formConfigs' => $this->FORM_CONFIGS + [
                'serology' => $this->getSerology($note->caseRecord->form->toArray()),
                'endpoints' => [
                    'update' => route('procedures.acute-hemodialysis.orders.update', $note->hashed_key),
                    'submit' => route('procedures.acute-hemodialysis.orders.submit', $note->hashed_key),
                    'reschedule' => route('procedures.acute-hemodialysis.orders.reschedule', $note->hashed_key),
                    'today_slot_request' => route('procedures.acute-hemodialysis.orders.today-slot-request', $note->hashed_key),
                    'swap' => route('procedures.acute-hemodialysis.orders.swap', $note->hashed_key),
                    'acute_hemodialysis_slot_available' => route('procedures.acute-hemodialysis.slot-available'),
                    'copy' => route('procedures.acute-hemodialysis.orders.copy', $note->hashed_key),
                ],
                'hn' => $note->meta['hn'],
                'an' => $note->meta['an'] ?? null,
                'dialysis_at' => $note->place_name,
                'dialysis_type' => $note->meta['dialysis_type'],
                'today' => $this->TODAY,
                'reserve_disable_dates' => [], // 'August 13, 2021',
                'reserve_available_dates' => $this->reserveAvailableDates(),
                'date_note' => $note->date_note->format('Y-m-d'),
                'swap_code' => $note->meta['swap_code'],
                'can' => $can,
                'comment' => $this->getCommentRoutes($note),
            ],
        ];
    }
}
