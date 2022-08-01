<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        // @todo make it support avatar

        $validated = $request->validate([
            'file' => 'required|file',
            'pathname' => 'required|string',
            'old' => 'nullable|string',
        ]);

//        $data = $request->only(['name', 'old']);
        $path = $request->file('file')->store('uploads/'.$validated['pathname']);

        if (($validated['old'])) {
            if (Storage::exists('uploads/'.$validated['pathname'].'/'.$validated['old'])) {
                Storage::delete('uploads/'.$validated['pathname'].'/'.$validated['old']);
            }
        }

        return [
            'filename' => basename($path),
        ];
    }

    public function show(Request $request)
    {
        $path = $request->input('path');

        if (! Storage::exists('uploads/'.$path)) {
            abort(404);
        }

        return Storage::response('uploads/'.$path);
    }
}
