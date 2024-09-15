<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use Carbon\Carbon; // Import Carbon for date manipulation

class NurseController extends Controller
{
    public function index()
{
    $patients = Patient::all();
    return view('nurse.dashboard', compact('patients'));
}


    public function createPatient()
    {
        return view('nurse.create_patient'); // Create a view for the form
    }

    public function storePatient(Request $request)
    {
        // Validate the form data
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthday' => 'required|date_format:Y-m-d', // Ensure the date format is correct
            'sex' => 'required|in:Male,Female',
            'mhp_no' => 'required|string|max:255',
            'mhp_exp' => 'required|date_format:Y-m-d', // Ensure the date format is correct
            'address' => 'required|string|max:255',
            'barangay' => 'nullable|string|max:255',
        ]);

        // Calculate age based on the birthday using Carbon's createFromFormat
        $birthday = Carbon::createFromFormat('Y-m-d', $request->input('birthday'));
        $age = $birthday->diffInYears(Carbon::now()); // Calculate the age

        // Create and store the patient data in the database
        Patient::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'birthday' => $request->input('birthday'),
            'sex' => $request->input('sex'),
            'mhp_no' => $request->input('mhp_no'),
            'mhp_exp' => $request->input('mhp_exp'),
            'address' => $request->input('address'),
            'barangay' => $request->input('barangay'),
            'age' => $age, // Store the calculated age
        ]);

        // Redirect back to the dashboard with success message
        return redirect()->route('nurse.dashboard')->with('success', 'Patient record created successfully.');
    }

    public function findPatient(Request $request)
{
    // Validate the input
    $request->validate([
        'lastName' => 'nullable|string|max:255',
        'firstName' => 'nullable|string|max:255',
        'dob' => 'nullable|date',
        'middleName' => 'nullable|string|max:255',
    ]);

    // Query the patients table
    $query = Patient::query();

    if ($request->filled('lastName')) {
        $query->where('last_name', 'like', '%' . $request->input('lastName') . '%');
    }
    if ($request->filled('firstName')) {
        $query->where('first_name', 'like', '%' . $request->input('firstName') . '%');
    }
    if ($request->filled('dob')) {
        $query->whereDate('birthday', $request->input('dob'));
    }
    if ($request->filled('middleName')) {
        $query->where('middle_name', 'like', '%' . $request->input('middleName') . '%');
    }

    // Get the results
    $patients = $query->get();

    // Determine if no records were found
    $noRecordsFound = $patients->isEmpty();

    if ($request->ajax()) {
        return response()->json(['noRecordsFound' => $noRecordsFound]);
    }

    // Return the view with the results and the flag
    return view('nurse.dashboard', compact('patients', 'noRecordsFound'));
}

    
    
}
