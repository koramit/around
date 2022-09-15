<?php

namespace App\Http\Controllers\Admin;

use App\Actions\User\UserIndexAction;
use App\Actions\User\UserRoleShowAction;
use App\Actions\User\UserRoleUpdateAction;
use App\Http\Controllers\Controller;
use App\Traits\AppLayoutSessionFlashable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    use AppLayoutSessionFlashable;

    public function index(Request $request)
    {
        $data = (new UserIndexAction())($request->user());

        if ($request->wantsJson()) {
            return $data;
        }

        $this->setFlash($data['flash']);

        return Inertia::render('User/ManageUser', [
            'users' => $data['users'],
        ]);
    }

    public function show(string $hashedKey, Request $request)
    {
        return (new UserRoleShowAction())($hashedKey, $request->user());
    }

    public function update(string $hashedKey, Request $request)
    {
        return (new UserRoleUpdateAction())($hashedKey, $request->input('roles'), $request->user());
    }
}
