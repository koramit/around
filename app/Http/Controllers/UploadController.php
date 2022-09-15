<?php

namespace App\Http\Controllers;

use App\Actions\Resources\UploadShowAction;
use App\Actions\Resources\UploadStoreAction;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        return (new UploadStoreAction())($request->all());
    }

    public function show(Request $request)
    {
        return (new UploadShowAction())($request->input('path'));
    }
}
