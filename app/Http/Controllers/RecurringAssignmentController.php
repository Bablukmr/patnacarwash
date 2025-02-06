<?php

namespace App\Http\Controllers;

use App\Models\RecurringAssignment;
use App\Models\RegularClient;
use App\Models\User;
use Illuminate\Http\Request;

class RecurringAssignmentController extends Controller
{
    public function create()
    {
        $regularClients = RegularClient::with('user')->get();
        $employees = User::where('role', 'teacher')->get();
        return view('admin.recurring-assignments.create', compact('regularClients', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'regular_client_id' => 'required|exists:regular_clients,id',
            'employee_id' => 'required|exists:users,id',
            'frequency' => 'required|in:daily,weekly',
            'preferred_time' => 'required|date_format:H:i'
        ]);

        RecurringAssignment::create($request->all());

        return redirect()->route('recurring-assignments.index')
            ->with('success', 'Recurring assignment created successfully');
    }
}
