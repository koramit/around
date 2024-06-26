<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\RegisteredWIthEmailController;
use App\Http\Controllers\Auth\SocialLinkController;
use App\Http\Controllers\Auth\SocialLoginController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->middleware(['locale', 'no-in-app-allow'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->name('login.store');
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->middleware(['locale', 'no-in-app-allow'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store'])
        ->name('register.store');
    Route::get('register-with-email', [RegisteredWIthEmailController::class, 'create'])
        ->name('register-with-email');
    Route::post('register-with-email', [RegisteredWIthEmailController::class, 'store'])
        ->name('register-with-email.store');

    Route::get('social-login/{provider}', [SocialLoginController::class, 'create'])
        ->name('social-login.create');
    Route::get('social-login/{provider}/callback', [SocialLoginController::class, 'store'])
        ->name('social-login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('check-timeout', [AuthenticatedSessionController::class, 'update'])
        ->name('check-timeout');
    Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('social-link/{provider}', [SocialLinkController::class, 'create'])
        ->name('social-link.create');
    Route::get('social-link/{provider}/callback', [SocialLinkController::class, 'store'])
        ->name('social-link.store');

    Route::put('extends-session', [AuthenticatedSessionController::class, 'update'])
        ->name('extends-session');
});
