<?php

namespace App\Http\Controllers\Procedures;

use App\Http\Controllers\Controller;
use App\Models\Resources\Registry;
use App\Traits\HomePageSelectable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class ProcedureController extends Controller
{
    use HomePageSelectable;

    public function __invoke(Request $request)
    {
        $user = $request->user();
        Session::flash('page-title', 'Procedures');
        Session::flash('main-menu-links', [
            ['icon' => 'patient', 'label' => 'Patients', 'route' => route('patients'), 'can' => true],
            ['icon' => 'clinic', 'label' => 'Clinics', 'route' => route('clinics'), 'can' => true],
            ['icon' => 'procedure', 'label' => 'Procedures', 'route' => route('procedures.index'), 'can' => true],
            // ['icon' => 'graduation-cap', 'label' => 'Kidney club', 'route' => route('kidney-club'), 'can' => true],
        ]);
        Session::flash('action-menu', [
            $this->getSetHomePageActionMenu($request->route()->getname(), $user),
        ]);

        $procedureNameRoute = cache()->rememberForever('procedure-name-route', function () {
            return Registry::query()
                ->where('route', 'like', 'procedures.%')
                ->get()
                ->transform(fn (Registry $r) => [
                    'name' => $r->name,
                    'route' => $r->route,
                ]);
        });

        if ($user->registry_names->count() === 0) {
            abort(403);
        }

        $procedures = $procedureNameRoute->filter(fn ($r) => $user->registry_names->contains($r['name']))->values();

        if ($procedures->count() === 0) {
            abort(403);
        }

        if ($procedures->count() === 1) {
            return redirect()->route($procedures[0]['route']);
        }

        return Inertia::render('Procedures/MainIndex', [
            'routes' => [
                'acute-hemodialysis' => route('procedures.acute-hemodialysis.index'),
            ],
        ]);
    }
}
