<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InAppBrowsingRedirectController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\MagicLinkController;
use App\Http\Controllers\NephflixController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\SocialProviderController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\TermsAndPoliciesController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

// pages
Route::get('terms-and-policies', TermsAndPoliciesController::class)
    ->middleware(['locale', 'no-in-app-allow'])
    ->name('terms');

// locales
Route::get('/locale/{locale}', [LocalizationController::class, 'store']);
Route::post('/translations', [LocalizationController::class, 'show']);

// common
Route::middleware(['auth'])->group(function () {
    Route::get('/', HomeController::class)
        ->middleware(['page-transition', 'locale', 'no-in-app-allow'])
        ->name('home');
    Route::get('/preferences', [PreferenceController::class, 'show'])
        ->middleware(['can:config_preferences', 'page-transition', 'locale', 'no-in-app-allow'])
        ->name('preferences');
    Route::patch('/preferences', [PreferenceController::class, 'update'])->name('preferences.update');
    // @TODO: remove view_any_patients ability from participant role before implementing this
    Route::get('/patients', function () {
        return 'patients';
    })->can('view_any_patients')->name('patients');
});

// administrative
Route::middleware(['auth', 'can:authorize_user'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])
        ->middleware(['page-transition', 'locale', 'no-in-app-allow'])
        ->name('users.index');
    Route::get('/users/{hashedKey}', [UserController::class, 'show'])
        ->name('users.roles.show');
    Route::patch('/users/{hashedKey}', [UserController::class, 'update'])
        ->name('users.roles.update');
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
    Route::delete('uploads', [UploadController::class, 'destroy'])
        ->name('uploads.destroy');
});

// discussion
Route::middleware(['auth', 'can:comment'])
    ->prefix('comments')
    ->name('comments.')
    ->group(function () {
        require __DIR__.'/discussion.php';
    });

// support
Route::middleware(['auth', 'can:get_support'])->group(function () {
    Route::get('support-tickets', [SupportTicketController::class, 'index'])
        ->middleware(['page-transition', 'locale', 'no-in-app-allow'])
        ->name('support-tickets.index');
    Route::post('support-tickets', [SupportTicketController::class, 'store'])
        ->name('support-tickets.store');
    Route::post('support-tickets', [SupportTicketController::class, 'destroy'])
        ->name('support-tickets.destroy');
    Route::get('feedback', [FeedbackController::class, 'index'])
        ->middleware(['page-transition', 'locale', 'no-in-app-allow'])
        ->name('feedback.index');
    Route::post('feedback', [FeedbackController::class, 'store'])
        ->name('feedback.store');
});

Route::middleware(['auth'])
    ->prefix('clinics')
    ->name('clinics.')
    ->group(function () {
        require __DIR__.'/clinics.php';
    });

Route::middleware(['auth'])
    ->prefix('procedures')
    ->name('procedures.')
    ->group(function () {
        require __DIR__.'/procedures.php';
    });

Route::middleware(['auth'])
    ->prefix('labs')
    ->name('labs.')
    ->group(function () {
        require __DIR__.'/labs.php';
    });

Route::middleware(['auth'])
    ->prefix('wards')
    ->name('wards.')
    ->group(function () {
        require __DIR__.'/wards.php';
    });

Route::middleware(['auth'])
    ->prefix('clinics')
    ->name('clinics.')
    ->group(function () {
        require __DIR__.'/clinics.php';
    });

// subscription
Route::middleware(['auth'])
    ->prefix('subscriptions')
    ->name('subscriptions.')
    ->group(function () {
        Route::post('', [SubscriptionController::class, 'store'])
            ->name('store');
    });

// bot
Route::middleware(['auth'])
    ->prefix('social-providers')
    ->name('social-providers.')
    ->group(function () {
        Route::get('', [SocialProviderController::class, 'index'])
            ->middleware(['page-transition', 'can:view_any_social_providers'])
            ->name('index');

        Route::get('/{hashedKey}', [SocialProviderController::class, 'show'])
            ->middleware(['can:view_any_social_providers', 'can:view_any_chat_bots'])
            ->name('show');

        Route::post('', [SocialProviderController::class, 'store'])
            ->middleware(['can:create_social_provider'])
            ->name('store');

        Route::patch('/{hashedKey}', [SocialProviderController::class, 'update'])
            ->middleware(['can:edit_social_provider'])
            ->name('update');

        Route::post('/{hashedKey}/chat-bots', [ChatBotController::class, 'store'])
            ->middleware(['can:create_chat_bot'])
            ->name('bots.store');

        Route::get('/chat-bots/{hashedKey}', [ChatBotController::class, 'show'])
            ->middleware(['can:view_any_chat_bots'])
            ->name('bots.show');

        Route::patch('/chat-bots/{hashedKey}', [ChatBotController::class, 'update'])
            ->middleware(['can:edit_chat_bot'])
            ->name('bots.update');
    });

// in app browsing redirect
Route::get('in-app-browsing-redirect/{token}', InAppBrowsingRedirectController::class)
    ->name('in-app-browsing-redirect');

Route::get('magic-link', MagicLinkController::class)
    ->middleware(['no-in-app-allow', 'signed'])
    ->name('magic-link');

Route::get('journal', [JournalController::class, 'index'])
    ->middleware(['auth', 'can:view_any_club_resources', 'page-transition', 'locale', 'no-in-app-allow'])
    ->name('journal');
Route::get('journal/show', [JournalController::class, 'show'])
    ->middleware(['auth', 'can:view_any_club_resources'])
    ->name('journal.show');

Route::get('nephflix', [NephflixController::class, 'index'])
    ->middleware(['auth', 'can:view_any_club_resources', 'page-transition', 'locale', 'no-in-app-allow'])
    ->name('nephflix');

Route::get('nephflix/{hashedKey}', [NephflixController::class, 'show'])
    ->middleware(['auth', 'can:view_any_club_resources', 'page-transition', 'locale', 'no-in-app-allow'])
    ->name('nephflix.show');

Route::get('clear-club-cache', function () {
    cache()->forget('asnonline-episodes');
    cache()->forget('journal-files');

    return [
        'journal-files' => cache('journal-files'),
        'asnonline-episodes' => cache('asnonline-episodes'),
    ];
});

Route::get('/tap', function () {
    return request()->all();
});
