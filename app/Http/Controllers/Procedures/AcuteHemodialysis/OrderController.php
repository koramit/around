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
        $note = (new OrderStoreAction)(data: $request->all(), user: $request->user());

        // if want json return $note

        return redirect()->route('procedures.acute-hemodialysis.orders.edit', $note->hashed_key);
    }

    public function edit(string $hashedKey, Request $request)
    {
        $data = (new OrderEditAction)(hashedKey: $hashedKey, user: $request->user());

        // if want json return $data

        $this->setFlash($data['flash']);

        return Inertia::render('Procedures/AcuteHemodialysis/OrderEdit', [
            'orderForm' => $data['orderForm'],
            'formConfigs' => $data['formConfigs'],
        ]);
    }

    public function update(string $hashedKey, Request $request)
    {
        $status = (new OrderUpdateAction)(data: $request->all(), hashedKey: $hashedKey, user: $request->user());

        // if want json return $data

        return ['ok' => $status];
    }
}
