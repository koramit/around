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
        if (config('auth.gurads.web.provider') === 'avatar') {
            return []; // call api
        }

        $note = AcuteHemodialysisOrderNote::query()->withPlaceName(Ward::class)->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('edit', $note)) {
            abort(403);
        }

        $flash = [
            'page-title' => 'Acute HD Order '.$note->patient->profile['first_name'].' @ '.$note->date_note->format('d M Y'),
            'main-menu-links' => [
                ['icon' => 'arrow-circle-left', 'label' => 'Back', 'route' => route('procedures.acute-hemodialysis.edit', app(Hashids::class)->encode($note->case_record_id)), 'can' => true],
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
        ];

        return [
            'orderForm' => $note->form,
            'flash' => $flash,
            'formConfigs' => $this->FORM_CONFIGS + [
                'update_endpoint' => route('procedures.acute-hemodialysis.orders.update', $note->hashed_key),
                'submit_endpoint' => route('procedures.acute-hemodialysis.orders.submit', $note->hashed_key),
                'hn' => $note->meta['hn'],
                'an' => $note->meta['an'] ?? null,
                'dialysis_at' => $note->place_name,
                'dialysis_type' => $note->meta['dialysis_type'],
            ],
        ];
    }
}
