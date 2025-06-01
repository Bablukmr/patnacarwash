<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Assuming you're storing clients in the users table

class ClientController extends Controller
{
    // Show the client registration form
    public function showRegistrationForm()
    {
        return view('welcome'); // Assuming your HTML view is saved as client/register.blade.php
    }

    // Handle the registration form submission
    public function register(Request $request)
    {
        // dd($request->all());
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone_number' => 'required',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'pincode' => 'nullable|string|max:10',
        ]);

        // Create the new client in the users table
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'city' => $request->city,
            'pincode' => $request->pincode,
            'role' => 'student', // Assuming 'student' is the role for a client
        ]);

        // Redirect to a page after successful registration
        return redirect()->route('student.login')->with('success', 'Registration successful! Please login.');
    }
}
