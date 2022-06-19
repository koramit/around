<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\OrderStoreAction;
use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $note = (new OrderStoreAction)($request->all(), $request->user()->id);

        // if want json return $note

        return redirect()->route('procedures.acute-hemodialysis.orders.edit', $note->hashed_key);
    }

    public function edit($hashedKey)
    {
        return Note::query()->findByUnhashKey($hashedKey)->firstOrFail();
    }
}
