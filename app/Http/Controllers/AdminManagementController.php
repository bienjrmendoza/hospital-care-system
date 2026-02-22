<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
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
        return view('admin.admins');
    }

    public function adminsFeed(): JsonResponse
    {
        $admins = User::query()
            ->where('role', User::ROLE_ADMIN)
            ->latest()
            ->get()
            ->map(fn (User $admin): array => [
                'name' => $admin->name,
                'email' => $admin->email,
                'created_at' => $admin->created_at->format('F j, Y g:i A'),
                'created_at_sort' => $admin->created_at->timestamp,
            ])
            ->values();

        return response()->json([
            'data' => $admins,
        ]);
    }

    public function storeAdmin(Request $request): JsonResponse|RedirectResponse
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

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Admin created.',
            ]);
        }

        return back()->with('success', 'Admin created.');
    }
}
