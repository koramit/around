<?php

use App\Http\Controllers\CommentReplyController;
use App\Http\Controllers\CommentTimelineController;
use Illuminate\Support\Facades\Route;

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
