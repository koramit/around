<?php

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
    });
