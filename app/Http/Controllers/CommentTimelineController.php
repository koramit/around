<?php

namespace App\Http\Controllers;

use App\Actions\Discussion\CommentTimelineIndexAction;
use App\Actions\Discussion\CommentTimelineStoreAction;
use Illuminate\Http\Request;

class CommentTimelineController extends Controller
{
    public function store(Request $request)
    {
        return (new CommentTimelineStoreAction())($request->all(), $request->user());
    }

    public function index(Request $request)
    {
        return (new CommentTimelineIndexAction())($request->all());
    }
}
