<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\SlotAvailableController as AcuteHemodialysisSlotAvailableController;
use App\Http\Controllers\Resources\AdmissionController;
use App\Http\Controllers\Resources\AttendingStaffController;
use App\Http\Controllers\Resources\PatientRecentlyAdmissionController;
use App\Http\Controllers\Resources\WardController;
use App\Http\Controllers\TermsAndPoliciesController;
use App\Http\Controllers\UploadController;
use App\Models\Resources\Patient;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

// test hash id model route
Route::get('/patients/{patient:hn}', function (Patient $patient) {
    return $patient;
});

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
    // Route::get('/procedures', function () {
    //     return 'procedures';
    // })->name('procedures');
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

    Route::post('acute-hemodialysis-slot-available', AcuteHemodialysisSlotAvailableController::class)
         ->name('acute-hemodialysis-slot-available');
});

// uploads
Route::post('uploads', [UploadController::class, 'store'])
     ->middleware('auth')
     ->name('uploads.store');
Route::get('uploads/{path}/{filename}', [UploadController::class, 'show'])
     ->middleware('auth')
     ->name('uploads.show');

require __DIR__.'/procedures.php';

Route::get('validator', function () {
    $data = ['a' => 1, 'b' => 2, 'c' => '1234', 'd' => 'foo'];
    $rules = [
        'a' => 'required|numeric|integer',
        'b' => 'numeric|integer',
        'c' => 'string|max:5',
    ];
    $validated = Validator::make($data, $rules);

    if ($validated->errors()->count()) {
        return $validated->errors();
    }

    return $validated->validated();

    return $validated->errors();
});
