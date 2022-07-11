<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\Resources\Ward;
use App\Models\User;
use App\Traits\AcuteHemodialysis\OrderFormConfigsShareable;
use Hashids\Hashids;

class OrderEditAction extends AcuteHemodialysisAction
{
    use OrderFormConfigsShareable;

    public function __invoke(string $hashedKey, User $user)
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        $note = AcuteHemodialysisOrderNote::query()->withPlaceName(Ward::class)->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('edit', $note)) {
            abort(403);
        }

        $flash = [
            'page-title' => 'Acute HD Order '.$note->patient->profile['first_name'].' @ '.$note->date_note->format('d M Y'),
            'hn' => $note->patient->hn,
            'main-menu-links' => [
                ['icon' => 'slack-hash', 'label' => 'Prescription', 'type' => '#', 'route' => '#prescription', 'can' => true],
                ['icon' => 'slack-hash', 'label' => 'Predialysis', 'type' => '#', 'route' => '#predialysis-evaluation', 'can' => true],
                ['icon' => 'slack-hash', 'label' => 'Monitoring', 'type' => '#', 'route' => '#monitoring', 'can' => true],
                ['icon' => 'patient', 'label' => 'Patients', 'route' => route('patients'), 'can' => true],
                ['icon' => 'clinic', 'label' => 'Clinics', 'route' => route('clinics'), 'can' => true],
                ['icon' => 'procedure', 'label' => 'Procedures', 'route' => route('procedures'), 'can' => true],
            ],
            'action-menu' => [
                ['icon' => 'paper-plain', 'action' => 'submit', 'label' => 'Submit'],
            ],
            'breadcrumbs' => $this->getBreadcumbs([
                ['label' => 'Acute HD', 'route' => route('procedures.acute-hemodialysis.index')],
                ['label' => 'Case Record', 'route' => route('procedures.acute-hemodialysis.edit', app(Hashids::class)->encode($note->case_record_id))],
            ]),
        ];

        return [
            'orderForm' => $note->form,
            'flash' => $flash,
            'formConfigs' => $this->FORM_CONFIGS + [
                'endpoints' => [
                    'update' => route('procedures.acute-hemodialysis.orders.update', $note->hashed_key),
                    'submit' => route('procedures.acute-hemodialysis.orders.submit', $note->hashed_key),
                    'reschedule' => route('procedures.acute-hemodialysis.orders.reschedule', $note->hashed_key),
                    'today_slot_request' => route('procedures.acute-hemodialysis.orders.today-slot-request', $note->hashed_key),
                    'swap' => route('procedures.acute-hemodialysis.orders.swap', $note->hashed_key),
                    'acutehemodialysis_slot_available' => route('procedures.acute-hemodialysis.slot-available'),
                ],
                'hn' => $note->meta['hn'],
                'an' => $note->meta['an'] ?? null,
                'dialysis_at' => $note->place_name,
                'dialysis_type' => $note->meta['dialysis_type'],
                'today' => $this->TODAY,
                'reserve_disable_dates' => [], // 'August 13, 2021',
                'reserve_available_dates' => $this->reserveAvailableDates(),
                'date_note' => $note->date_note->format('Y-m-d'),
                'dialysis_type' => $note->meta['dialysis_type'],
                'dialysis_at' => $note->place_name,
                'swap_code' => $note->meta['swap_code'],
                'can' => [
                    'update' => $user->can('update', $note),
                    'reschedule' => $user->can('reschedule', $note),
                ],
            ],
        ];
    }
}
