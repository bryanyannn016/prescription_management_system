<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Record; 
use App\Models\File; 
use App\Models\Diagnosis; 
use App\Models\User; 
use App\Models\Prescription; 
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

use Carbon\Carbon; 
use Illuminate\Support\Facades\Storage; 

class NurseController extends Controller
{
    public function index()
    {
        // Get the health facility of the currently logged-in user
        $userHealthFacility = auth()->user()->health_facility;
    
        // Initialize an empty collection to store eligible patients
        $eligiblePatients = collect();
    
        // Retrieve all patients
        $patients = Patient::all();
    
        foreach ($patients as $patient) {
            // Find a single record associated with the patient_id in the records table
            $record = Record::where('patient_id', $patient->id)->first();
    
            if ($record) {
                // Retrieve the user associated with this record
                $recordUser = User::find($record->user_id);
    
                // Check if the health facility of this user matches the logged-in user's health facility
                if ($recordUser && $recordUser->health_facility === $userHealthFacility) {
                    $eligiblePatients->push($patient); // Add the patient to the eligible list
                }
            }
        }
    
        // Pass only eligible patients to the view
        return view('nurse.dashboard', ['patients' => $eligiblePatients]);
    }
    

    public function createPatient()
    {
        return view('nurse.create_patient'); // Create a view for the form
    }

    public function savePatient(Request $request)
{
    // Validate the incoming request
    $validatedData = $request->validate([
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        'birthday' => 'required|date',
        'sex' => 'required|string',
        'mhp_no' => 'required|string|max:255',
        'mhp_exp' => 'required|date',
        'address' => 'required|string|max:255',
        'barangay' => 'nullable|string',
        // Add other validation rules as needed
    ]);

    // Set the middle name to null if 'no_middle_name' is checked
    if ($request->has('no_middle_name')) {
        $validatedData['middle_name'] = null; // or ''
    }

    // Set the barangay to null if 'outside_makati' is checked
    if ($request->has('outside_makati')) {
        $validatedData['barangay'] = null; // or ''
    }

    // Store the patient record in the session or database
    session(['patient_data' => $validatedData]);

    // Redirect to a success page or back with a success message
    return redirect()->route('nurse.admitPatient')->with('success', 'Patient record created successfully!');
}


    public function admitPatient()
    {
        // Retrieve patient data from session
        $patient = session('patient_data');

        // Return the view with patient details
        return view('nurse.admit_patient', compact('patient'));
    }

    public function storeAdmitPatient(Request $request)
{
    // Validate incoming request
    $request->validate([
        'service' => 'required|string|max:255',
        'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Validate multiple files
    ]);

    // Retrieve patient data from session
    $patientData = session('patient_data');

    // Ensure patient data is available
    if (!$patientData || !isset($patientData['birthday'])) {
        return redirect()->back()->withErrors(['patient_data' => 'Patient data is missing or incomplete.']);
    }

    // Retrieve the birthday from patient data
    $birthdayInput = $patientData['birthday'];

    // Check if the birthday is valid and calculate age
    $birthday = Carbon::createFromFormat('Y-m-d', $birthdayInput);
    if (!$birthday) {
        return redirect()->back()->withErrors(['birthday' => 'Invalid date format.']);
    }

    // Calculate age
    $age = $birthday->diffInYears(Carbon::now());

    // Create a new Patient record
    $patient = new Patient();
    $patient->first_name = $patientData['first_name'];
    $patient->middle_name = $patientData['middle_name'];
    $patient->last_name = $patientData['last_name'];
    $patient->birthday = $birthdayInput; // Store the valid birthday
    $patient->sex = $patientData['sex'];
    $patient->mhp_no = $patientData['mhp_no'];
    $patient->mhp_exp = $patientData['mhp_exp'];
    $patient->address = $patientData['address'];
    $patient->barangay = $patientData['barangay'];
    $patient->age = $age; // Use the calculated age
    $patient->save();

    // Create a new Record
    $record = new Record();
    $record->patient_id = $patient->id; // Link to the patient
    $record->service = $request->input('service'); // Use the service from the request
    $record->status = $request->input('service') === 'Medical Consultation (Face to Face)' ? 'Approved' : 'Pending'; // Determine status
    $record->date = now(); // Set current date
    $record->user_id = auth()->id(); 
    $record->doctor_id = null;
    $record->save(); // Save the record

    // Handle file uploads
    if ($request->hasFile('attachments')) {  // Use 'attachments' instead of 'files'
        foreach ($request->file('attachments') as $file) {
            // Generate a unique filename based on the current date and original filename
            $filename = date('YmdHi') . '_' . $file->getClientOriginalName();

            // Store the file in the 'storage/app/public' directory
            $filePath = $file->storeAs('', $filename, 'public');

            // Create a new File record for each uploaded file
            $fileRecord = new File();
            $fileRecord->record_id = $record->id; // Link to the record
            $fileRecord->file_path = 'storage/' . $filename; // Store the relative file path
            $fileRecord->save(); // Save the file record
        }
    }

    // Clear the session data after saving
    Session::forget('patient_data');

    // Redirect with success message
    return redirect()->route('nurse.dashboard')->with('success', 'Patient admitted successfully!');
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

    // Get the health facility of the currently logged-in user
    $userHealthFacility = auth()->user()->health_facility;

    // Initialize the query to fetch patients with filtering conditions
    $query = Patient::query();

    // Apply search filters if provided
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

    // Retrieve patients and filter based on health facility association in records
    $patients = $query->whereHas('records', function ($query) use ($userHealthFacility) {
        $query->whereHas('user', function ($query) use ($userHealthFacility) {
            $query->where('health_facility', $userHealthFacility);
        });
    })->get();

    // Determine if no records were found
    $noRecordsFound = $patients->isEmpty();

    // Return JSON response if the request is AJAX
    if ($request->ajax()) {
        return response()->json(['noRecordsFound' => $noRecordsFound]);
    }

    // Return the view with the filtered patients and the flag
    return view('nurse.dashboard', compact('patients', 'noRecordsFound'));
    }


