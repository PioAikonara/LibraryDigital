<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }
    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Normalize role to lowercase string and trim whitespace to avoid
            // mismatches caused by casing or accidental spaces.
            $role = strtolower(trim((string) Auth::user()->role));

            if ($role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome back, Admin!');
            } elseif ($role === 'user') {
                return redirect()->route('user.dashboard')->with('success', 'Welcome back, User!');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'user',
            ]);

            Auth::login($user);
            return redirect()->route('user.dashboard')
                ->with('success', 'Account registered successfully! Welcome aboard!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create account. Please try again.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }
    public function logout(Request $request)
    {
        $request->session()->invalidate();
        Auth::logout();
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}
