<?php

use App\Http\Controllers\Wards\KidneyTransplantAdmission\CaseRecordCompleteController;
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
    ->name('edit');

Route::post('/{hashedKey}/complete', CaseRecordCompleteController::class)
    ->name('complete');

Route::put('/{hashedKey}/addendum', [CaseRecordController::class, 'edit'])
    ->name('addendum');

Route::delete('/{hashedKey}/cancel', [CaseRecordController::class, 'edit'])
    ->name('cancel');

Route::patch('/{hashedKey}', [CaseRecordController::class, 'update'])
    ->name('update');

Route::delete('/{hashedKey}', [CaseRecordController::class, 'destroy'])
    ->name('destroy');
