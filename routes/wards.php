<?php

use App\Http\Controllers\Wards\WardController;
use Illuminate\Support\Facades\Route;

Route::get('/', WardController::class)
    ->name('index');

// KT ward registry
Route::prefix('kt-ward-registry')
    ->name('kt-ward-registry.')
    ->group(function () {
        require __DIR__.'/wards/kt_ward_registry.php';
    });
