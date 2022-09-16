<?php

use App\Http\Controllers\Procedures\AcuteHemodialysis\CaseRecordCompleteController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\CaseRecordController;
use Illuminate\Support\Facades\Route;

// Case
Route::get('/', [CaseRecordController::class, 'index'])
    ->can('view_any_acute_hemodialysis_cases')
    ->name('index');
Route::post('/', [CaseRecordController::class, 'store'])
    ->can('create_acute_hemodialysis_case')
    ->name('store');
/* @TODO split to show */
Route::get('/{hashedKey}/edit', [CaseRecordController::class, 'edit'])
    ->can('view_any_acute_hemodialysis_cases')
    ->name('edit');
Route::patch('/{hashedKey}', [CaseRecordController::class, 'update'])
    ->name('update');
Route::delete('/{hashedKey}', [CaseRecordController::class, 'destroy'])
    ->name('destroy');
Route::post('/{hashedKey}/complete', CaseRecordCompleteController::class)
    ->name('complete');
Route::put('/{hashedKey}/addendum', CaseRecordCompleteController::class)
    ->name('addendum');

// orders
