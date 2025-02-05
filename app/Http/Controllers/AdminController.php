<?php

namespace App\Http\Controllers;

use App\Models\CarWashBooking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }
    public function dashboard()
    {
        return view('admin.dashboard');
    }
    public function form()
    {
        return view('admin.form');
    }
    public function table()
    {
        return view('admin.table');
    }
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            if (Auth::guard('admin')->user()->role != 'admin') {
                Auth::guard('admin')->logout();
                return redirect()->route('admin.login')->with('error', 'Unautherise user, credentials');
            } else {
                return redirect()->route('admin.dashboard');
            }
        } else {
            return redirect()->route('admin.login')->with('error', 'Invalid credentials');
        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
    public function register()
    {
        return view('admin.register');
        // $user = new User();
        // $user->name = 'Student';
        // $user->role = 'student';
        // $user->email = 'student@gmail.com';
        // $user->password = Hash::make('register123');
        // $user->save();
    }
    public function clientsave(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users', // Ensure email is unique in 'clients' table
            'phone' => 'required|digits:10',
            'address' => 'required|string|max:255',
            'password' => 'required|confirmed|min:8',
        ]);

        // Save the data into the database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => bcrypt($request->password), // Encrypt the password
        ]);

        // Redirect to a success page or login page
        return redirect()->route('admin.login')->with('success', 'Registration successful. Please log in.');
    }

    public function bookinglist()
    {
        $bookings = CarWashBooking::with('workAssignment.employee')->get();
        return view('admin.bookings', compact('bookings'));
    }

    // Show the details of a specific booking
    public function bookingDetails(CarWashBooking $booking)
    {
        $booking->load(['workAssignment.employee', 'workAssignment.dailyUpdates']);
        return view('admin.booking-details', compact('booking'));
    }
}
