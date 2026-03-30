<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,avif,webp', 'max:2048'],
            'birthday' => ['required', 'date', 'before:today'],
            'chief_complaint' => ['nullable', 'string', 'max:255'],
        ]);

        // $profileImagePath = null;

        // if ($request->hasFile('profile_image')) {
        //     $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
        // }

        $profileImagePath = null;

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());

            $destinationPath = base_path('../public_html/profile_images');

            $file->move($destinationPath, $filename);

            $profileImagePath = 'profile_images/' . $filename;
        }


        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => $data['password'],
            'role' => User::ROLE_USER,
            'profile_image' => $profileImagePath,
            'birthday' => $data['birthday'],
            'chief_complaint' => $data['chief_complaint'] ?? null,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login_type = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) 
            ? 'email' 
            : 'phone';

        if (!Auth::attempt([
            $login_type => $credentials['login'],
            'password' => $credentials['password']
        ], $request->boolean('remember'))) {

            return back()->withErrors([
                'login' => 'Invalid credentials.'
            ])->onlyInput('login');
        }

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
