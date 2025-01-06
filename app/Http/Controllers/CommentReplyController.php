<?php

namespace App\Http\Controllers;

use App\Actions\Discussion\CommentReplyIndexAction;
use App\Actions\Discussion\CommentReplyShowRepliesAction;
use App\Actions\Discussion\CommentReplyStoreCommentAction;
use App\Actions\Discussion\CommentReplyStoreReplyAction;
use App\Traits\FirstNameAware;
use Illuminate\Http\Request;

class CommentReplyController extends Controller
{
    use FirstNameAware;

    public function store(Request $request)
    {
        return (new CommentReplyStoreCommentAction)($request->all(), $request->user());
    }

    public function index(Request $request)
    {
        return (new CommentReplyIndexAction)($request->all());
    }

    public function show(string $hashedKey)
    {
        return (new CommentReplyShowRepliesAction)($hashedKey);
    }

    public function reply(string $hashedKey, Request $request)
    {
        return (new CommentReplyStoreReplyAction)($hashedKey, $request->all(), $request->user());
    }
}
