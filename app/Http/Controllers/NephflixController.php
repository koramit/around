<?php

namespace App\Http\Controllers;

use App\Traits\CSVLoader;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NephflixController extends Controller
{
    use CSVLoader;

    protected $episodes;

    public function __construct()
    {
        $this->episodes = cache()->rememberForever('asnonline-episodes', function () {
            $hasher = app(Hashids::class);

            return collect($this->loadCSV(storage_path('app/seeders/asnonline.csv')))->map(fn ($e, $i) => [
                'title' => "{$e['serie']}/{$e['season']}/{$e['episode']}",
                'episode' => $e['episode'],
                'speakers' => $e['speakers'],
                'asset' => $e['url'],
                'route' => route('nephflix.show', $hasher->encode($i)),
            ]);
        });
    }

    public function index()
    {
        session()->flash('page-title', 'Nephflix');
        session()->flash('navs', [
            ['label' => 'Journal', 'route' => route('journal')],
            ['label' => 'Nephflix', 'route' => route('nephflix')],
        ]);

        return Inertia::render('Nephflix/NephflixIndex', [
            'episodes' => $this->episodes,
        ]);
    }

    public function show(string $hashedKey)
    {
        if (!$episode = $this->episodes[app(Hashids::class)->decode($hashedKey)[0]] ?? null) {
            abort(404);
        }

        session()->flash('page-title', $episode['episode']);

        return Inertia::render('Nephflix/NephflixShow', [
            'episode' => $episode,
            'baseUrl' => route('home'),
        ]);
    }
}
