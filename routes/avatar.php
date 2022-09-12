<?php

use App\Http\Controllers\Auth\AvatarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\CaseRecordController as AcuteHemodialysisCaseController;
use App\Http\Controllers\Procedures\ProcedureController;
use Illuminate\Support\Facades\Route;

Route::post('/', [AvatarController::class, 'store'])->name('login');

Route::middleware('auth:sanctum')
    ->group(function () {
        Route::get('/', HomeController::class)->name('home');
        Route::get('user', [AvatarController::class, 'show'])->name('user');

        // preferences
        Route::get('preferences', [PreferenceController::class, 'show'])->name('preferences');
        Route::patch('preferences', [PreferenceController::class, 'update'])->name('preferences.update');
    });

// procedures
Route::middleware('auth:sanctum')
    ->get('procedures', ProcedureController::class)
    ->name('procedures.index');

// Acute HD
Route::middleware('auth:sanctum')
    ->prefix('procedures/acute-hemodialysis')
    ->name('procedures.acute-hemodialysis.')
    ->group(function () {
        Route::get('/', [AcuteHemodialysisCaseController::class, 'index'])
            ->can('view_any_acute_hemodialysis_cases')
            ->name('index');
        // store next
        Route::get('/{hashedKey}/edit', [AcuteHemodialysisCaseController::class, 'edit'])
            ->can('view_any_acute_hemodialysis_cases')
            ->name('edit');

    });
