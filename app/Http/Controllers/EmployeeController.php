<?php

namespace App\Http\Controllers;

use App\Models\DailyAttendance;
use App\Models\RecurringAssignment;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function recurringWork()
    {
        $assignments = RecurringAssignment::with(['regularClient.user', 'attendances'])
            ->where('employee_id', auth()->id())
            ->get();

        return view('employee.recurring-work', compact('assignments'));
    }

    public function markAttendance(Request $request, RecurringAssignment $assignment)
    {
        $request->validate([
            'date' => 'required|date',
            'status' => 'required|in:completed,missed',
            'notes' => 'nullable|string'
        ]);

        DailyAttendance::updateOrCreate(
            ['assignment_id' => $assignment->id, 'date' => $request->date],
            $request->only('status', 'notes')
        );

        return back()->with('success', 'Attendance marked successfully');
    }
}
