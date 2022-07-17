<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\SlotRequestDestroyAction;
use App\Actions\Procedures\AcuteHemodialysis\SlotRequestIndexAction;
use App\Actions\Procedures\AcuteHemodialysis\SlotRequestUpdateAction;
use App\Http\Controllers\Controller;
use App\Models\Registries\AcuteHemodialysisCaseRecord;
use App\Traits\AppLayoutSessionFlashable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SlotRequestController extends Controller
{
    use AppLayoutSessionFlashable;

    public function index(Request $request)
    {
        $data = (new SlotRequestIndexAction)(user: $request->user());

        // if request want json return $data

        $this->setFlash($data['flash']);
        unset($data['flash']);

        return Inertia::render('Procedures/AcuteHemodialysis/SlotRequestIndex', [...$data]);
    }

    public function update(string $hashedKey, Request $request)
    {
        $reply = (new SlotRequestUpdateAction)(hashedKey: $hashedKey, data: $request->all(), user: $request->user());

        return back()->with('message', $reply);
    }

    public function destroy(string $hashedKey, Request $request)
    {
        $reply = (new SlotRequestDestroyAction())(hashedKey: $hashedKey, data: $request->all(), user: $request->user());

        return back()->with('message', $reply);
    }

    public function case(Request $request)
    {
        $ilike = config('database.ilike');

        return AcuteHemodialysisCaseRecord::query()
                    ->whereDoesntHave('orders', fn ($q) => $q->activeStatuses())
                    ->where( fn ($q) => $q->where('meta->name', $ilike, '%'.$request->input('search').'%')
                        ->orWhere('meta->hn', $ilike, '%'.$request->input('search').'%')
                    )->get()
                    ->transform(fn ($c) => "HN {$c->meta['hn']} {$c->meta['name']}");
    }
}
