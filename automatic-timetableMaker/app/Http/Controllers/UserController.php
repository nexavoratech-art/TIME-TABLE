<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index', ['users' => User::query()->orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['admin', 'user'])],
        ]);

        User::query()->create([...$validated, 'password' => Hash::make($validated['password'])]);

        return back()->with('success', 'User account created successfully.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'user'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if ($user->isAdmin() && $validated['role'] !== 'admin' && User::query()->where('role', 'admin')->count() === 1) {
            return back()->with('error', 'The system must retain at least one administrator.');
        }

        $user->fill(['name' => $validated['name'], 'email' => $validated['email'], 'role' => $validated['role']]);
        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        return back()->with('success', 'User account updated successfully.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return back()->with('error', 'You cannot delete the account currently in use.');
        }
        if ($user->isAdmin() && User::query()->where('role', 'admin')->count() === 1) {
            return back()->with('error', 'The system must retain at least one administrator.');
        }
        $user->delete();

        return back()->with('success', 'User account deleted successfully.');
    }
}
