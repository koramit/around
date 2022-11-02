<?php

use App\Http\Controllers\Labs\KidneyTransplantHLATyping\CaseRecordController;
use App\Http\Controllers\Labs\KidneyTransplantHLATyping\ReportController;
use App\Http\Controllers\Labs\LabController;
use Illuminate\Support\Facades\Route;

Route::get('/', LabController::class)
    ->name('index');

// KT HLA-CXM
Route::prefix('kt-hla-typing')
    ->name('kt-hla-typing.')
    ->group(function () {
        Route::get('/', [CaseRecordController::class, 'index'])
            ->name('index');
        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports.index');
        Route::post('/reports', [ReportController::class, 'store'])
            ->middleware(['can:create_kt_hla_typing_report'])
            ->name('reports.store');
        Route::get('/reports/{hashedKey}', [ReportController::class, 'edit'])
            ->name('reports.edit');
        Route::patch('/reports/{hashedKey}', [ReportController::class, 'update'])
            ->name('reports.update');
    });
