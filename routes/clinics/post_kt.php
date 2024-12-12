<?php

use App\Http\Controllers\Clinics\PostKT\CaseRecordController;
use Illuminate\Support\Facades\Route;

// cases
Route::get('/', [CaseRecordController::class, 'index'])
    ->middleware(['can:view_any_kt_survival_cases'])
    ->name('index');
Route::post('/', [CaseRecordController::class, 'store'])
    ->middleware(['can:create_kt_survival_case'])
    ->name('store');
Route::get('/{month}/month-cases', [CaseRecordController::class, 'monthCases'])
    ->middleware(['can:view_any_kt_survival_cases'])
    ->name('month-cases');
Route::get('/{hashedKey}/edit', [CaseRecordController::class, 'edit'])
    ->middleware(['can:view_any_kt_survival_cases'])
    ->name('edit');
Route::get('/{hashedKey}/annual-update', [CaseRecordController::class, 'annualUpdate'])
    ->name('annual-update');
Route::post('/{hashedKey}/annual-update', [CaseRecordController::class, 'annualUpdate'])
    ->name('annual-update');
Route::get('/{hashedKey}/annual-update-by-latest-cr', [CaseRecordController::class, 'annualUpdateByCr'])
    ->name('annual-update-by-latest-cr');
Route::put('/{hashedKey}/timestamp-update', [CaseRecordController::class, 'timestampUpdate'])
    ->name('timestamp-update');
Route::get('/{hashedKey}/timestamp-update-by-latest-cr', [CaseRecordController::class, 'timestampUpdateByCr'])
    ->name('timestamp-update-by-latest-cr');
Route::delete('/{hashedKey}', [CaseRecordController::class, 'destroy'])
    ->name('destroy');
Route::patch('/{hashedKey}', [CaseRecordController::class, 'update'])
    ->name('update');
