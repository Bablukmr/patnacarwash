<?php

namespace App\Http\Controllers;

use App\Models\CarWashBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarWashController extends Controller
{
    // Show the booking form
    public function showBookingForm()
    {
        return view('student.dashboard');
    }

    // Store the booking data
    public function storeBooking(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'car_model' => 'required|string|max:255',
            'car_type' => 'required|string|max:255',
            'license_plate' => 'required|string|max:255',
            'service_type' => 'required|string|max:255',
            'preferred_date' => 'required|date|after_or_equal:today',
            'preferred_time' => 'required|date_format:H:i', // Use this instead of `time`
            'address' => 'required|string|max:500',
            'payment_method' => 'required|string|in:cash_on_delivery,credit_card,upi',
            'car_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);


        // Create a new car wash booking record
        $booking = new CarWashBooking();
        $booking->name = $request->name;
        $booking->email = $request->email;
        $booking->phone = $request->phone;
        $booking->car_model = $request->car_model;
        $booking->service_type = $request->service_type;
        $booking->preferred_date = $request->preferred_date;
        $booking->preferred_time = $request->preferred_time;
        $booking->address = $request->address;

        // Assign the user ID (if logged in)
        $booking->user_id = Auth::id();  // Will be null if guest user
        $booking->save();

        // Redirect with success message
        return redirect()->route('student.bookings')->with('success', 'Booking successfully created!');
    }

    // View all bookings (for admin and user who booked)
    public function viewBookings()
    {
        $userId = Auth::id();
        $isAdmin = Auth::user()->role === 'admin';

        // Fetch bookings ordered by newest first
        if ($isAdmin) {
            $bookings = CarWashBooking::latest()->get(); // Show newest first
        } else {
            $bookings = CarWashBooking::where('user_id', $userId)->latest()->get();
        }

        return view('student.bookings', compact('bookings'));
    }


    public function bookingStatus($bookingId)
    {
        // dd($bookingId);
        // Retrieve the booking details along with its work assignment and updates
        $booking = CarWashBooking::with(['workAssignment', 'workAssignment.dailyUpdates'])
            ->where('id', $bookingId)
            ->first();

        // Check if the booking exists
        if (!$booking) {
            return redirect()->route('student.bookings')->with('error', 'Booking not found.');
        }

        return view('student.booking-status', compact('booking'));
    }

    public function bookingStatusall()
    {
        $user = Auth::user();
        $bookings = CarWashBooking::with(['workAssignment.employee', 'workAssignment.dailyUpdates'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.statusall', compact('bookings'));
    }
}
