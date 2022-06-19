<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\OrderEditAction;
use App\Actions\Procedures\AcuteHemodialysis\OrderStoreAction;
use App\Actions\Procedures\AcuteHemodialysis\OrderUpdateAction;
use App\Http\Controllers\Controller;
use App\Traits\AppLayoutSessionFlashable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    use AppLayoutSessionFlashable;

    public function store(Request $request)
    {
        $note = (new OrderStoreAction)($request->all(), $request->user()->id);

        // if want json return $note

        return redirect()->route('procedures.acute-hemodialysis.orders.edit', $note->hashed_key);
    }

    public function edit($hashedKey)
    {
        $data = (new OrderEditAction)($hashedKey);

        // if want json return $data

        $this->setFlash($data['flash']);

        return Inertia::render('Procedures/AcuteHemodialysis/OrderEdit', [
            'orderForm' => $data['orderForm'],
            'formConfigs' => $data['formConfigs'],
        ]);
    }

    public function update($hashedKey, Request $request)
    {
        $status = (new OrderUpdateAction)(data: $request->all(), hashedKey: $hashedKey, userId: $request->user()->id);

        // if want json return $data

        return ['ok' => $status];
    }
}
