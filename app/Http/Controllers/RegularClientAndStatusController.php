<?php

namespace App\Http\Controllers;

use App\Models\RegularClientAndStatus;
use App\Models\User;
use App\Models\WorkAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegularClientAndStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $regularClients = WorkAssignment::where('client_types', 'regular')->latest()->get();
        return view('admin.regular_clients.index', compact('regularClients'));
    }


    /**
     * Show the form for creating a new resource.
     */
    // Show the form to create a new regular client and status (Admin View)
    public function create()
    {
        $clients = User::where('role', 'teacher')->get();
        $employees = User::where('role', 'student')->get();
        return view('admin.regular_clients.create', compact('clients', 'employees'));
    }

    // Store a new regular client and status (Admin View)
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:users,id',
            'employee_id' => 'required|exists:users,id',
            'location' => 'nullable|string',
            'contact_number' => 'nullable|string',
        ]);
        // Get the authenticated admin's ID
        $adminId = Auth::guard('admin')->id(); // This will return only the ID, not an object

        if (!$adminId) {
            return redirect()->back()->with('error', 'Only admins can assign clients.');
        }
        RegularClientAndStatus::create([
            'client_id' => $request->client_id,
            'employee_id' => $request->employee_id,
            'assigned_by' => $adminId, // Ensuring it's the logged-in admin
            'location' => $request->location,
            'contact_number' => $request->contact_number,
        ]);

        return redirect()->route('admin.regular-clients-list')->with('success', 'Regular client assigned successfully!');
    }
    // Show details of a regular client and status (Admin, Client, Employee View)
    public function show(RegularClientAndStatus $regularClient)
    {
        return view('regular_clients.show', compact('regularClient'));
    }
    public function clientIndex()
    {
        // Use the 'client' guard
        $regularClients = RegularClientAndStatus::where('client_id', auth()->id())
            ->with(['employee', 'assignedBy'])
            ->get();
        return view('student.regular_clients.index', compact('regularClients'));
    }
}
