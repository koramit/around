<?php

use App\Http\Controllers\Auth\AvatarController;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* !!!!!!!!!!!!!!!!!!!!!!!!! NO SESSION AVAILABLE HERE !!!!!!!!!!!!!!!!!!!!!!!!!!!!!! */

// Route::post('/avatar/login', [AvatarController::class, 'store']);

// Route::middleware('auth:sanctum')->get('/avatar', HomeController::class)->name('avatar.home');
// Route::middleware('auth:sanctum')->get('/avatar', [AvatarController::class, 'show']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
