<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\OrderSwapAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderSwapController extends Controller
{
    public function __invoke($hashedKey, Request $request)
    {
        $note = (new OrderSwapAction)(data: $request->all(), hashedKey: $hashedKey, user: $request->user());

        // if want json return $data

        return redirect()->route('procedures.acute-hemodialysis.edit', $note->caseRecord->hashed_key);
    }
}
