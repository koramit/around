<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\User;
use Hashids\Hashids;

class OrderShowAction extends AcuteHemodialysisAction
{
    public function __invoke(string $hashedKey, User $user): array
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        $order = AcuteHemodialysisOrderNote::findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('view', $order)) {
            abort(403);
        }
        
        $flash = [
            'page-title' => 'Acute '.$order->meta['dialysis_type'].' '.$order->patient->profile['first_name'].' @ '.$order->date_note->format('d M Y'),
            'hn' => $order->patient->hn,
            'main-menu-links' => [
                ['icon' => 'slack-hash', 'label' => 'Prescription', 'type' => '#', 'route' => '#prescription', 'can' => true],
                ['icon' => 'slack-hash', 'label' => 'Predialysis', 'type' => '#', 'route' => '#predialysis-evaluation', 'can' => true],
                ['icon' => 'slack-hash', 'label' => 'Monitoring', 'type' => '#', 'route' => '#monitoring', 'can' => true],
                ['icon' => 'patient', 'label' => 'Patients', 'route' => route('patients'), 'can' => true],
                ['icon' => 'clinic', 'label' => 'Clinics', 'route' => route('clinics'), 'can' => true],
                ['icon' => 'procedure', 'label' => 'Procedures', 'route' => route('procedures.index'), 'can' => true],
            ],
            'action-menu' => [
                //                ['icon' => 'paper-plain', 'action' => 'submit', 'label' => 'Submit'],
            ],
            'breadcrumbs' => $this->getBreadcrumbs([
                ['label' => 'Case Record', 'route' => route('procedures.acute-hemodialysis.edit', app(Hashids::class)->encode($order->case_record_id))],
            ]),
        ];

        return [
            'flash' => $flash,
            'content' => $order->form,
            'configs' => [
                'covid' => [
                    'hn' => $order->patient->hn,
                    'cid' => $order->patient->profile['document_id'],
                    'route_lab' => route('resources.api.covid-lab'),
                    'route_vaccine' => route('resources.api.covid-vaccine'),
                ],
            ],
        ];
    }
}
