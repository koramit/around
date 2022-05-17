<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::get('login', function () {
    return view('login');
});

Route::any('logout', function () {
    \Auth::logout();

    return redirect('/login');
});

Route::get('/home', function () {
    return \Auth::user()->toArray();
})->name('home');

Route::post('login', function () {
    // return request()->only(['email', 'password']);
    if (\Auth::attempt(request()->only(['email', 'password']))) {
        return redirect('home');
    } else {
        abort(403);
    }
    return request()->only(['email', 'password']);
    return view('login');
});
