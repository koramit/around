<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\OrderSubmitAction;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class OrderSubmitController extends Controller
{
    public function __invoke($hashedKey, Request $request)
    {
        $data = (new OrderSubmitAction)(data: $request->all(), hashedKey: $hashedKey, user: $request->user());

        if ($request->wantsJson()) {
            return $data;
        }

        if ($previous = $request->session()->pull('acute-hd-order-edit-previous-route')) {
            try {
                return redirect()->route($previous)->with('message', $data['message']);
            } catch (Exception) {
            }
        }

        return redirect()->route('procedures.acute-hemodialysis.edit', $data['case'])->with('message', $data['message']);
    }
}