    public function viewPatientRecord($id)
{
    // Retrieve the patient by ID
    $patient = Patient::findOrFail($id); 

    // Retrieve all records associated with the patient
    $records = Record::where('patient_id', $id)->get();

    // Return a view and pass the patient and records data
    return view('nurse.view_patient_record', compact('patient', 'records'));
}

public function selectPatient(Request $request)
{
    // Validate the patient ID
    $validatedData = $request->validate([
        'patient_id' => 'required|exists:patients,id',
    ]);

    // Retrieve the patient by ID
    $patient = Patient::findOrFail($validatedData['patient_id']);

    // Concatenate the name in the format: "Last Name, First Name Middle Name"
    $fullName = $patient->last_name . ', ' . $patient->first_name . ' ' . $patient->middle_name;

    // Store the relevant data in the session, including the patient ID
    session([
        'selected_patient' => [
            'id' => $patient->id, // Store the patient ID
            'full_name' => $fullName,
            'sex' => $patient->sex,
            'birthday' => $patient->birthday,
            'patient_number' => $patient->patient_number,
            'barangay' => $patient->barangay,
        ]
    ]);

    // Redirect to the existing_patient view and pass the patient's data
    return view('nurse.existing_patient', [
        'patient' => $patient,
    ]);
}


public function storeExistingPatient(Request $request)
{
    // Log the session data for debugging
    \Log::info('Session Data:', session('selected_patient'));

    // Validate incoming request
    $request->validate([
        'service' => 'required|string|max:255',
        'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Validate multiple files
    ]);

    // Retrieve patient data from session
    $patientData = session('selected_patient');

    // Ensure patient data is available
    if (!$patientData || !isset($patientData['patient_number'])) {
        return redirect()->back()->withErrors(['patient_data' => 'Patient data is missing or incomplete.']);
    }

    // Retrieve patient ID from the session
    $patientId = $patientData['id'];

    // Ensure the patient exists in the database
    $existingPatient = Patient::find($patientId);
    if (!$existingPatient) {
        return redirect()->back()->withErrors(['patient_data' => 'Selected patient not found.']);
    }

    // Create a new Record for the existing patient
    $record = new Record();
    $record->patient_id = $existingPatient->id; // Link to the existing patient
    $record->service = $request->input('service'); // Use the service from the request
    $record->status = $request->input('service') === 'Medical Consultation (Face to Face)' ? 'Approved' : 'Pending'; // Determine status
    $record->date = now(); // Set current date
    $record->user_id = auth()->id(); 
    $record->save(); // Save the record

    // Handle file uploads
    if ($request->hasFile('attachments')) { // Use 'attachments' instead of 'files'
        foreach ($request->file('attachments') as $file) {
            // Generate a unique filename based on the current date and original filename
            $filename = date('YmdHi') . '_' . $file->getClientOriginalName();

            // Store the file in the 'storage/app/public' directory
            $filePath = $file->storeAs('', $filename, 'public');

            // Create a new File record for each uploaded file
            $fileRecord = new File();
            $fileRecord->record_id = $record->id; // Link to the record
            $fileRecord->file_path = 'storage/' . $filename; // Store the relative file path
            $fileRecord->save(); // Save the file record
        }
    }

    // Clear the session data after saving
    Session::forget('selected_patient');

    // Redirect with success message
    return redirect()->route('nurse.dashboard')->with('success', 'Record saved for existing patient successfully!');
}


public function viewExistingPatientRecord($patient_id, $record_id)
{
    // Fetch patient details
    $patient = Patient::findOrFail($patient_id);

    // Fetch record details
    $record = Record::findOrFail($record_id);

    $diagnoses = collect(); // Default empty collection for diagnoses

    if ($record->service === 'Refill') {
        // Search for a "Medical Consultation (Face to Face)" record for the same patient
        $consultationRecord = Record::where('patient_id', $patient_id)
                                    ->where('service', 'Medical Consultation (Face to Face)')
                                    ->first();

        if ($consultationRecord) {
            // Fetch ongoing diagnoses from the consultation record
            $diagnoses = Diagnosis::where('record_id', $consultationRecord->id)
                                  ->where('isOngoing', true)
                                  ->get();
        }
    } else {
        // Fetch diagnoses associated with the current record
        $diagnoses = Diagnosis::where('record_id', $record_id)->get();
    }

    // Fetch prescriptions and files as usual
    $prescriptions = Prescription::where('record_id', $record_id)->get();
    $files = File::where('record_id', $record_id)->get();

    // Pass data to the view
    return view('nurse.patient_record_view', compact('patient', 'record', 'diagnoses', 'prescriptions', 'files'));
}


public function prescriptionList()
{
    // Get today's date
    $today = now()->format('Y-m-d');

    // Get the health facility of the currently logged-in user
    $userHealthFacility = auth()->user()->health_facility;

    // Get admitted patients for 'Refill' from the records table
    $admitPatients = Record::where('service', 'Refill')
        ->where('date', $today)
        ->whereHas('user', function ($query) use ($userHealthFacility) {
            $query->where('health_facility', $userHealthFacility);
        })
        ->get(['id', 'patient_id', 'status']); // Include 'status'

    // Get refill patients from the prescription table with today's refill date
    $refillPatients = Prescription::where('refill_date', $today)
        ->pluck('record_id')
        ->map(function ($recordId) {
            return Record::where('id', $recordId)->pluck('patient_id')->first();
        })
        ->unique()
        // Exclude patients already admitted for 'Refill' today
        ->reject(function ($patientId) use ($admitPatients, $today) {
            return Record::where('patient_id', $patientId)
                ->where('service', 'Refill')
                ->where('date', $today)
                ->exists();
        })
        ->filter(function ($patientId) use ($userHealthFacility) {
            // Check if the patient is associated with the same health facility as the logged-in user
            return Record::where('patient_id', $patientId)
                ->whereHas('user', function ($query) use ($userHealthFacility) {
                    $query->where('health_facility', $userHealthFacility);
                })
                ->exists();
        });

    // Retrieve patient details for admitted and refill patients
    $admitPatientDetails = Patient::whereIn('id', $admitPatients->pluck('patient_id'))->get();
    $refillPatientDetails = Patient::whereIn('id', $refillPatients)->get();

    // Check for each admitted patient's record_id in the prescriptions table
    $admitPatientRecords = $admitPatients->map(function ($record) {
        // Add 'has_prescription' and retain 'status'
        $record->has_prescription = Prescription::where('record_id', $record->id)->exists();
        return $record;
    });

    // Pass all required data to the view
    return view('nurse.prescription_list', compact('admitPatientDetails', 'refillPatientDetails', 'admitPatientRecords'));
}




public function findRefillPatient(Request $request)
{
    // Validate the input
    $request->validate([
        'lastName' => 'nullable|string|max:255',
        'firstName' => 'nullable|string|max:255',
        'dob' => 'nullable|date',
        'middleName' => 'nullable|string|max:255',
    ]);

    // Get today's date
    $today = now()->format('Y-m-d');

    // Get the health facility of the currently logged-in user
    $userHealthFacility = auth()->user()->health_facility;

    // Initialize queries for admitPatients and refillPatients
    $admitPatientsQuery = Record::where('service', 'Refill')
        ->where('date', $today)
        ->whereHas('user', function ($query) use ($userHealthFacility) {
            $query->where('health_facility', $userHealthFacility);
        });

    $refillPatientsQuery = Prescription::where('refill_date', $today)
        ->pluck('record_id')
        ->map(function ($recordId) {
            return Record::where('id', $recordId)->pluck('patient_id')->first();
        })
        ->unique()
        // Exclude patients already admitted for 'Refill' today
        ->reject(function ($patientId) use ($today) {
            return Record::where('patient_id', $patientId)
                ->where('service', 'Refill')
                ->where('date', $today)
                ->exists();
        })
        ->filter(function ($patientId) use ($userHealthFacility) {
            return Record::where('patient_id', $patientId)
                ->whereHas('user', function ($query) use ($userHealthFacility) {
                    $query->where('health_facility', $userHealthFacility);
                })
                ->exists();
        });

    // Apply filters if search parameters are provided
    if ($request->filled('lastName')) {
        $admitPatientsQuery->whereHas('patient', function ($query) use ($request) {
            $query->where('last_name', 'like', '%' . $request->input('lastName') . '%');
        });
        $refillPatientsQuery = $refillPatientsQuery->filter(function ($patientId) use ($request) {
            return Patient::where('id', $patientId)
                ->where('last_name', 'like', '%' . $request->input('lastName') . '%')
                ->exists();
        });
    }

    if ($request->filled('firstName')) {
        $admitPatientsQuery->whereHas('patient', function ($query) use ($request) {
            $query->where('first_name', 'like', '%' . $request->input('firstName') . '%');
        });
        $refillPatientsQuery = $refillPatientsQuery->filter(function ($patientId) use ($request) {
            return Patient::where('id', $patientId)
                ->where('first_name', 'like', '%' . $request->input('firstName') . '%')
                ->exists();
        });
    }

    if ($request->filled('dob')) {
        $admitPatientsQuery->whereHas('patient', function ($query) use ($request) {
            $query->whereDate('birthday', $request->input('dob'));
        });
        $refillPatientsQuery = $refillPatientsQuery->filter(function ($patientId) use ($request) {
            return Patient::where('id', $patientId)
                ->whereDate('birthday', $request->input('dob'))
                ->exists();
        });
    }

    if ($request->filled('middleName')) {
        $admitPatientsQuery->whereHas('patient', function ($query) use ($request) {
            $query->where('middle_name', 'like', '%' . $request->input('middleName') . '%');
        });
        $refillPatientsQuery = $refillPatientsQuery->filter(function ($patientId) use ($request) {
            return Patient::where('id', $patientId)
                ->where('middle_name', 'like', '%' . $request->input('middleName') . '%')
                ->exists();
        });
    }

    // Get the admitPatients and refillPatients after filtering
    $admitPatients = $admitPatientsQuery->get(['id', 'patient_id']);
    $refillPatients = $refillPatientsQuery->values(); // Re-index after filtering

    // Retrieve patient details for admitted and refill patients
    $admitPatientDetails = Patient::whereIn('id', $admitPatients->pluck('patient_id'))->get();
    $refillPatientDetails = Patient::whereIn('id', $refillPatients)->get();

    // Check for each admitted patient's record_id in the prescriptions table
    $admitPatientRecords = $admitPatients->map(function ($record) {
        $record->has_prescription = Prescription::where('record_id', $record->id)->exists();
        return $record;
    });

    // Pass all required data to the view
    return view('nurse.prescription_list', compact('admitPatientDetails', 'refillPatientDetails', 'admitPatientRecords'));
}


public function deferPatient(Request $request)
{
    // Retrieve the patient ID from the request
    $patientId = $request->input('patient_id');
    
    // Fetch patient details
    $patient = Patient::find($patientId);

    // Redirect to defer.blade.php with the patient data
    return view('nurse.defer', compact('patient'));
}

public function deferRefillDate(Request $request)
{
    // Validate the date input
    $request->validate([
        'defer_date' => 'required|date',
        'patient_id' => 'required|exists:patients,id'
    ]);

    $patientId = $request->input('patient_id');
    $newRefillDate = $request->input('defer_date');

    // Retrieve the record ID associated with the patient
    $record = Record::where('patient_id', $patientId)->first();

    if ($record) {
        // Update only refill dates for prescriptions associated with this record ID where isRefillable is true
        $updatedCount = Prescription::where('record_id', $record->id)
            ->where('isRefillable', true) // Only update if isRefillable is true
            ->update(['refill_date' => $newRefillDate]);

        if ($updatedCount > 0) {
            return redirect()->route('nurse.prescription_list')->with('success', 'Refill dates have been deferred successfully.');
        } else {
            return redirect()->route('nurse.prescription_list')->with('info', 'No refillable prescriptions found to update.');
        }
    } else {
        // If no record is found, return an error message
        return redirect()->route('nurse.prescription_list')->with('error', 'No record found for this patient.');
    }
}

public function refillPatient(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'patient_id' => 'required|exists:patients,id',
    ]);

    // Get the selected patient ID
    $patientId = $request->input('patient_id');

    // Fetch patient details
    $patient = Patient::findOrFail($patientId);

    // Fetch today's record for the patient with service type 'Refill'
    $today = now()->format('Y-m-d');
    $record = Record::where('patient_id', $patientId)
        ->where('date', $today)
        ->where('service', 'Refill') // Filter for 'Refill' service
        ->first();

    // Check if a record exists for today with the service 'Refill'
    if (!$record) {
        return redirect()->back()->with('error', 'No refill record found for today.');
    }

    // Get the most recent previous record ID for this patient
    $previousRecord = Record::where('patient_id', $patientId)
        ->where('id', '<', $record->id)
        ->latest('id') // Sort by most recent
        ->first();

    // Check if there is a previous record
    if (!$previousRecord) {
        return redirect()->back()->with('error', 'No previous record found for this patient.');
    }

    // Check if the status of the previous record is 'Deferred'
    if ($previousRecord->status === 'Deferred') {
        // Get the next previous record (if any)
        $previousRecord = Record::where('patient_id', $patientId)
            ->where('id', '<', $previousRecord->id)
            ->latest('id') // Sort by most recent
            ->first();

        // If there is no next previous record, return an error
        if (!$previousRecord) {
            return redirect()->back()->with('error', 'No previous record found to fetch medications.');
        }
    }

    // Fetch refillable prescriptions using the (possibly updated) previous record ID
    $prescriptions = Prescription::where('record_id', $previousRecord->id)
        ->where('isRefillable', true)
        ->get();

    // Pass the patient details, record, and prescriptions to the refill view
    return view('nurse.refill', compact('patient', 'record', 'prescriptions'));
}


