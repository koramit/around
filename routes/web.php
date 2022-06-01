<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\TermsAndPoliciesController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

// pages
Route::get('terms-and-policies', TermsAndPoliciesController::class)
     ->name('terms');

// locales
Route::get('/locale/{locale}', [LocalizationController::class, 'store']);
Route::post('/translations', [LocalizationController::class, 'show']);

// home
Route::middleware(['auth'])->group(function () {
    Route::get('/', HomeController::class)->name('home');
    Route::get('/preferences', [PreferenceController::class, 'show'])->name('preferences');

    //
    Route::get('/clinics', function () {
        return 'clinics';
    })->name('clinics');
    Route::get('/patients', function () {
        return 'patients';
    })->name('patients');
    Route::get('/procedures', function () {
        return 'procedures';
    })->name('procedures');
});
