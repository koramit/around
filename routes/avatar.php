<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AvatarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Labs\LabController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\Procedures\ProcedureController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\Wards\WardController;
use Illuminate\Support\Facades\Route;

// auth
Route::post('/', [AvatarController::class, 'store'])->name('login');
Route::get('user', [AvatarController::class, 'show'])->middleware('auth:sanctum')->name('user');

// common
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/', HomeController::class)->name('home');

    // preferences
    Route::get('preferences', [PreferenceController::class, 'show'])->name('preferences');
    Route::patch('preferences', [PreferenceController::class, 'update'])->name('preferences.update');
});

// administrative
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');
    Route::get('/users/{hashedKey}', [UserController::class, 'show'])
        ->name('users.roles.show');
    Route::patch('/users/{hashedKey}', [UserController::class, 'update'])
        ->name('users.roles.update');
});

// resources
Route::middleware('auth:sanctum')
    ->prefix('resources')
    ->name('resources.api.')
    ->group(function () {
        require __DIR__.'/resources.php';
    });

// uploads
Route::middleware('auth:sanctum')->group(function () {
    Route::post('uploads', [UploadController::class, 'store'])
        ->name('uploads.store');
    Route::get('uploads', [UploadController::class, 'show'])
        ->name('uploads.show');
});

// discussion
Route::middleware('auth:sanctum')
    ->prefix('comments')
    ->name('comments.')
    ->group(function () {
        require __DIR__.'/discussion.php';
    });

// procedures
Route::middleware('auth:sanctum')
    ->get('procedures', ProcedureController::class)
    ->name('procedures.index');

// Acute HD
Route::middleware('auth:sanctum')
    ->prefix('procedures/acute-hemodialysis')
    ->name('procedures.acute-hemodialysis.')
    ->group(function () {
        require __DIR__.'/procedures/acute_hd.php';
    });

// labs
Route::middleware('auth:sanctum')
    ->get('labs', LabController::class)
    ->name('labs.index');

// KT HLA Typing
Route::middleware('auth:sanctum')
    ->prefix('labs/kt-hla-typing')
    ->name('labs.kt-hla-typing.')
    ->group(function () {
        require __DIR__.'/labs/kt_hla_typing.php';
    });

// wards
Route::middleware('auth:sanctum')
    ->get('wards', WardController::class)
    ->name('wards.index');

// KT ward registry
Route::middleware('auth:sanctum')
    ->prefix('wards/kt-admission')
    ->name('wards.kt-admission.')
    ->group(function () {
        require __DIR__.'/wards/kt_admission.php';
    });
