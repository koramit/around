<?php

use App\Http\Controllers\Wards\KidneyTransplantAdmission\CaseRecordController;
use Illuminate\Support\Facades\Route;

// cases
Route::get('/', [CaseRecordController::class, 'index'])
    ->middleware(['can:view_any_kt_admission_cases'])
    ->name('index');
