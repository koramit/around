<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\DocumentChangeRequest;
use App\Models\User;
use App\Traits\AcuteHemodialysis\OrderShareValidatable;

class SlotRequestIndexAction extends AcuteHemodialysisAction
{
    use OrderShareValidatable;

    public function __invoke(User $user): array
    {
        $slot = (new ScheduleIndexAction)(dateNote: null, user: $user)['slot'];
        $requests = DocumentChangeRequest::query()
            ->with(['changeable', 'requester'])
            ->where(fn ($q) => $q->where('requester_id', $user->id))
            ->where('changeable_type', 'App\Models\Notes\AcuteHemodialysisOrderNote')
            ->get()
            ->transform(function ($request) {
                return [
                    'hn' => $request->changeable->meta['hn'],
                    'patient_name' => $request->changeable->meta['name'],
                    'request' => $request->changeable->getChangRequestText($request->changes),
                    'requester' => $request->requester->first_name,
                ];
            });

        return [
            'requests' => $requests,
            'slot' => $slot,
            'flash' => [
                'page-title' => 'Acute Hemodialysis - Slot Request',
                'main-menu-links' => $this->MENU,
                'navs' => $this->NAVS,
                'action-menu' => [],
            ],
            'endpoints' => [
                'resources_api_wards' => route('resources.api.wards'),
                'resources_api_staffs' => route('resources.api.staffs'),
                'acute_hemodialysis_slot_available' => route('procedures.acute-hemodialysis.slot-available'),
                'cases' => route('procedures.acute-hemodialysis.slot-requests.case'),
                'orders_store' => route('procedures.acute-hemodialysis.orders.store'),
            ],
            'configs' => [
                'staffs_scope_params' => '&division_id='.$this->STAFF_DIVISION_ID,
                'in_unit_dialysis_types' => $this->IN_UNIT,
                'out_unit_dialysis_types' => $this->OUT_UNIT,
                'patient_types' => $this->PATIENT_TYPES,
            ],
        ];
    }
}
