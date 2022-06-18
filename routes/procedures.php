<?php

use App\Http\Controllers\Procedures\AcuteHemodialysis\CaseRecordController as AcuteHemodialysisCaseController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\OrderController as AcuteHemodialysisOrderController;
use App\Http\Controllers\Procedures\ProcedureController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('procedures')->group(function () {
    Route::get('/', ProcedureController::class)->name('procedures');

    // Acute HD
    Route::prefix('acute-hemodialysis')
        ->name('procedures.acute-hemodialysis.')
        ->group(function () {
            Route::get('/', [AcuteHemodialysisCaseController::class, 'index'])
                ->middleware('remember')
                ->name('index');
            Route::post('/', [AcuteHemodialysisCaseController::class, 'store'])
               ->name('store');
            Route::get('/{hashedKey}/edit', [AcuteHemodialysisCaseController::class, 'edit'])
               ->name('edit');
            Route::patch('/{hashedKey}', [AcuteHemodialysisCaseController::class, 'update'])
               ->name('update');
            Route::post('/orders', [AcuteHemodialysisOrderController::class, 'store'])
               ->name('orders.store');
        });
});
