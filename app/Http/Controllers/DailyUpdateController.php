<?php

namespace App\Http\Controllers;

use App\Models\WorkAssignment;
use App\Models\DailyUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DailyUpdateController extends Controller
{
    public function create(WorkAssignment $assignment)
    {
        return view('employee.daily-update', compact('assignment'));
    }

    public function store(Request $request, WorkAssignment $assignment)
    {
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|in:present,absent',
            'cleaning_status' => 'required|in:not_started,in_progress,completed',
            'daily_notes' => 'nullable|string',
            'defects_found' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/daily-updates');
                $imagePaths[] = str_replace('public/', '', $path);
            }
        }

        DailyUpdate::create([
            'work_assignment_id' => $assignment->id,
            'date' => $request->date,
            'attendance' => $request->attendance,
            'cleaning_status' => $request->cleaning_status,
            'daily_notes' => $request->daily_notes,
            'defects_found' => $request->defects_found,
            'images' => json_encode($imagePaths)
        ]);

        return redirect()->route('teacher.assigned-works')->with('success', 'Daily update submitted');
    }
}