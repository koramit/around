<?php

use App\Http\Controllers\Wards\WardController;
use Illuminate\Support\Facades\Route;

Route::get('/', WardController::class)
    ->name('index');

// KT ward registry
Route::prefix('kt-admission')
    ->name('kt-admission.')
    ->group(function () {
        require __DIR__.'/wards/kt_admission.php';
    });
