<?php

use App\Http\Controllers\Auth\AvatarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PreferenceController;
use Illuminate\Support\Facades\Route;

Route::post('/', [AvatarController::class, 'store'])->name('login');

Route::middleware('auth:sanctum')
    ->group(function () {
        Route::get('/', HomeController::class)->name('home');

        Route::get('preferences', [PreferenceController::class, 'show'])->name('preferences');
        Route::patch('preferences', [PreferenceController::class, 'update'])->name('preferences.update');

        Route::get('user', [AvatarController::class, 'show'])->name('user');
    });
