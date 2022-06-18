<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\CaseRecordEditAction;
use App\Actions\Procedures\AcuteHemodialysis\CaseRecordIndexAction;
use App\Actions\Procedures\AcuteHemodialysis\CaseRecordStoreAction;
use App\Actions\Procedures\AcuteHemodialysis\CaseRecordUpdateAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CaseRecordController extends Controller
{
    public function index(Request $request)
    {
        $data = (new CaseRecordIndexAction)($request->all());

        // if want json return $data

        return Inertia::render('Procedures/AcuteHemodialysis/CaseIndex', [
            'cases' => $data['cases'],
            'filters' => $data['filters'],
            'routes' => $data['routes'],
        ]);
    }

    public function store(Request $request)
    {
        $case = (new CaseRecordStoreAction)($request->all(), $request->user()->id);

        // if want json return $case

        return redirect()->route('procedures.acute-hemodialysis.edit', $case->hashed_key);
    }

    public function edit($hashedKey)
    {
        $data = (new CaseRecordEditAction)($hashedKey);

        // if want json return $data

        return Inertia::render('Procedures/AcuteHemodialysis/CaseEdit', [
            'caseRecordForm' => $data['caseRecordForm'],
            'formConfigs' => $data['formConfigs'],
            'orders' => $data['orders'],
        ]);
    }

    public function update($hashedKey, Request $request)
    {
        $status = (new CaseRecordUpdateAction)(data: $request->all(), hashedKey: $hashedKey, userId: $request->user()->id);

        // if want json return $data

        return ['ok' => $status];
    }
}
