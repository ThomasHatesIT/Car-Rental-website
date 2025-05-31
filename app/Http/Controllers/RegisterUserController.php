<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

use Spatie\Permission\Models\Permission;

use Spatie\Permission\Models\Role;

class RegisterUserController extends Controller
{
    public function index(){
        return view('auth.register');
    }

    public function store()
    {
        $attributes = request()->validate([
            'name'  => ['required', 'string', 'max:255'], // Added string and max validation
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users,email'], // Added string and max
            'password'   => ['required', Password::min(8)->numbers(), 'confirmed'], // Enhanced Password rule
        ]);

        // Hash the password before creating the user
        $attributes['password'] = Hash::make($attributes['password']);

        // Create the user
        $user = User::create($attributes);

        // Assign the 'user' role to the newly created user
        // This assumes you have a role named 'user' created by your PermissionSeeder
        $user->assignRole('user');

        // Log the user in
        Auth::login($user);

        // Redirect with a success message
        return redirect('/')->with('success', 'User registered successfully and logged in!'); // Changed message key to 'success' for consistency
    }
}