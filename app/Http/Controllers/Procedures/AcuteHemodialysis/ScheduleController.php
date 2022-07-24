<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\ScheduleIndexAction;
use App\Http\Controllers\Controller;
use App\Traits\AppLayoutSessionFlashable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    use AppLayoutSessionFlashable;

    public function __invoke(Request $request)
    {
        $validated = $request->validate(['ref_date' => 'nullable|date']);

        $data = (new ScheduleIndexAction)(refDate: $validated['ref_date'] ?? null, user: $request->user());

        // if request want json return $data

        $this->setFlash($data['flash']);
        unset($data['flash']);

        return Inertia::render('Procedures/AcuteHemodialysis/ScheduleIndex', [...$data]);
    }
}
