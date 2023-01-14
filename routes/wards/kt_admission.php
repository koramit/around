<?php

use App\Http\Controllers\Wards\KidneyTransplantAdmission\CaseRecordController;
use Illuminate\Support\Facades\Route;

// cases
Route::get('/', [CaseRecordController::class, 'index'])
    ->middleware(['can:view_any_kt_admission_cases'])
    ->name('index');

Route::post('/', [CaseRecordController::class, 'store'])
    ->middleware(['can:create_kt_admission_case'])
    ->name('store');

Route::get('/{hashedKey}/edit', [CaseRecordController::class, 'edit'])
    ->middleware(['can:create_kt_admission_case'])
    ->name('edit');
