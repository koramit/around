<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $tickets = SupportTicket::query()
            ->orderByDesc('created_at')
            ->when(!$user->hasRole('root'), fn ($q) => $q->where('requester_id', $user->id))
            ->paginate($user->items_per_page)
            ->withQueryString()
            ->through(fn ($f) => [
                'feedback' => $f->feedback,
                'when' => $f->created_at->diffForHumans(now()),
            ]);

        session()->flash('page-title', __('Support Tickets'));
        session()->flash('main-menu-links', collect([
            ['icon' => 'patient', 'label' => 'Patients', 'route' => route('patients'), 'can' => true],
            ['icon' => 'clinic', 'label' => 'Clinics', 'route' => route('clinics'), 'can' => true],
            ['icon' => 'procedure', 'label' => 'Procedures', 'route' => route('procedures.index'), 'can' => true],
        ])->filter(fn ($link) => $link['can']));

        return Inertia::render('FeedbackPage', [
            'tickets' => $tickets,
            'configs' => [
                'store_endpoint' => route('feedback.store'),
            ],
        ]);
    }

    public function store(){}
    public function destroy(){}
}
