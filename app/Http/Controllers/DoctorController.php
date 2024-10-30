<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Record;
use App\Models\Medication;
use App\Models\Prescription;
use App\Models\Diagnosis;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        // Get the current date
        $currentDate = now()->toDateString(); // Format: YYYY-MM-DD
    
        // Base query to fetch patients with records for the current date
        $query = Patient::with(['records' => function ($query) use ($currentDate) {
            $query->whereDate('date', $currentDate) // Filter by current date
                  ->where(function ($query) {
                      $query->where('status', 'Pending')
                            ->orWhere('service', 'Medical Consultation (Face to Face)');
                  });
        }])->whereHas('records', function ($query) use ($currentDate) {
            $query->whereDate('date', $currentDate); // Ensure the patient has at least one record for today
        });
    
        // Apply search filters if any search criteria are provided
        if ($request->has('searched')) {
            if ($request->filled('lastName')) {
                $query->where('last_name', 'like', '%' . $request->lastName . '%');
            }
            if ($request->filled('firstName')) {
                $query->where('first_name', 'like', '%' . $request->firstName . '%');
            }
            if ($request->filled('dob')) {
                $query->whereDate('birthday', $request->dob);
            }
            if ($request->filled('middleName')) {
                $query->where('middle_name', 'like', '%' . $request->middleName . '%');
            }
        }
    
        // Get the filtered patients
        $patients = $query->get();
    
        return view('doctor.dashboard', compact('patients'));
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
    
        // Initialize the query to fetch patients
        $query = Patient::query();
    
        // Build the query based on provided input
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
        return view('doctor.dashboard', compact('patients', 'noRecordsFound'));
    }

    public function selectPatient(Request $request)
{
    $patientId = $request->input('patient_id');
    $recordId = $request->input('record_id');
    

    // Redirect to the diagnosis page with both patient and record IDs
    return redirect()->route('doctor.diagnosis', ['patient_id' => $patientId, 'record_id' => $recordId]);
}

public function showDiagnosis($patient_id, $record_id)
{
    // Retrieve the patient and their specific record using the IDs
    $patient = Patient::find($patient_id);
    $record = $patient->records()->where('id', $record_id)->first();

    // Check if patient or record is missing
    if (!$patient || !$record) {
        return redirect()->back()->with('error', 'Patient or record not found.');
    }

    // Retrieve diagnoses stored in the session (if coming back from the prescription page)
    $diagnoses = session('diagnoses', []);

    // Retrieve files associated with the record
    $files = $record->files;

    // Return the view with the patient, record, diagnoses, and files
    return view('doctor.diagnosis', compact('patient', 'record', 'diagnoses', 'files'));
}


public function storeDiagnosis(Request $request)
{
    // Validate the request data
    $request->validate([
        'diagnoses' => 'required|array',
        'diagnoses.*.diagnosis' => 'required|string',
        'diagnoses.*.ongoing' => 'required|boolean',
        'patient_id' => 'required|exists:patients,id',
        'record_id' => 'required|exists:records,id'
    ]);

    // Store the diagnoses in the session or database as needed
    session(['diagnoses' => $request->input('diagnoses')]);

    // Directly route to the prescription view (replace 'doctor.prescription' with your route)
    $redirectUrl = route('doctor.prescription', [
        'patient_id' => $request->input('patient_id'),
        'record_id' => $request->input('record_id')
    ]);

    // Return a JSON response with the redirect URL
    return response()->json(['redirectUrl' => $redirectUrl]);
}

public function prescription($patient_id, $record_id)
{
    // Retrieve the diagnoses stored in the session
    $diagnoses = session('diagnoses', []);

    // Retrieve patient details based on the patient_id
    $patient = Patient::find($patient_id); // Make sure to import the Patient model at the top of your controller

    // Retrieve record details based on record_id
    $record = Record::find($record_id); // Make sure to import the Record model at the top of your controller

    $medications = Medication::all(); // Ensure you import the Medication model


    // Check if patient data exists
    if (!$patient) {
        return redirect()->route('doctor.dashboard')->with('error', 'Patient not found.');
    }

    // Check if record data exists
    if (!$record) {
        return redirect()->route('doctor.dashboard')->with('error', 'Record not found.');
    }

    // Prepare patient data for view
    $patientDetails = [
        'last_name' => $patient->last_name,
        'first_name' => $patient->first_name,
        'middle_name' => $patient->middle_name,
        'age' => $patient->age,
        'sex' => $patient->sex,
        'barangay' => $patient->barangay,
        'patient_number' => $patient->patient_number,
        // Add other fields as necessary
        'service' => $record->service, // Add service from record
        'date' => \Carbon\Carbon::parse($record->date)->format('F j, Y'), // Format the date for display
    ];

    // Return the view with the data
    return view('doctor.prescription', compact('diagnoses', 'patientDetails', 'record', 'medications'));
}


public function storePrescription(Request $request)
{
    // Validate incoming data
    $validatedData = $request->validate([
        'diagnoses' => 'required|array',
        'prescriptions' => 'required|array',
        'patient_id' => 'required|string',
        'record_id' => 'required|integer',
    ]);

    // Get the current date
    $currentDate = now();

    // Loop through diagnoses and store them
    foreach ($validatedData['diagnoses'] as $diagnosis) {
        // Ensure that the required fields exist
        if (isset($diagnosis['diagnosis']) && isset($diagnosis['ongoing'])) {
            Diagnosis::create([
                'record_id' => $validatedData['record_id'],
                'diagnosis' => $diagnosis['diagnosis'],
                'isOngoing' => $diagnosis['ongoing'],
            ]);
        }
    }

    // Loop through prescriptions and store them
    foreach ($validatedData['prescriptions'] as $prescription) {
        // Ensure that the required fields exist
        if (isset($prescription['medication']) && isset($prescription['quantity']) && isset($prescription['sig'])) {
            Prescription::create([
                'record_id' => $validatedData['record_id'],
                'medication' => $prescription['medication'],
                'quantity' => $prescription['quantity'],
                'sig' => $prescription['sig'],
                'isRefillable' => $prescription['isRefillable'],
                'refill_date' => $prescription['refillDate'] ?: null, // Handle nullable refill date
                'date_started' => $currentDate, // Set date_started to current date
                'last_prescribed' => $currentDate, // Set last_prescribed to current date
            ]);
        }
    }

    return response()->json(['success' => true, 'message' => 'Prescription saved successfully!']);
}


    
}
