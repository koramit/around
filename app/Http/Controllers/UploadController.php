<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        // @todo make it support avatar

        $request->validate([
            'file' => 'required|file',
            'name' => 'required|string',
        ]);

        $data = $request->only(['name', 'old']);
        $path = $request->file('file')->store('uploads/'.$data['name']);

        if ($data['old']) {
            if (Storage::exists('uploads/'.$data['name'].'/'.$data['old'])) {
                Storage::delete('uploads/'.$data['name'].'/'.$data['old']);
            }
        }

        return [
            'filename' => basename($path),
        ];
    }

    public function show($path, $filename)
    {
        $path = $path.'/'.$filename;

        if (Storage::exists('uploads/'.$path)) {
            return Storage::response('uploads/'.$path);
        }
    }
}
