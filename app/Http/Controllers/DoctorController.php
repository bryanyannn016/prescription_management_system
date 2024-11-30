<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Record;
use App\Models\Medication;
use App\Models\Prescription;
use App\Models\Diagnosis;
use App\Models\User;
use App\Models\File; 
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function index(Request $request)
{
    // Get the current date
    $currentDate = now()->toDateString(); // Format: YYYY-MM-DD
    
    // Get the health facility of the authenticated user
    $userHealthFacility = auth()->user()->health_facility;

    // Base query to fetch patients with records for the current date and matching health facility
    $query = Patient::with(['records' => function ($query) use ($currentDate, $userHealthFacility) {
            $query->whereDate('date', $currentDate) // Filter by current date
                  ->where(function ($query) {
                      $query->where('service', 'Refill')
                            ->orWhere('service', 'Medical Consultation (Face to Face)');
                  })
                  ->whereHas('user', function ($query) use ($userHealthFacility) {
                      $query->where('health_facility', $userHealthFacility); // Match health facility
                  });
        }])->whereHas('records', function ($query) use ($currentDate, $userHealthFacility) {
            $query->whereDate('date', $currentDate) // Ensure the patient has at least one record for today
                  ->whereHas('user', function ($query) use ($userHealthFacility) {
                      $query->where('health_facility', $userHealthFacility); // Match health facility
                  });
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

    // Get the health facility of the currently logged-in user
    $userHealthFacility = auth()->user()->health_facility;

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

    // Retrieve patients and filter based on health facility association in records
    $patients = $query->whereHas('records', function ($query) use ($userHealthFacility) {
        $query->whereHas('user', function ($query) use ($userHealthFacility) {
            $query->where('health_facility', $userHealthFacility);
        });
    })->get();

    // Determine if no records were found
    $noRecordsFound = $patients->isEmpty();

    // If the request is AJAX, return JSON response
    if ($request->ajax()) {
        return response()->json(['noRecordsFound' => $noRecordsFound]);
    }

    // Return the view with the filtered patients and the flag
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

    // Retrieve the record using the provided record_id
    $record = Record::find($validatedData['record_id']);
    
    // Ensure the record exists and the doctor_id is null before updating it
    if ($record && !$record->doctor_id) {
        // Set the doctor_id to the authenticated user's ID
        $record->doctor_id = auth()->id();
        $record->save(); // Save the updated record
    }

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


public function docRefill(Request $request)
{
    $patientId = $request->input('patient_id');
    $recordId = $request->input('record_id');

    $patient = Patient::with('records')->find($patientId);
    $record = Record::find($recordId);
    
    // Fetch the prescriptions related to the record
    $prescriptions = Prescription::where('record_id', $recordId)->get();

    // Get the refill date from one of the prescriptions (assuming they share the same date)
    $refillDate = $prescriptions->first()->refill_date ?? null;

    return view('doctor.docRefill', compact('patient', 'record', 'prescriptions', 'refillDate'));
}

public function submitRefill(Request $request, $recordId)
{
    // Get the form inputs
    $quantities = $request->input('quantity', []);
    $refillables = $request->input('refillable', []);
    $refillDate = $request->input('refill_date');

    // Retrieve all prescriptions linked to the specified record ID
    $prescriptions = Prescription::where('record_id', $recordId)->get();

    foreach ($prescriptions as $prescription) {
        // Update the quantity for each prescription
        if (isset($quantities[$prescription->id])) {
            $prescription->quantity = $quantities[$prescription->id];
        }

        // Update the isRefillable status
        $prescription->isRefillable = isset($refillables[$prescription->id]) ? true : false;

        // Update the refill date
        $prescription->refill_date = $refillDate;

        // Save changes to the prescription
        $prescription->save();
    }

    // Retrieve the record and update doctor_id and status
    $record = Record::find($recordId);
    if ($record) {
        // Update the doctor_id to the authenticated user's ID
        $record->doctor_id = auth()->id(); // Set the doctor_id to the authenticated user's ID
        
        // Update the status to 'Approved'
        $record->status = 'Approved';
        
        // Save the changes to the record
        $record->save();
    }

    // Redirect back to the doctor dashboard after updating
    return redirect()->route('doctor.dashboard')->with('success', 'Prescription refills updated successfully, and record status set to Approved.');
}

public function deferred($recordId)
{
    // Remove all prescriptions associated with the record ID
    Prescription::where('record_id', $recordId)->delete();

    // Update the status of the record to 'Deferred'
    $record = Record::find($recordId);
    if ($record) {
        $record->status = 'Deferred';
        $record->save();
    }

    // Redirect back to the doctor dashboard with a success message
    return redirect()->route('doctor.dashboard')->with('success', 'Prescriptions have been deferred, and the record status has been updated to Deferred.');
}



public function removePrescription($id)
{
    $prescription = Prescription::find($id);
    
    if ($prescription) {
        $prescription->delete(); // Remove from the database
        return response()->json(['success' => true]);
    }
    
    return response()->json(['success' => false], 404); // Not found
}

public function allPatients()
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
      return view('doctor.records', ['patients' => $eligiblePatients]);
}

public function recordfindPatient(Request $request)
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
    return view('doctor.records', compact('patients', 'noRecordsFound'));
}


    public function viewPatientRecord($id)
    {
        // Retrieve the patient by ID
        $patient = Patient::findOrFail($id); 
    
        // Retrieve all records associated with the patient
        $records = Record::where('patient_id', $id)->get();
    
        // Return a view and pass the patient and records data
        return view('doctor.view_patient_record', compact('patient', 'records'));
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
    return view('doctor.patient_record_view', compact('patient', 'record', 'diagnoses', 'prescriptions', 'files'));
}

public function accountSettings()
{
    // Assuming the logged-in user information is available via Auth
    $user = auth()->user();

    // Pass the user details to the blade view
    return view('doctor.account_settings', compact('user'));
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
    return redirect()->route('doctor.dashboard')->with('success', 'Password successfully changed.');
}

}
