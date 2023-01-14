<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class JournalController extends Controller
{
    public function index()
    {
        session()->flash('page-title', 'Journal');
        session()->flash('navs', [
            ['label' => 'Journal', 'route' => route('journal')],
            ['label' => 'Nephflix', 'route' => route('nephflix')],
        ]);

        return Inertia::render('JournalPage', [
            'files' => cache()->rememberForever('journal-files', function () {
                return Storage::disk('s3')->allFiles('f/j');
            }),
        ]);
    }

    public function show()
    {
        return Storage::disk('s3')->download(request()->input('key'));
    }
}
