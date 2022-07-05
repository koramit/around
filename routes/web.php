<?php

Barryvdh\Debugbar\Facades\Debugbar::disable();

use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\Resources\AdmissionController;
use App\Http\Controllers\Resources\AttendingStaffController;
use App\Http\Controllers\Resources\PatientRecentlyAdmissionController;
use App\Http\Controllers\Resources\WardController;
use App\Http\Controllers\TermsAndPoliciesController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

// pages
Route::get('terms-and-policies', TermsAndPoliciesController::class)
     ->name('terms');

// locales
Route::get('/locale/{locale}', [LocalizationController::class, 'store']);
Route::post('/translations', [LocalizationController::class, 'show']);

// common
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
});

// resurces
Route::middleware('auth')->name('resources.api.')->group(function () {
    Route::post('admissions', AdmissionController::class)
         ->name('admissions.show');
    Route::post('patient-recently-admission', PatientRecentlyAdmissionController::class)
         ->name('patient-recently-admission.show');
    Route::get('wards', WardController::class)
         ->name('wards');
    Route::get('staffs', AttendingStaffController::class)
         ->name('staffs');
});

// uploads
Route::post('uploads', [UploadController::class, 'store'])
     ->middleware('auth')
     ->name('uploads.store');
Route::get('uploads/{path}/{filename}', [UploadController::class, 'show'])
     ->middleware('auth')
     ->name('uploads.show');

// feedback
Route::get('feedback', [FeedbackController::class, 'index'])
     ->middleware(['auth'])
     ->name('feedback');
Route::post('feedback', [FeedbackController::class, 'store'])
     ->middleware(['auth'])
     ->name('feedback.store');

require __DIR__.'/procedures.php';
