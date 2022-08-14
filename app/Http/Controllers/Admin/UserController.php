<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Traits\RegistryUserAttachable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    use RegistryUserAttachable;

    public function index(Request $request)
    {
        session()->flash('page-title', __('Manage User'));
        session()->flash('main-menu-links', collect([
            ['icon' => 'patient', 'label' => 'Patients', 'route' => route('patients'), 'can' => $request->user()->can('view_any_patients')],
            ['icon' => 'clinic', 'label' => 'Clinics', 'route' => route('clinics'), 'can' => $request->user()->can('view_any_patients')],
            ['icon' => 'procedure', 'label' => 'Procedures', 'route' => route('procedures.index'), 'can' => $request->user()->can('view_any_patients')],
        ])->filter(fn ($link) => $link['can'])->values());
        session()->flash('action-menu', []);
        $users = User::query()
            ->select(['id', 'full_name'])
            ->whereNotIn('id', [1, $request->user()->id])
            ->orderBy('full_name')
            ->paginate($request->user()->items_per_page)
            ->withQueryString()
            ->through(fn ($u) => [
                'id' => $u->hashed_key,
                'name' => $u->full_name,
                'get_route' => route('users.show', $u->hashed_key),
            ]);

        return Inertia::render('User/ManageUser', [
            'users' => $users,
        ]);
    }

    public function show(string $hashedKey, Request $request)
    {
        $user = User::query()->findByUnhashKey($hashedKey)->firstOrFail();
        $authority = $request->user();

        if ($authority->can('authorize_authority')) {
            $roles = Role::query()
                ->select('label')
                ->whereNotNull('label') // null label roles are higher level role
                ->orderBy('id')
                ->pluck('label')
                ->map(fn ($r) => ['name' => $r, 'has_role' => $user->role_labels->contains($r)]);
        } else {
            $roles = $authority->role_labels
                ->map(fn ($r) => ['name' => $r, 'has_role' => $user->role_labels->contains($r)])
                ->filter(fn ($r) => strtolower($r['name']) !== 'authority')
                ->values();
        }

        return [
            'name' => $user->full_name,
            'division' => $user->profile['division'],
            'position' => $user->profile['position'],
            'remark' => $user->profile['remark'],
            'roles' => $roles,
            'update_route' => route('users.update', $user->hashed_key),
        ];
    }

    public function update(string $hashedKey, Request $request)
    {
        // user
        $user = User::query()->findByUnhashKey($hashedKey)->firstOrFail();
        $authority = $request->user();

        // granted roles
        $checked = collect($request->input('roles'))->filter(fn ($r) => $r['has_role'])
            ->values()
            ->pluck('name')
            ->filter(fn ($name) => ! $user->role_labels->contains($name))
            ->values();
        if ($checked->count()) {
            $user->actionLogs()->create([
                'action' => 'grant',
                'actor_id' => $authority->id,
                'payload' => ['roles' => $checked->all()],
            ]);
        }
        $ids = Role::query()->whereIn('label', $checked)->pluck('id');
        if ($user->role_labels->count() === 0) {
            $ids[] = 3; // participant
        }
        $user->roles()->attach($ids);

        // revoked roles
        $notChecked = collect($request->input('roles'))->filter(fn ($r) => ! $r['has_role'])
            ->values()
            ->pluck('name')
            ->filter(fn ($name) => $user->role_labels->contains($name))
            ->values();
        if ($notChecked->count()) {
            $user->actionLogs()->create([
                'action' => 'grant',
                'actor_id' => $authority->id,
                'payload' => ['roles' => $notChecked->all()],
            ]);
        }
        $user->roles()->detach(Role::query()->whereIn('label', $notChecked)->pluck('id'));
        if ($user->roles()->count() === 1) {
            $user->roles()->detach();
        }
        $user->flushPrivileges();

        $this->toggleRegistryUser($user);

        return ['ok' => true];
    }
}
