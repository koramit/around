<?php

use App\Http\Controllers\Procedures\AcuteHemodialysis\CreateOrderShortcutController as AcuteHemodialysisCreateOrderShortcutController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\DialysisSessionController as AcuteHemodialysisDialysisSessionController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\IdleCaseController as AcuteHemodialysisIdleCase;
use App\Http\Controllers\Procedures\AcuteHemodialysis\LastIndexSectionController as AcuteHemodialysisLastIndexSectionController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\OrderController as AcuteHemodialysisOrderController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\OrderCopyController as AcuteHemodialysisOrderCopyController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\OrderExportController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\OrderRescheduleController as AcuteHemodialysisOrderRescheduleController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\OrderSubmitController as AcuteHemodialysisOrderSubmitController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\OrderSwapController as AcuteHemodialysisOrderSwapController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\ScheduleController as AcuteHemodialysisScheduleController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\SlotAvailableController as AcuteHemodialysisSlotAvailableController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\SlotAvailableDatesController as AcuteHemodialysisSlotAvailableDatesController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\SlotRequestController as AcuteHemodialysisSlotRequestController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\TodaySlotRequestController as AcuteHemodialysisTodaySlotRequestController;
use App\Http\Controllers\Procedures\ProcedureController;
use Illuminate\Support\Facades\Route;

Route::get('/', ProcedureController::class)
    ->middleware(['page-transition', 'locale', 'no-in-app-allow'])
    ->name('index');

// Acute HD
Route::prefix('acute-hemodialysis')
    ->name('acute-hemodialysis.')
    ->group(function () {
        require __DIR__.'/procedures/acute_hd.php';

        Route::post('/orders', [AcuteHemodialysisOrderController::class, 'store'])
            ->can('create_acute_hemodialysis_order')
            ->name('orders.store');
        Route::get('/orders/{hashedKey}/edit', [AcuteHemodialysisOrderController::class, 'edit'])
            ->middleware(['page-transition', 'locale', 'no-in-app-allow'])
            ->name('orders.edit');
        Route::patch('/orders/{hashedKey}/submit', AcuteHemodialysisOrderSubmitController::class)
            ->name('orders.submit');
        Route::patch('/orders/{hashedKey}/reschedule', AcuteHemodialysisOrderRescheduleController::class)
            ->name('orders.reschedule');
        Route::patch('/orders/{hashedKey}/swap', AcuteHemodialysisOrderSwapController::class)
            ->name('orders.swap');
        Route::patch('/orders/{hashedKey}/today-slot-request', AcuteHemodialysisTodaySlotRequestController::class)
           ->name('orders.today-slot-request');
        Route::get('/orders/{hashedKey}/create-shortcut', AcuteHemodialysisCreateOrderShortcutController::class)
            ->can('create_acute_hemodialysis_order')
            ->name('orders.create-shortcut');
        Route::patch('/orders/{hashedKey}/copy', AcuteHemodialysisOrderCopyController::class)
            ->name('orders.copy');
        Route::post('/orders/{hashedKey}/session', [AcuteHemodialysisDialysisSessionController::class, 'store'])
            ->name('orders.start-session');
        Route::patch('/orders/{hashedKey}/session', [AcuteHemodialysisDialysisSessionController::class, 'update'])
            ->name('orders.update-session');
        Route::delete('/orders/{hashedKey}/session', [AcuteHemodialysisDialysisSessionController::class, 'destroy'])
            ->name('orders.finish-session');
        Route::patch('/orders/{hashedKey}', [AcuteHemodialysisOrderController::class, 'update'])
           ->name('orders.update');
        Route::delete('/orders/{hashedKey}', [AcuteHemodialysisOrderController::class, 'destroy'])
           ->name('orders.destroy');
        Route::get('/orders/{hashedKey}', [AcuteHemodialysisOrderController::class, 'show'])
            ->middleware(['page-transition', 'locale', 'no-in-app-allow'])
            ->name('orders.show');

        Route::post('/slot-available', AcuteHemodialysisSlotAvailableController::class)
           ->can('view_any_acute_hemodialysis_slot_requests')
           ->name('slot-available');

        Route::get('/schedule', AcuteHemodialysisScheduleController::class)
           ->middleware(['remember', 'page-transition', 'locale', 'no-in-app-allow'])
           ->can('view_any_acute_hemodialysis_orders')
           ->name('schedule');

        Route::get('/slot-requests', [AcuteHemodialysisSlotRequestController::class, 'index'])
            ->middleware(['page-transition', 'locale', 'no-in-app-allow'])
           ->can('view_any_acute_hemodialysis_slot_requests')
           ->name('slot-requests');
        Route::patch('/slot-requests/{hashedKey}', [AcuteHemodialysisSlotRequestController::class, 'update'])
            ->name('slot-requests.approve');
        Route::delete('/slot-requests/{hashedKey}', [AcuteHemodialysisSlotRequestController::class, 'destroy'])
            ->name('slot-requests.cancel');

        Route::get('/idle-cases', AcuteHemodialysisIdleCase::class)
           ->can('view_any_acute_hemodialysis_cases')
           ->name('idle-cases');

        Route::post('/extra-slot-requests', [AcuteHemodialysisSlotRequestController::class, 'store'])
            ->can('create_acute_hemodialysis_order')
            ->name('extra-slot-requests.store');

        Route::post('/slot-available-dates', AcuteHemodialysisSlotAvailableDatesController::class)
            ->can('create_acute_hemodialysis_order')
            ->name('slot-available-dates');

        Route::get('/last-index-section', AcuteHemodialysisLastIndexSectionController::class)
            ->can('view_any_acute_hemodialysis_cases')
            ->name('last-index-section');

        Route::get('/orders-export', OrderExportController::class)
            ->can('view_any_acute_hemodialysis_orders')
            ->name('orders.export');
    });