public function storeRefill(Request $request)
{
    // Validate incoming request data
    $validated = $request->validate([
        'medications' => 'required|array',
        'refill_date' => 'required|date',
        'record_id' => 'required|integer|exists:records,id', // Ensure record_id exists
    ]);

    try {
        // Iterate through each medication in the request
        foreach ($validated['medications'] as $medicationData) {
            // Create a new prescription entry
            Prescription::create([
                'record_id' => $validated['record_id'], // Associate with the record
                'medication' => $medicationData['medication'],
                'quantity' => $medicationData['quantity'],
                'sig' => $medicationData['sig'],
                'isRefillable' => true, // Mark as refillable if needed
                'refill_date' => $validated['refill_date'],
                'date_started' => $medicationData['date_started'], // Store date_started from the request
                'last_prescribed' => now(), // Or set this to the last prescribed date
            ]);
        }

        // Return success response
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        // Return error response
        return response()->json(['success' => false, 'message' => 'Failed to submit refill.'], 500);
    }
}


public function patient_list()
{
    // Get the health facility of the currently authenticated user
    $userHealthFacility = auth()->user()->health_facility;

    // Get today's date in the format 'Y-m-d' (you can modify the format based on your database column type)
    $today = Carbon::today();

    // Retrieve all records where the associated user's health facility matches the auth user's
    // and the record's created_at is today's date
    $records = Record::whereHas('user', function($query) use ($userHealthFacility) {
            $query->where('health_facility', $userHealthFacility);
        })
        ->whereDate('date', $today) // Add this line to filter by today's date
        ->select('id', 'service', 'status', 'patient_id', 'date', 'user_id')
        ->get();

    // Loop through records to add patient information based on patient_id
    foreach ($records as $record) {
        // Retrieve patient information for each record
        $record->patient = Patient::where('id', $record->patient_id)->first();
    }

    // Pass the filtered records to the view
    return view('nurse.patient_list', ['records' => $records]);
}


