<?php

namespace App\Actions\Labs\KidneyTransplantHLATyping;

use App\Models\Notes\KidneyTransplantHLATypingReportNote;
use App\Traits\AvatarLinkable;
use App\Traits\FirstNameAware;
use App\Traits\FlashDataGeneratable;
use App\Traits\HomePageSelectable;

class ReportIndexAction
{
    use HomePageSelectable, AvatarLinkable, FlashDataGeneratable, FirstNameAware;

    public function __invoke(array $filters, mixed $user, string $routeName = 'home')
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $reports = KidneyTransplantHLATypingReportNote::query()
            ->with('patient')
            ->withAuthorName()
            ->orderByDesc('date_note')
            ->paginate($user->items_per_page)
            ->withQueryString()
            ->through(function ($report) {
                return [
                    'hn' => $report->patient->hn,
                    'patient_name' => $report->patient->full_name,
                    'request' => $report->request,
                    'date_serum' => $report->date_note->format('M j Y'),
                    'status' => $report->status,
                    'author' => $this->getFirstName($report->author_name),
                    'routes' => [
                        'edit' => $report->edit_route,
                    ]
                ];
            });

        $flash = $this->getFlash(title: 'KT HLA Typing Report', user: $user);
        $flash['action-menu'][] = $this->getSetHomePageActionMenu(routeName: $routeName,userHomePage: $user->home_page);

        return [
            'filters' => [
                'search' => $filters['search'] ?? '',
                'scope' => $filters['scope'] ?? 'all',
            ],
            'configs' => [
                'scopes' => ['draft', 'approved', 'canceled', 'all'],
            ],
            'can' => [
                'create' => $user->can('create_kt_hla_typing_report'),
            ],
            'reports' => $reports,
            'routes' => [
                'patientShow' => route('resources.api.patients.show'),
                'reportStore' => route('labs.kt-hla-typing.reports.store'),
            ],
            'flash' =>$flash,
        ];
    }
}
