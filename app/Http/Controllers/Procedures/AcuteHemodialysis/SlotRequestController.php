<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\SlotRequestIndexAction;
use App\Http\Controllers\Controller;
use App\Models\CaseRecord;
use App\Traits\AppLayoutSessionFlashable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SlotRequestController extends Controller
{
    use AppLayoutSessionFlashable;

    public function index(Request $request)
    {
        $data = (new SlotRequestIndexAction)(user: $request->user());

        // if want json return $data

        $this->setFlash($data['flash']);
        unset($data['flash']);

        return Inertia::render('Procedures/AcuteHemodialysis/SlotRequestIndex', [...$data]);
    }

    public function case(Request $request)
    {
        $like = config('database.default') === 'pgsql' ? 'ilike' : 'like';
        $cases = CaseRecord::query()
                     ->where('meta->name', $like, '%'.$request->input('search').'%')
                     ->orWhere('meta->hn', $like, '%'.$request->input('search').'%')
                     ->get()
                     ->transform(fn ($c) => 'HN '.$c->meta['hn'].' '.$c->meta['name']);

        return $cases;
    }
}
