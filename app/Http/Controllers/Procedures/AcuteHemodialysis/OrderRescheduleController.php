<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\OrderRescheduleAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderRescheduleController extends Controller
{
    public function __invoke($hashedKey, Request $request)
    {
        $data = (new OrderRescheduleAction)(data: $request->all(), hashedKey: $hashedKey, user: $request->user());

        // if want json return $data

        return redirect()->route('procedures.acute-hemodialysis.edit', $data['note']->caseRecord->hashed_key);
    }
}
