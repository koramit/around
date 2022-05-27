<?php

namespace App\Http\Controllers;

use App\Services\TranslationGenerator;
use Illuminate\Support\Facades\Session;

class LocalizationController extends Controller
{
    public function store(string $locale)
    {
        Session::put('locale', $locale);

        return back();
    }

    public function show()
    {
        return [
            'translations' => TranslationGenerator::getTranslationsIfNeeded(),
        ];
    }
}
