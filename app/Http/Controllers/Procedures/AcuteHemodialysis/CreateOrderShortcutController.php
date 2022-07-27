<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\CreateOrderShortcutAction;
use App\Http\Controllers\Controller;
//use App\Models\Registries\AcuteHemodialysisCaseRecord;
use Illuminate\Http\Request;

class CreateOrderShortcutController extends Controller
{
    public function __invoke(string $hashedKey, Request $request)
    {
        (new CreateOrderShortcutAction)($hashedKey, $request->user());

        return redirect()->route('procedures.acute-hemodialysis.schedule');
    }
}