public function findPatientRecord(Request $request)
{
    // Validate the input
    $request->validate([
        'lastName' => 'nullable|string|max:255',
        'firstName' => 'nullable|string|max:255',
        'dob' => 'nullable|date',
        'middleName' => 'nullable|string|max:255',
    ]);

    // Get the health facility of the currently logged-in user
    $userHealthFacility = auth()->user()->health_facility;

    // Initialize the query for the Record model
    $query = Record::query();

    // Check if the last name is filled and apply the filter
    if ($request->filled('lastName')) {
        $query->whereHas('patient', function ($q) use ($request) {
            $q->where('last_name', 'like', '%' . $request->input('lastName') . '%');
        });
    }

    // Check if the first name is filled and apply the filter
    if ($request->filled('firstName')) {
        $query->whereHas('patient', function ($q) use ($request) {
            $q->where('first_name', 'like', '%' . $request->input('firstName') . '%');
        });
    }

    // Check if the date of birth is filled and apply the filter
    if ($request->filled('dob')) {
        $query->whereHas('patient', function ($q) use ($request) {
            $q->whereDate('birthday', $request->input('dob'));
        });
    }

    // Check if the middle name is filled and apply the filter
    if ($request->filled('middleName')) {
        $query->whereHas('patient', function ($q) use ($request) {
            $q->where('middle_name', 'like', '%' . $request->input('middleName') . '%');
        });
    }

    // Apply the filter for health facility matching between the record's user and the logged-in user
    $query->whereHas('user', function ($q) use ($userHealthFacility) {
        $q->where('health_facility', $userHealthFacility);
    });

    // Get the results
    $records = $query->get();

    // Determine if no records were found
    $noRecordsFound = $records->isEmpty();

    // Pass the records and noRecordsFound flag to the view
    return view('nurse.patient_list', compact('records', 'noRecordsFound'));
}


