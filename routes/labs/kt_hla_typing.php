<?php

use App\Http\Controllers\Labs\KidneyTransplantHLATyping\ReportCancelController;
use App\Http\Controllers\Labs\KidneyTransplantHLATyping\ReportController;
use App\Http\Controllers\Labs\KidneyTransplantHLATyping\ReportPublishController;
use Illuminate\Support\Facades\Route;

// cases
Route::get('/', function () {
    return redirect()->route('labs.kt-hla-typing.reports.index');
})->name('index');
// reports
Route::get('/reports', [ReportController::class, 'index'])
    ->name('reports.index');
Route::post('/reports', [ReportController::class, 'store'])
    ->middleware(['can:create_kt_hla_typing_report'])
    ->name('reports.store');
Route::post('/reports/{hashedKey}/publish', ReportPublishController::class)
    ->name('reports.publish');
Route::put('/reports/{hashedKey}/addendum', ReportPublishController::class)
    ->name('reports.addendum');
Route::delete('/reports/{hashedKey}/cancel', ReportCancelController::class)
    ->name('reports.cancel');
Route::get('/reports/{hashedKey}/edit', [ReportController::class, 'edit'])
    ->name('reports.edit');
Route::patch('/reports/{hashedKey}', [ReportController::class, 'update'])
    ->name('reports.update');
Route::delete('/reports/{hashedKey}', [ReportController::class, 'destroy'])
    ->name('reports.destroy');
