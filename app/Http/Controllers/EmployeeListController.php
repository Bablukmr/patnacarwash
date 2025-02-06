<?php

namespace App\Http\Controllers;

use App\Models\DailyUpdate;
use App\Models\User;
use App\Models\WorkAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use OpenCage\Geocoder\Geocoder;
use Illuminate\Support\Facades\Storage;

class EmployeeListController extends Controller
{
    public  function index()
    {
        $clients = User::where('role', 'teacher')->get();
        return view('admin.employeeList', compact('clients'));
    }

    public function employeeform()
    {
        return view('employee.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,student,teacher,parent',
            'password' => 'required|min:6',
            'phone_number' => 'required',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'pincode' => 'nullable|string|max:10',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => bcrypt($request->password),
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'city' => $request->city,
            'pincode' => $request->pincode,
        ]);

        return redirect()->route('admin.employeelist')->with('success', 'New Employee added successfully!');
    }

    public function clientform()
    {
        return view('admin.clientaddform');
    }

    public function clientstore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,student,teacher,parent',
            'password' => 'required|min:6',
            'phone_number' => 'required',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'pincode' => 'nullable|string|max:10',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => bcrypt($request->password),
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'city' => $request->city,
            'pincode' => $request->pincode,
        ]);

        return redirect()->route('admin.clientlist')->with('success', 'New Client added successfully!');
    }




    public function login()
    {
        return view('employee.login');
    }
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (Auth::guard('teacher')->attempt(['email' => $request->email, 'password' => $request->password])) {
            if (Auth::guard('teacher')->user()->role != 'teacher') {
                Auth::guard('teacher')->logout();
                return redirect()->route('teacher.login')->with('error', 'Unautherise user, credentials');
            } else {
                return redirect()->route('teacher.dashboard');
            }
        } else {
            return redirect()->route('teacher.login')->with('error', 'Invalid credentials');
        }
    }

    public function dashboard()
    {
        return view('employee.dashboard');
    }
    public function logout()
    {
        Auth::guard('teacher')->logout();
        return redirect()->route('teacher.login');
    }

    public function assignwork()
    {
        $employeeId = Auth::guard('teacher')->id();

        $assignments = WorkAssignment::with('booking')
        ->where('employee_id', $employeeId)
        ->orderBy('created_at', 'desc') // Order by creation date (newest first)
        ->get();
    

        return view('employee.assignwork', compact('assignments'));
    }

    public function showUpdateForm(WorkAssignment $assignment)
    {
        return view('employee.update-work', compact('assignment'));
    }

    public function updateWork(Request $request, WorkAssignment $assignment)
    {
        // Validate the request
        $request->validate([
            'status' => 'required|in:in_progress,completed',
            'notes' => 'nullable|string',
            'defects' => 'nullable|string',
            'images' => 'nullable|array', // Images are optional
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'live_camera' => 'required|string',  // Validation for base64 live_camera image
        ]);

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Store in 'public/work-updates' under the 'public' disk
                $path = $image->store('work-updates', 'public');  // Use 'public' disk
                // Correct the path to ensure it's publicly accessible
                $imagePaths[] = 'work-updates/' . basename($path); // Store relative path
            }
        }

        // Process live camera image
        $liveCameraStoredPath = null;
        if ($request->has('live_camera')) {
            $liveCameraData = $request->input('live_camera');

            // Ensure base64 string is valid
            if (strpos($liveCameraData, ',') !== false) {
                $imageData = explode(',', $liveCameraData)[1]; // Extract base64 part
                $imageData = base64_decode($imageData);

                // Validate the decoded image data
                if ($imageData === false) {
                    return redirect()->back()->with('error', 'Invalid live camera image data.');
                }

                // Generate unique file name
                $filename = uniqid('camera_') . '.png';
                $liveCameraPath = 'live_camera/' . $filename;

                // Store in 'storage/app/public/live_camera/' on the 'public' disk
                Storage::disk('public')->put($liveCameraPath, $imageData);

                // Use correct asset path for frontend display
                $liveCameraStoredPath = 'live_camera/' . $filename; // Store relative path
            }
        }

        // Update work assignment with images, location, and live camera image
        $assignment->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'defects' => $request->defects,
            'images' => !empty($imagePaths) ? json_encode($imagePaths) : null,  // Store only if images exist
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'live_camera' => $liveCameraStoredPath,  // Store live camera image path
        ]);

        return redirect()->route('teacher.assignwork')->with('success', 'Work updated successfully');
    }




    public function dailyUpdate(WorkAssignment $assignment)
    {
        return view('employee.daily-update', compact('assignment'));
    }

    public function storeDailyUpdate(Request $request, WorkAssignment $assignment)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
            'notes' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/daily-updates');
                $imagePaths[] = str_replace('public/', '', $path);
            }
        }

        // Geocoding
        try {
            $geocoder = new Geocoder(env('OPENCAGE_API_KEY'));
            $result = $geocoder->geocode($request->latitude . ',' . $request->longitude);
            $address = $result['results'][0]['formatted'] ?? 'Unknown location';
        } catch (\Exception $e) {
            Log::error('Geocoding error: ' . $e->getMessage());
            $address = 'Location lookup failed';
        }

        // Create daily update
        DailyUpdate::create([
            'work_assignment_id' => $assignment->id,
            'date' => now()->format('Y-m-d'),
            'status' => $request->status,
            'notes' => $request->notes,
            'images' => $imagePaths,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'location_address' => $address
        ]);

        return redirect()->route('teacher.daily-updates')
            ->with('success', 'Daily update submitted successfully');
    }
}
