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
use App\Http\Controllers\Procedures\AcuteHemodialysis\SlotAvailableController as AcuteHemodialysisSlotAvailableController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\SlotAvailableDatesController as AcuteHemodialysisSlotAvailableDatesController;
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



        Route::post('/slot-available', AcuteHemodialysisSlotAvailableController::class)
           ->can('view_any_acute_hemodialysis_slot_requests')
           ->name('slot-available');

        Route::get('/idle-cases', AcuteHemodialysisIdleCase::class)
           ->can('view_any_acute_hemodialysis_cases')
           ->name('idle-cases');

        Route::get('/last-index-section', AcuteHemodialysisLastIndexSectionController::class)
            ->can('view_any_acute_hemodialysis_cases')
            ->name('last-index-section');

        Route::post('/slot-available-dates', AcuteHemodialysisSlotAvailableDatesController::class)
            ->can('create_acute_hemodialysis_order')
            ->name('slot-available-dates');




    });
