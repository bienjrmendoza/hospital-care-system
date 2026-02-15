<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminManagementController extends Controller
{
    public function dashboard(): View
    {
        $totals = [
            'doctors' => User::query()->where('role', User::ROLE_DOCTOR)->count(),
            'users' => User::query()->where('role', User::ROLE_USER)->count(),
            'admins' => User::query()->where('role', User::ROLE_ADMIN)->count(),
        ];

        return view('admin.dashboard', compact('totals'));
    }

    public function indexAdmins(): View
    {
        $admins = User::query()->where('role', User::ROLE_ADMIN)->latest()->get();

        return view('admin.admins', compact('admins'));
    }

    public function storeAdmin(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => User::ROLE_ADMIN,
        ]);

        return back()->with('success', 'Admin created.');
    }
}
