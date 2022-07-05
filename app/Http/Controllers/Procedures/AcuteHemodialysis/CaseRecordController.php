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

    public function index(Request $request)
    {
        $data = (new CaseRecordIndexAction)(filters: $request->all(), user: $request->user());

        // if want json return $data

        $this->setFlash($data['flash']);
        unset($data['flash']);

        return Inertia::render('Procedures/AcuteHemodialysis/CaseIndex', [...$data]);
    }

    public function store(Request $request)
    {
        $case = (new CaseRecordStoreAction)(data: $request->all(), user: $request->user());

        // if want json return $case

        return redirect()->route('procedures.acute-hemodialysis.edit', $case->hashed_key);
    }

    public function edit($hashedKey, Request $request)
    {
        $data = (new CaseRecordEditAction)(hashed: $hashedKey, user: $request->user());

        // if want json return $data

        $this->setFlash($data['flash']);

        return Inertia::render('Procedures/AcuteHemodialysis/CaseEdit', [
            'caseRecordForm' => $data['caseRecordForm'],
            'formConfigs' => $data['formConfigs'],
            'orders' => $data['orders'],
        ]);
    }

    public function update($hashedKey, Request $request)
    {
        $status = (new CaseRecordUpdateAction)(data: $request->all(), hashedKey: $hashedKey, user: $request->user());

        // if want json return $data

        return ['ok' => $status];
    }
}
