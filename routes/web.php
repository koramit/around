<?php

// Barryvdh\Debugbar\Facades\Debugbar::disable();

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\CommentReplyController;
use App\Http\Controllers\CommentTimelineController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InAppBrowsingRedirectController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\MagicLinkController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\SocialProviderController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\TermsAndPoliciesController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

// TEST
// Route::get('tt', function () {
//     return \App\Models\Registries\AcuteHemodialysisCaseRecord::query()
//         ->with('lastOrder')
//         ->get()
//         ->transform(function ($c) {
//             $an = $c->form['an'];
//             $admission = null;
//             $dischargedAt = null;
//            if ($an) {
//                $admission = \App\Models\Resources\Admission::findByHashedKey($an)->withPlaceName()->first();
//                $dischargedAt = $admission?->dismissed_at;
//            }
//
//            return (Object) [
//                'key' => $c->hashed_key,
//                'name' => $c->meta['name'],
//                'discharged_at' => $dischargedAt?->format('Y-m-d'),
//                'last_order_date' => $c->lastOrder?->date_note->format('Y-m-d'),
//                'overlap' => ($dischargedAt && $c->lastOrder)
//                     ? ($c->lastOrder->date_note->greaterThan($dischargedAt))
//                     : false,
//            ];
//         })->filter(fn ($c) => $c->overlap);
// });

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
        ->middleware(['page-transition', 'locale', 'no-in-app-allow'])
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
    ->prefix('procedures')
    ->name('procedures.')
    ->group(function () {
        require __DIR__.'/procedures.php';
    });

// comment
Route::middleware(['auth', 'can:comment'])
    ->prefix('comments')
    ->name('comments.')
    ->group(function () {
        // Route::post('', CommentController::class)
        //     ->name('store');

        Route::get('/reply-oriented', [CommentReplyController::class, 'index'])
            ->name('reply-oriented.index');
        Route::post('/reply-oriented', [CommentReplyController::class, 'store'])
            ->name('reply-oriented.store');
        Route::get('/reply-oriented/{hashedKey}', [CommentReplyController::class, 'show'])
            ->name('reply-oriented.show');
        Route::post('/reply-oriented/{hashedKey}', [CommentReplyController::class, 'reply'])
            ->name('reply-oriented.reply');

        Route::get('/timeline-oriented', [CommentTimelineController::class, 'index'])
            ->name('timeline-oriented.index');
        Route::post('/timeline-oriented', [CommentTimelineController::class, 'store'])
            ->name('timeline-oriented.store');
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
