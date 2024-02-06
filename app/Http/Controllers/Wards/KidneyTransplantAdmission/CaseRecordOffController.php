<?php

namespace App\Http\Controllers\Wards\KidneyTransplantAdmission;

use App\Actions\Wards\KidneyTransplantAdmission\CaseRecordOffAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CaseRecordOffController extends Controller
{
    public function __invoke(string $hashedKey, Request $request)
    {
        $reply = (new CaseRecordOffAction())(data: $request->all(), hashedKey: $hashedKey, user: $request->user());

        if ($request->wantsJson()) {
            return $reply;
        }

        return redirect()->route('wards.kt-admission.index');
    }
}
