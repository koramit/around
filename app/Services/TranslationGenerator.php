<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class TranslationGenerator
{
    public static function getTranslationsIfNeeded(): mixed
    {
        $locale = Session::get('locale', config('app.locale'));
        if (Session::get('locale-loaded') === $locale) {
            return false;
        }

        return static::get($locale);
    }

    public static function get(string $locale): Collection
    {
        if (! App::environment('production')) {
            return static::generate($locale);
        }

        return Cache::rememberForever(
            key: 'transition-'.$locale,
            callback: fn () => static::generate($locale)
        );
    }

    protected static function generate(string $locale): Collection
    {
        Session::put('locale-loaded', $locale);

        return collect(File::files(base_path('lang/'.$locale)))->flatMap(function ($file) {
            return [($file->getBasename('.php')) => require $file];
        })->merge(
            json_decode(
                json: file_get_contents(base_path('lang/'.$locale.'.json')),
                associative: true
            )
        );
    }
}
