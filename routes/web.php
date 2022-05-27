<?php

use App\Http\Controllers\LocalizationController;
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
Route::get('/', function () {
    return 'home';
})->middleware(['auth'])->name('home');
