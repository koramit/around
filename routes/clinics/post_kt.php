<?php

use App\Http\Controllers\Clinics\PostKT\CaseRecordController;
use Illuminate\Support\Facades\Route;

// cases
Route::get('/', [CaseRecordController::class, 'index'])->name('index');

Route::get('/edit', [CaseRecordController::class, 'edit'])->name('edit');
