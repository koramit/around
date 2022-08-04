<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\OrderCopyAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderCopyController extends Controller
{
    public function __invoke(string $hashedKey, Request $request)
    {
        return (new OrderCopyAction)($hashedKey, $request->user());
    }
}
