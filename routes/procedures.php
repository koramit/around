<?php

use App\Http\Controllers\Procedures\AcuteHemodialysis\CaseRecordController as AcuteHemodialysisCasesController;
use App\Http\Controllers\Procedures\ProcedureController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('procedures')->group(function () {
    Route::get('/', ProcedureController::class)->name('procedures');

    // Acute HD
    Route::prefix('acute-hemodialysis')
        ->name('procedures.acute-hemodialysis.')
        ->group(function () {
            Route::get('/', [AcuteHemodialysisCasesController::class, 'index'])
                ->middleware('remember')
                ->name('index');
            Route::post('/', [AcuteHemodialysisCasesController::class, 'store'])
               ->name('store');
            Route::get('/{hashedKey}/edit', [AcuteHemodialysisCasesController::class, 'edit'])
               ->name('edit');
        });
});
