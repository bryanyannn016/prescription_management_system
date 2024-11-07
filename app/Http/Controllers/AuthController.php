<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Attempt to log the user in
        if (Auth::attempt($credentials)) {
            // Get the authenticated user
            $user = Auth::user();
            
            // Check if the user is Active
            if ($user->status !== 'Active') {
                // Logout the user if the status is not Active
                Auth::logout();
                
                // Redirect back with an error message
                return redirect()->back()->withErrors(['email' => 'Your account is currently not active.']);
            }

            // If the user is active, redirect to the intended page
            return redirect()->intended('/' . $user->type . '/dashboard');
        }

        // If credentials are incorrect, redirect back with an error
        return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
