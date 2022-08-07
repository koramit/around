<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('webhooks/{provider}/{chatBot:callback_token}', WebhookController::class);
