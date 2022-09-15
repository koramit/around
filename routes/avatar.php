<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AvatarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\Procedures\AcuteHemodialysis\CaseRecordController as AcuteHemodialysisCaseController;
use App\Http\Controllers\Procedures\ProcedureController;
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
        Route::get('/', [AcuteHemodialysisCaseController::class, 'index'])
            ->can('view_any_acute_hemodialysis_cases')
            ->name('index');
        Route::post('/', [AcuteHemodialysisCaseController::class, 'store'])
            ->can('create_acute_hemodialysis_case')
            ->name('store');
        Route::get('/{hashedKey}/edit', [AcuteHemodialysisCaseController::class, 'edit'])
            ->can('view_any_acute_hemodialysis_cases')
            ->name('edit');
    });
