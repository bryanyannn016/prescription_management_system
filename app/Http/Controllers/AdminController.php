<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function showCreateAccountForm()
    {
        return view('admin.create-account');
    }

    public function createAccount(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'type' => 'required|in:doctor,nurse',
            'health_facility' => 'nullable|string|max:255',
        ]);

        // Create the user
        \App\Models\User::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => \Illuminate\Support\Facades\Hash::make('defaultpassword'), // You can set a default password or generate one
            'type' => $request->input('type'),
            'health_facility' => $request->input('health_facility'),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Account created successfully.');
    }
}

