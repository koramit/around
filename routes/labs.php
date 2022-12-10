<?php

use App\Http\Controllers\Labs\LabController;
use Illuminate\Support\Facades\Route;

Route::get('/', LabController::class)
    ->name('index');

// KT HLA Typing
Route::prefix('kt-hla-typing')
    ->name('kt-hla-typing.')
    ->group(function () {
        require __DIR__.'/labs/kt_hla_typing.php';
    });
