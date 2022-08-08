<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('webhooks/messaging/{chatBot:callback_token}', WebhookController::class);
