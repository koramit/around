<?php

use App\Http\Controllers\Procedures\AcuteHemodialysis\CaseRecordController as AcuteHemodialysisCaseController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\OrderController as AcuteHemodialysisOrderController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\OrderRescheduleController as AcuteHemodialysisOrderRescheduleController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\OrderRescheduleToTodayController as AcuteHemodialysisOrderRescheduleToTodayController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\OrderSubmitController as AcuteHemodialysisOrderSubmitController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\OrderSwapController as AcuteHemodialysisOrderSwapController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\SlotAvailableController as AcuteHemodialysisSlotAvailableController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\SlotController as AcuteHemodialysisSlotController;
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
            Route::get('/orders/{hashedKey}/edit', [AcuteHemodialysisOrderController::class, 'edit'])
               ->name('orders.edit');
            Route::patch('/orders/{hashedKey}/submit', AcuteHemodialysisOrderSubmitController::class)
               ->name('orders.submit');
            Route::patch('/orders/{hashedKey}/reschedule', AcuteHemodialysisOrderRescheduleController::class)
               ->name('orders.reschedule');
            Route::post('/orders/{hashedKey}/reschedule-to-today', AcuteHemodialysisOrderRescheduleToTodayController::class)
               ->name('orders.reschedule-to-today');
            Route::patch('/orders/{hashedKey}/swap', AcuteHemodialysisOrderSwapController::class)
               ->name('orders.swap');
            Route::patch('/orders/{hashedKey}', [AcuteHemodialysisOrderController::class, 'update'])
               ->name('orders.update');
            Route::delete('/orders/{hashedKey}', [AcuteHemodialysisOrderController::class, 'destroy'])
               ->name('orders.destroy');

            Route::post('slot-available', AcuteHemodialysisSlotAvailableController::class)
               ->name('slot-available');

            Route::post('slot', AcuteHemodialysisSlotController::class)
               ->name('slot');
        });
});
