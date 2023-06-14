<?php

namespace App\Http\Controllers\Wards\KidneyTransplantAdmission;

use App\Actions\Wards\KidneyTransplantAdmission\CaseRecordCompleteAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CaseRecordCompleteController extends Controller
{
    public function __invoke(string $hashedKey, Request $request)
    {
        $message = (new CaseRecordCompleteAction())(data: $request->all(), hashedKey: $hashedKey, user: $request->user());

        if ($request->wantsJson()) {
            return $message;
        }

        return redirect()->route('wards.kt-admission.index')->with('message', $message);
    }
}
