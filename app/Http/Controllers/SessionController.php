<?php

namespace App\Http\Controllers;

use App\Models\User; // Not strictly needed in this controller unless you type-hint or query User model directly here
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Not used in this controller directly
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Prevent session fixation

            // Get the authenticated user
            $user = Auth::user();

            // Check if the user has the 'admin' role
            // This assumes your User model uses Spatie's HasRoles trait
          if ($user && $user->hasRole('admin')) {
    // Always redirect admins to dashboard, ignoring "intended"
    return redirect()->route('admin.dashboard')->with('success', 'Logged in successfully! Welcome Admin.');
} else {
    // Let non-admins go to their intended page or home
    return redirect()->intended('/')->with('success', 'Logged in successfully!');
}

        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.', // More standard error message
        ])->onlyInput('email');
    }

    public function destroy(Request $request) // It's good practice to accept Request for session invalidation
    {
        Auth::logout();

        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate CSRF token

        return redirect('/')->with('success', 'Logged out successfully!'); // Add a logout message
    }
}