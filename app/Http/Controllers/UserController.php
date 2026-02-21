<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        $users = User::with('roles')
                     ->withCount(['createdTasks', 'assignedTasks'])
                     ->orderBy('name')
                     ->paginate(15);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $this->authorize('create', User::class);
        return view('users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', User::class);

        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users',
            'password'   => ['required', Rules\Password::defaults()],
            'position'   => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'role'       => 'required|in:admin,manager,developer,viewer',
        ]);

        $user = User::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'position'   => $data['position'] ?? null,
            'department' => $data['department'] ?? null,
        ]);

        $user->assignRole($data['role']);

        return redirect()->route('users.index')
                         ->with('success', "Пользователь {$user->name} создан!");
    }

    public function edit(User $user): View
    {
        $this->authorize('update', $user);
        $user->load('roles');
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => "required|email|unique:users,email,{$user->id}",
            'password'   => ['nullable', Rules\Password::defaults()],
            'position'   => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'is_active'  => 'boolean',
            'role'       => 'required|in:admin,manager,developer,viewer',
        ]);

        $updateData = [
            'name'       => $data['name'],
            'email'      => $data['email'],
            'position'   => $data['position'] ?? null,
            'department' => $data['department'] ?? null,
            'is_active'  => $data['is_active'] ?? true,
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);
        $user->syncRoles([$data['role']]);

        return redirect()->route('users.index')
                         ->with('success', 'Пользователь обновлён!');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Нельзя удалить собственный аккаунт.');
        }

        $user->update(['is_active' => false]);

        return redirect()->route('users.index')
                         ->with('success', 'Пользователь деактивирован.');
    }
}
