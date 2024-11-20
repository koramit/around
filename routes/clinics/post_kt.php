<?php

use App\Http\Controllers\Clinics\PostKT\CaseRecordController;
use Illuminate\Support\Facades\Route;

// cases
Route::get('/', [CaseRecordController::class, 'index'])
    ->name('index');
Route::post('/', [CaseRecordController::class, 'store'])
    ->name('store');
Route::get('/{hashedKey}/edit', [CaseRecordController::class, 'edit'])
    ->name('edit');
Route::get('/{hashedKey}/annual-update', [CaseRecordController::class, 'annualUpdate'])
    ->name('annual-update');