public function accountSettings()
{
    // Assuming the logged-in user information is available via Auth
    $user = auth()->user();

    // Pass the user details to the blade view
    return view('nurse.account_settings', compact('user'));
}

public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|confirmed|min:8',
    ]);

    $user = auth()->user();

    // Check if the current password matches
    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Current password is incorrect.']);
    }

    // Update the password
    $user->update([
        'password' => Hash::make($request->new_password),
    ]);

    // Flash a success message
    return redirect()->route('nurse.dashboard')->with('success', 'Password successfully changed.');
}

public function printRecord($recordId)
{
    // Fetch the record and associated patient
    $record = Record::findOrFail($recordId);
    $patient = $record->patient;

    if (!$patient) {
        return redirect()->back()->with('error', 'Patient details not found!');
    }

    // Fetch prescriptions associated with the record_id
    $prescriptions = Prescription::where('record_id', $recordId)->get();

    if ($prescriptions->isEmpty()) {
        return redirect()->back()->with('error', 'No prescriptions found for this record!');
    }

    // Get the date from the record
    $recordDate = $record->date;
    $recordDoctorId = $record->doctor_id;

    // Fetch doctor's details from the users table using the doctor_id
    $doctor = User::find($recordDoctorId);

    if (!$doctor) {
        return redirect()->back()->with('error', 'Doctor details not found!');
    }

    // Prepare data for the PDF
    $data = [
        'first_name' => $patient->first_name,
        'middle_name' => $patient->middle_name,
        'last_name' => $patient->last_name,
        'age' => $patient->age,
        'sex' => $patient->sex,
        'address' => $patient->address,
        'prescriptions' => $prescriptions, // Add prescriptions data here
        'record_date' => $recordDate,
        'doctor_first_name' => $doctor->first_name, // Doctor's first name
        'doctor_middle_name' => $doctor->middle_name, // Doctor's middle name
        'doctor_last_name' => $doctor->last_name, 
        'doctor_license_no' => $doctor->license_no,// Doctor's last name
    ];

    // Generate PDF using the PDF library
    $pdf = PDF::loadView('nurse.print', $data);

    // Stream the PDF
    return $pdf->stream('Prescription.pdf');
}






}
