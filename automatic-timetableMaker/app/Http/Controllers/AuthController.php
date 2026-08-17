<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Show Login Form
    public function showLogin()
    {
        // If no users exist, redirect to first-time setup
        if (User::count() === 0) {
            return redirect()->route('register.first');
        }

        return view('auth.login');
    }

    // Authenticate User
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        return back()->with('error', 'Invalid login credentials.');
    }

    // Show First User (Admin) Setup Form
    public function showFirstRegister()
    {
        if (User::count() > 0) {
            return redirect()->route('login')->with('error', 'Public registration is disabled. Only Admins can add new users.');
        }

        return view('auth.register_first');
    }

    // Process First User (Admin) Registration
    public function registerFirst(Request $request)
    {
        if (User::count() > 0) {
            return redirect()->route('login')->with('error', 'Admin already exists.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin', // Force first user to be Admin
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'System initialized! You are logged in as System Administrator.');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Successfully logged out.');
    }
}