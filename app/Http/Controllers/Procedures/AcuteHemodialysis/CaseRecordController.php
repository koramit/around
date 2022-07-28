<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\CaseRecordEditAction;
use App\Actions\Procedures\AcuteHemodialysis\CaseRecordIndexAction;
use App\Actions\Procedures\AcuteHemodialysis\CaseRecordStoreAction;
use App\Actions\Procedures\AcuteHemodialysis\CaseRecordUpdateAction;
use App\Http\Controllers\Controller;
use App\Traits\AppLayoutSessionFlashable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CaseRecordController extends Controller
{
    use AppLayoutSessionFlashable;

    // @TODO policy
    public function index(Request $request)
    {
        $data = (new CaseRecordIndexAction)(filters: $request->all(), user: $request->user());

        // if request want json then return $data

        $this->setFlash($data['flash']);
        unset($data['flash']);

        session()->put('acute-hemodialysis-last-index-section-route', $request->route()->getName());

        return Inertia::render('Procedures/AcuteHemodialysis/CaseIndex', [...$data]);
    }

    public function store(Request $request)
    {
        $case = (new CaseRecordStoreAction)(data: $request->all(), user: $request->user());

        // if request want json then return $case

        return redirect()->route('procedures.acute-hemodialysis.edit', $case->hashed_key);
    }

    public function edit($hashedKey, Request $request)
    {
        $data = (new CaseRecordEditAction)(hashed: $hashedKey, user: $request->user());

        // if request want json then return $data

        $this->setFlash($data['flash']);
        unset($data['flash']);

        if ($request->has('section')) {
            $data['gotoSection'] = '#'.$request->input('section');
        }

        return Inertia::render('Procedures/AcuteHemodialysis/CaseEdit', [...$data]);
    }

    public function update($hashedKey, Request $request)
    {
        $status = (new CaseRecordUpdateAction)(data: $request->all(), hashedKey: $hashedKey, user: $request->user());

        // if request want json then return $data

        return ['ok' => $status];
    }
}
