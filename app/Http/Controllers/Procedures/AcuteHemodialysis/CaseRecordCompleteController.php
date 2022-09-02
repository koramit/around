<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\CaseRecordCompleteAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CaseRecordCompleteController extends Controller
{
    public function __invoke(string $hashedKey, Request $request)
    {
        $message = (new CaseRecordCompleteAction())($request->all(), $hashedKey, $request->user());

        return redirect()->route('procedures.acute-hemodialysis.index')->with('message', $message);
    }
}
