<?php

namespace App\Http\Controllers;

use App\Models\CarWashBooking;
use App\Models\User;
use App\Models\WorkAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkAssignmentController extends Controller
{
    public function create(CarWashBooking $booking)
    {
        $employees = User::where('role', 'teacher')->get();
        return view('admin.assign-work', compact('booking', 'employees'));
    }

    public function store(Request $request, CarWashBooking $booking)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id'
        ]);

        WorkAssignment::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'employee_id' => $request->employee_id,
                'assigned_by' => Auth::guard('admin')->id(),
                'status' => 'assigned'
            ]
        );

        return redirect()->route('admin.bookinglist')->with('success', 'Work assigned successfully');
    }
}