<?php

namespace App\Http\Controllers;

use App\Traits\CSVLoader;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NephflixController extends Controller
{
    use CSVLoader;

    protected $episodes;

    public function __construct()
    {
        $this->episodes = collect($this->loadCSV(storage_path('app/seeders/asnonline.csv')))->map(fn ($e) => [
            'title' => "{$e['serie']}/{$e['season']}/{$e['episode']}",
            'episode' => $e['episode'],
            'speakers' => $e['speakers'],
            'asset' => $e['url'],
            'route' => route('nephflix.show', ['ep' => $e['episode']])
        ]);
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

    public function show()
    {
        $episode = request()->input('ep');

        if (!$episode) {
            abort(404);
        }

        $index = $this->episodes->search(fn ($e, $i) => $e['episode'] === $episode);

        if ($index === false) {
            abort(404);
        }

        session()->flash('page-title', $this->episodes[$index]['episode']);

        return Inertia::render('Nephflix/NephflixShow', [
            'episode' => $this->episodes[$index],
            'baseUrl' => route('home'),
        ]);
    }
}
