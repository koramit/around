<?php

use App\Http\Controllers\Procedures\AcuteHemodialysis\CaseRecordCompleteController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\CaseRecordController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\CreateOrderShortcutController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\DialysisSessionController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\IdleCaseController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\LastIndexSectionController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\OrderController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\OrderCopyController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\OrderExportController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\OrderRescheduleController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\OrderSubmitController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\OrderSwapController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\ScheduleController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\SlotAvailableController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\SlotAvailableDatesController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\SlotRequestController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\TodaySlotRequestController;
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

// schedule
Route::get('/schedule', ScheduleController::class)
    ->can('view_any_acute_hemodialysis_orders')
    ->name('schedule');

// slot request
Route::get('/slot-requests', [SlotRequestController::class, 'index'])
    ->can('view_any_acute_hemodialysis_slot_requests')
    ->name('slot-requests');
Route::patch('/slot-requests/{hashedKey}', [SlotRequestController::class, 'update'])
    ->name('slot-requests.approve');
Route::delete('/slot-requests/{hashedKey}', [SlotRequestController::class, 'destroy'])
    ->name('slot-requests.cancel');

// orders
Route::post('/orders', [OrderController::class, 'store'])
    ->can('create_acute_hemodialysis_order')
    ->name('orders.store');
Route::get('/orders/{hashedKey}/edit', [OrderController::class, 'edit'])
    ->name('orders.edit');
Route::patch('/orders/{hashedKey}/submit', OrderSubmitController::class)
    ->name('orders.submit');
Route::patch('/orders/{hashedKey}/reschedule', OrderRescheduleController::class)
    ->name('orders.reschedule');
Route::patch('/orders/{hashedKey}/swap', OrderSwapController::class)
    ->name('orders.swap');
Route::patch('/orders/{hashedKey}/today-slot-request', TodaySlotRequestController::class)
    ->name('orders.today-slot-request');
Route::get('/orders/{hashedKey}/create-shortcut', CreateOrderShortcutController::class)
    ->can('create_acute_hemodialysis_order')
    ->name('orders.create-shortcut');
Route::patch('/orders/{hashedKey}/copy', OrderCopyController::class)
    ->name('orders.copy');
Route::post('/orders/{hashedKey}/session', [DialysisSessionController::class, 'store'])
    ->name('orders.start-session');
Route::patch('/orders/{hashedKey}/session', [DialysisSessionController::class, 'update'])
    ->name('orders.update-session');
Route::delete('/orders/{hashedKey}/session', [DialysisSessionController::class, 'destroy'])
    ->name('orders.finish-session');
Route::patch('/orders/{hashedKey}', [OrderController::class, 'update'])
    ->name('orders.update');
Route::delete('/orders/{hashedKey}', [OrderController::class, 'destroy'])
    ->name('orders.destroy');
Route::get('/orders/{hashedKey}', [OrderController::class, 'show'])
    ->name('orders.show');
Route::get('/orders-export', OrderExportController::class)
    ->can('view_any_acute_hemodialysis_orders')
    ->name('orders.export');

// resources
Route::post('/slot-available', SlotAvailableController::class)
    ->can('view_any_acute_hemodialysis_slot_requests')
    ->name('slot-available');
Route::post('/slot-available-dates', SlotAvailableDatesController::class)
    ->can('create_acute_hemodialysis_order')
    ->name('slot-available-dates');
Route::get('/idle-cases', IdleCaseController::class)
    ->can('view_any_acute_hemodialysis_cases')
    ->name('idle-cases');
Route::get('/last-index-section', LastIndexSectionController::class)
    ->can('view_any_acute_hemodialysis_cases')
    ->name('last-index-section');
