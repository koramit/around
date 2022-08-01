<?php

Barryvdh\Debugbar\Facades\Debugbar::disable();

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\TermsAndPoliciesController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

// pages
Route::get('terms-and-policies', TermsAndPoliciesController::class)
     ->name('terms');

// locales
Route::get('/locale/{locale}', [LocalizationController::class, 'store']);
Route::post('/translations', [LocalizationController::class, 'show']);

// common
Route::middleware(['auth'])->group(function () {
    Route::get('/', HomeController::class)
        ->middleware('page-transition')
        ->name('home');
    Route::get('/preferences', [PreferenceController::class, 'show'])
        ->middleware(['can:config_preferences', 'page-transition'])
        ->name('preferences');
    Route::patch('/preferences', [PreferenceController::class, 'update'])->name('preferences.update');

    //
    Route::get('/clinics', function () {
        return 'clinics';
    })->name('clinics');
    Route::get('/patients', function () {
        return 'patients';
    })->can('view_any_patients')->name('patients');
});

// administrative
Route::middleware(['auth', 'can:authorize_user'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])
        ->middleware('page-transition')
        ->name('users.index');
    Route::get('/users/{hashedKey}', [UserController::class, 'show'])
        ->name('users.show');
    Route::patch('/users/{hashedKey}', [UserController::class, 'update'])
        ->name('users.update');
});

// resources
Route::middleware(['auth', 'can:get_shared_api_resources'])
    ->prefix('resources')
    ->name('resources.api.')
    ->group(function () {
        require __DIR__.'/resources.php';
    });

// uploads
Route::middleware(['auth', 'can:upload_file'])->group(function () {
    Route::post('uploads', [UploadController::class, 'store'])
        ->name('uploads.store');
    Route::get('uploads', [UploadController::class, 'show'])
        ->name('uploads.show');
});

// support
Route::middleware(['auth', 'can:get_support'])->group(function () {
    Route::get('support-tickets', [SupportTicketController::class, 'index'])
         ->middleware('page-transition')
         ->name('support-tickets.index');
    Route::post('support-tickets', [SupportTicketController::class, 'store'])
         ->name('support-tickets.store');
    Route::post('support-tickets', [SupportTicketController::class, 'destroy'])
        ->name('support-tickets.destroy');
    Route::get('feedback', [FeedbackController::class, 'index'])
        ->middleware('page-transition')
        ->name('feedback.index');
    Route::post('feedback', [FeedbackController::class, 'store'])
        ->name('feedback.store');
});

Route::middleware(['auth'])
    ->prefix('procedures')
    ->name('procedures.')
    ->group(function () {
        require __DIR__.'/procedures.php';
    });

Route::middleware(['auth', 'can:comment'])
    ->prefix('comments')
    ->name('comments.')
    ->group(function () {
        Route::post('/fetch', [CommentController::class, 'index'])
            ->name('index');
        Route::post('', [CommentController::class, 'store'])
            ->name('store');
    });

Route::middleware(['auth'])
    ->prefix('subscriptions')
    ->name('subscriptions.')
    ->group(function () {
        Route::post('', [SubscriptionController::class, 'store'])
            ->name('store');
    });
