<?php

namespace App\Http\Controllers;

use App\Traits\FirstNameAware;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    use FirstNameAware;

    public function store(Request $request)
    {
        $validated = $request->validate([
            'commentable_type' => 'required|string|starts_with:App\Models',
            'commentable_id' => 'required|string',
            'body' => 'required|string|max:1024',
            'notify_op' => 'required|bool',
            'parent_id' => 'exists:comments,id',
        ]);

        try {
            $resource = $validated['commentable_type']::query()->findByUnhashKey($validated['commentable_id'])->first();
        } catch (Exception) {
            throw ValidationException::withMessages(['status' => 'resource not found.']);
        }

        $resource->comments()->create([
            'body' => $validated['body'],
            'commentator_id' => $request->user()->id,
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        return back();
    }

    public function index(Request $request)
    {
        $validated = $request->validate([
            'commentable_type' => 'required|string|starts_with:App\Models',
            'commentable_id' => 'required|string',
        ]);

        try {
            $resource = $validated['commentable_type']::query()->findByUnhashKey($validated['commentable_id'])->first();
        } catch (Exception) {
            throw ValidationException::withMessages(['status' => 'resource not found.']);
        }

        return $resource->comments()
            ->select(['body', 'updated_at', 'commentator_id'])
            ->withCommentatorName()
            ->get()
            ->transform(fn ($c) => [
                'body' => $c->body_html,
                'commentator' => $this->getFirstName($c->commentator_name),
                'at' => $c->updated_at->longRelativeToNowDiffForHumans(),
            ]);
    }
}
