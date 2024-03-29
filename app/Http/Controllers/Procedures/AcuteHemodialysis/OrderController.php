<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\OrderDestroyAction;
use App\Actions\Procedures\AcuteHemodialysis\OrderEditAction;
use App\Actions\Procedures\AcuteHemodialysis\OrderShowAction;
use App\Actions\Procedures\AcuteHemodialysis\OrderStoreAction;
use App\Actions\Procedures\AcuteHemodialysis\OrderUpdateAction;
use App\Http\Controllers\Controller;
use App\Traits\AppLayoutSessionFlashable;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    use AppLayoutSessionFlashable;

    public function __construct(Request $request)
    {
        if (! $request->wantsJson()) {
            $this->middleware(['page-transition', 'locale', 'no-in-app-allow'])->only(['edit', 'show']);
        }
    }

    /**
     * @throws Exception
     */
    public function store(Request $request)
    {
        $data = (new OrderStoreAction)(data: $request->all(), user: $request->user());

        if ($request->wantsJson()) {
            return $data;
        }

        if (isset($data['message'])) {
            session()->flash(key: 'message', value: $data['message']);
        }

        return redirect()->route('procedures.acute-hemodialysis.orders.edit', $data['note']);
    }

    public function edit(string $hashedKey, Request $request)
    {
        $data = (new OrderEditAction)(hashedKey: $hashedKey, user: $request->user());

        if ($request->wantsJson()) {
            return $data;
        }

        $this->setFlash($data['flash']);
        unset($data['flash']);

        $request->session()->put('acute-hd-order-edit-previous-route', app('router')->getRoutes()->match($request->create(url()->previous()))->getName());

        return Inertia::render('Procedures/AcuteHemodialysis/OrderEdit', [...$data]);
    }

    public function update(string $hashedKey, Request $request)
    {
        return (new OrderUpdateAction)(data: $request->all(), hashedKey: $hashedKey, user: $request->user());
    }

    public function destroy(string $hashedKey, Request $request)
    {
        $reply = (new OrderDestroyAction)(data: $request->all(), hashedKey: $hashedKey, user: $request->user());

        if ($request->wantsJson()) {
            return $reply;
        }

        /* @TODO check if redirect somewhere else needed */
        return back()->with('message', $reply);
    }

    public function show(string $hashedKey, Request $request)
    {
        $data = (new OrderShowAction)(hashedKey: $hashedKey, user: $request->user());

        if ($request->wantsJson()) {
            return $data;
        }

        $this->setFlash($data['flash']);
        unset($data['flash']);

        return Inertia::render('Procedures/AcuteHemodialysis/OrderShow', [...$data]);
    }
}
