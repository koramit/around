<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Extensions\Auth\AvatarUser;
use App\Models\DocumentChangeRequests\AcuteHemodialysisSlotRequest;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\User;
use App\Traits\AcuteHemodialysis\OrderShareValidatable;
use App\Traits\HomePageSelectable;

class SlotRequestIndexAction extends AcuteHemodialysisAction
{
    use HomePageSelectable, OrderShareValidatable;

    public function __invoke(User|AvatarUser $user, string $routeName): array
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
        }

        $requests = AcuteHemodialysisSlotRequest::query()
            ->with(['changeable:id,date_note,meta'])
            ->withRequesterName()
            ->where('submitted_at', '>=', now()->tz($this->TIMEZONE)->addDays(-7))
            ->latest('submitted_at')
            ->paginate($user->items_per_page)
            ->withQueryString()
            ->through(function ($request) use ($user) {
                /** @var AcuteHemodialysisOrderNote $changeable */
                $changeable = $request->changeable;
                $actions = collect([
                    [
                        'label' => 'Approve',
                        'as' => 'button',
                        'icon' => 'check-circle',
                        'theme' => 'success',
                        'route' => route('procedures.acute-hemodialysis.slot-requests.approve', $request->hashed_key),
                        'name' => 'approve-request',
                        'can' => $user->can('approve', $request),
                    ],
                    [
                        'label' => 'Disapprove',
                        'as' => 'button',
                        'icon' => 'times-circle',
                        'theme' => 'danger',
                        'route' => route('procedures.acute-hemodialysis.slot-requests.approve', $request->hashed_key),
                        'name' => 'disapprove-request',
                        'config' => [
                            'heading' => 'HN '.$changeable->meta['hn'].' '.$changeable->meta['name'],
                            'confirmText' => 'Disapprove '.$request->change_request_text,
                            'requireReason' => true,
                        ],
                        'can' => $user->can('approve', $request),
                    ],
                    [
                        'label' => 'Cancel',
                        'as' => 'button',
                        'icon' => 'trash',
                        'theme' => 'warning',
                        'route' => route('procedures.acute-hemodialysis.slot-requests.cancel', $request->hashed_key),
                        'name' => 'cancel-request',
                        'config' => [
                            'heading' => 'HN '.$changeable->meta['hn'].' '.$changeable->meta['name'],
                            'confirmText' => 'Cancel '.$request->change_request_text,
                            'requireReason' => true,
                        ],
                        'can' => $user->can('cancel', $request),
                    ],
                ])->filter(fn ($action) => $action['can'])->values()->all();

                return [
                    'hn' => $changeable->meta['hn'],
                    'patient_name' => $changeable->meta['name'],
                    'request' => $request->change_request_text,
                    'requester' => $this->getFirstName($request->requester_name),
                    'actions' => $actions,
                    'status' => $this->styleStatusBadge($request->status, 'request'),
                ];
            });

        $flash = $this->getFlash('Acute Hemodialysis - Slot Request', $user);
        $flash['navs'] = $this->NAVS;
        $flash['action-menu'][] = $this->getSetHomePageActionMenu($routeName, $user->home_page);

        return [
            'requests' => $requests,
            'flash' => $flash,
            'endpoints' => [
                'resources_api_wards' => route('resources.api.wards'),
                'resources_api_staffs' => route('resources.api.people'),
                'acute_hemodialysis_slot_available' => route('procedures.acute-hemodialysis.slot-available'),
                'cases' => route('procedures.acute-hemodialysis.idle-cases'),
                'orders_store' => route('procedures.acute-hemodialysis.orders.store'),
            ],
            'configs' => [
                'staffs_scope_params' => $this->STAFF_SCOPE_PARAMS,
                'in_unit_dialysis_types' => $this->IN_UNIT,
                'out_unit_dialysis_types' => $this->OUT_UNIT,
                'patient_types' => $this->PATIENT_TYPES,
            ],
        ];
    }
}
