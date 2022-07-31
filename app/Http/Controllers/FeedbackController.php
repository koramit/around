<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $feedback = Feedback::query()
            ->orderByDesc('created_at')
            ->paginate($request->user()->items_per_page)
            ->withQueryString()
            ->through(fn ($f) => [
                'feedback' => $f->feedback,
                'when' => $f->created_at->diffForHumans(now()),
            ]);

        session()->flash('page-title', __('Feedback'));
        session()->flash('main-menu-links', collect([
            ['icon' => 'patient', 'label' => 'Patients', 'route' => route('patients'), 'can' => true],
            ['icon' => 'clinic', 'label' => 'Clinics', 'route' => route('clinics'), 'can' => true],
            ['icon' => 'procedure', 'label' => 'Procedures', 'route' => route('procedures.index'), 'can' => true],
        ])->filter(fn ($link) => $link['can']));

        return Inertia::render('FeedbackPage', [
            'feedback' => $feedback,
            'configs' => [
                'store_endpoint' => route('feedback.store'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['feedback' => 'required|string|max:512']);

        Feedback::query()->create(['feedback' => $validated['feedback']]);

        Log::notice("feedback\n".$validated['feedback']);

        return redirect()->route('feedback.index');
    }
}
