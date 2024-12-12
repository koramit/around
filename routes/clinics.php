<?php

use App\Http\Controllers\Clinics\ClinicController;
use Illuminate\Support\Facades\Route;

Route::get('/', ClinicController::class)
    ->middleware(['page-transition', 'locale', 'no-in-app-allow'])
    ->name('index');

// Post KT Clinic
Route::prefix('post-kt')
    ->name('post-kt.')
    ->group(function () {
        require __DIR__.'/clinics/post_kt.php';
    });
