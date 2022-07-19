<?php

use App\Http\Controllers\Procedures\AcuteHemodialysis\CaseRecordController as AcuteHemodialysisCaseController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\OrderController as AcuteHemodialysisOrderController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\OrderRescheduleController as AcuteHemodialysisOrderRescheduleController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\OrderSubmitController as AcuteHemodialysisOrderSubmitController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\OrderSwapController as AcuteHemodialysisOrderSwapController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\ScheduleController as AcuteHemodialysisScheduleController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\SlotAvailableController as AcuteHemodialysisSlotAvailableController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\SlotRequestController as AcuteHemodialysisSlotRequestController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\TodaySlotRequestController as AcuteHemodialysisTodaySlotRequestController;
use App\Http\Controllers\Procedures\ProcedureController;
use Illuminate\Support\Facades\Route;


Route::get('/', ProcedureController::class)->name('index');

// Acute HD
Route::prefix('acute-hemodialysis')
    ->name('acute-hemodialysis.')
    ->group(function () {
        Route::get('/', [AcuteHemodialysisCaseController::class, 'index'])
            ->middleware('remember')
            ->can('view_any_acute_hemodialysis_cases')
            ->name('index');
        Route::post('/', [AcuteHemodialysisCaseController::class, 'store'])
           ->can('create_acute_hemodialysis_case')
           ->name('store');
        Route::get('/{hashedKey}/edit', [AcuteHemodialysisCaseController::class, 'edit'])
           ->can('edit_acute_hemodialysis_case')
           ->name('edit');
        Route::patch('/{hashedKey}', [AcuteHemodialysisCaseController::class, 'update'])
           ->name('update');
        Route::post('/orders', [AcuteHemodialysisOrderController::class, 'store'])
           ->can('create_acute_hemodialysis_order')
           ->name('orders.store');
        Route::get('/orders/{hashedKey}/edit', [AcuteHemodialysisOrderController::class, 'edit'])
           ->name('orders.edit');
        Route::patch('/orders/{hashedKey}/submit', AcuteHemodialysisOrderSubmitController::class)
           ->name('orders.submit');
        Route::patch('/orders/{hashedKey}/reschedule', AcuteHemodialysisOrderRescheduleController::class)
           ->name('orders.reschedule');
        Route::patch('/orders/{hashedKey}/swap', AcuteHemodialysisOrderSwapController::class)
              ->name('orders.swap');
        Route::patch('/orders/{hashedKey}/today-slot-request', AcuteHemodialysisTodaySlotRequestController::class)
           ->name('orders.today-slot-request');
        Route::patch('/orders/{hashedKey}', [AcuteHemodialysisOrderController::class, 'update'])
           ->name('orders.update');
        Route::delete('/orders/{hashedKey}', [AcuteHemodialysisOrderController::class, 'destroy'])
           ->name('orders.destroy');

        Route::post('/slot-available', AcuteHemodialysisSlotAvailableController::class)
           ->can('view_any_acute_hemodialysis_slot_requests')
           ->name('slot-available');

        Route::get('/schedule', AcuteHemodialysisScheduleController::class)
           ->middleware('remember')
           ->can('view_any_acute_hemodialysis_slot_requests')
           ->name('schedule');

        Route::get('/slot-requests', [AcuteHemodialysisSlotRequestController::class, 'index'])
           ->can('view_any_acute_hemodialysis_slot_requests')
           ->name('slot-requests');
        Route::patch('/slot-requests/{hashedKey}', [AcuteHemodialysisSlotRequestController::class, 'update'])
            ->name('slot-requests.approve');
        Route::delete('/slot-requests/{hashedKey}', [AcuteHemodialysisSlotRequestController::class, 'destroy'])
            ->name('slot-requests.cancel');

        Route::get('/slot-requests-show-case', [AcuteHemodialysisSlotRequestController::class, 'case'])
           ->can('view_any_acute_hemodialysis_slot_requests')
           ->name('slot-requests.case');
    });
