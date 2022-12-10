<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Extensions\Auth\AvatarUser;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\Resources\Ward;
use App\Models\User;
use App\Traits\AcuteHemodialysis\OrderFormConfigsShareable;

class OrderEditAction extends AcuteHemodialysisAction
{
    use OrderFormConfigsShareable;

    public function __invoke(string $hashedKey, User|AvatarUser $user): array
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
        }

        /** @var AcuteHemodialysisOrderNote $note */
        $note = AcuteHemodialysisOrderNote::query()->withPlaceName(Ward::class)->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('edit', $note)) {
            abort(403);
        }

        $can = [
            'update' => $user->can('update', $note),
            'reschedule' => $user->can('reschedule', $note),
            'copy' => ! $note->meta['submitted'],
        ];

        $flash = $this->initOrderFlash($note, $user);
        $flash['action-menu'] = [
            [
                'as' => 'button',
                'icon' => 'paper-plain',
                'name' => 'submit',
                'label' => 'Submit',
                'can' => $user->can('submit', $note),
            ],
            $this->getSubscriptionActionMenu($note, $user),
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
                'routes' => [
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
