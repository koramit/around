<?php

namespace App\Http\Controllers\Wards\KidneyTransplantAdmission;

use App\Actions\Wards\KidneyTransplantAdmission\CaseRecordCancelAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CaseRecordCancelController extends Controller
{
    public function __invoke(string $hashedKey, Request $request)
    {
        $reply = (new CaseRecordCancelAction())(data: $request->all(), hashedKey: $hashedKey, user: $request->user());

        if ($request->wantsJson()) {
            return $reply;
        }

        return redirect()->route('wards.kt-admission.index');
    }
}
