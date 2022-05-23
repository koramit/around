<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class TranslationGenerator
{
    public static function getTranslationsIfNeeded(string $locale)
    {
        if (Session::get('locale-loaded') === Session::get('locale')) {
            return null;
        }

        return static::get($locale);
    }

    public static function get(string $locale)
    {
        if (! App::environment('production')) {
            return static::generate($locale);
        }

        return Cache::rememberForever(
            key: 'transition-'.$locale,
            callback: fn () => static::generate($locale)
        );
    }

    protected static function generate(string $locale)
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
