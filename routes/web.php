<?php

use App\Services\TranslationGenerator;
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
    // session()->put('locale', 'en');

    return Inertia::render('Welcome', [
        'trans' => TranslationGenerator::getTranslationsIfNeeded(session('locale', config('app.locale'))),
    ]);
});

Route::get('/user/{locale}', function ($locale) {
    session()->put('locale', $locale);

    return Inertia::render('User', [
        'trans' => TranslationGenerator::getTranslationsIfNeeded(session('locale', config('app.locale'))),
    ]);
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

Route::get('/colors', function () {
    return view('colors');
});

Route::get('/lang/{locale}', function ($locale) {
    \App::setLocale($locale); // only one request
    session()->put('locale', $locale);

    return back();
});
