<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Diagnosis;
use App\Models\Prescription;
use App\Models\Record;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    protected $healthFacilities = [
        'bangkal_health_center' => 'Bangkal Health Center',
        'bangkal_lying_in' => 'Bangkal Lying-In',
        'carmona' => 'Carmona',
        'kasilawan' => 'Kasilawan',
        'la_paz' => 'La Paz',
        'olympia' => 'Olympia',
        'palanan' => 'Palanan',
        'pio_del_pilar_pc' => 'Pio Del Pilar PC',
        'pio_del_pilar_rhu' => 'Pio Del Pilar RHU',
        'poblacion' => 'Poblacion',
        'san_antonio' => 'San Antonio',
        'san_isidro' => 'San Isidro',
        'santa_cruz' => 'Santa Cruz',
        'singkamas' => 'Singkamas',
        'tejeros' => 'Tejeros',
        'guadalupe_nuevo' => 'Guadalupe Nuevo',
        'guadalupe_nuevo_lying_in' => 'Guadalupe Nuevo Lying-In',
        'guadalupe_viejo' => 'Guadalupe Viejo',
        'pinagkaisahan' => 'Pinagkaisahan',
    ];

    public function index()
    {
        return view('admin.dashboard');
    }

    public function showCreateAccountForm()
    {
        return view('admin.create-account');
    }

    public function createAccount(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'type' => 'required|in:doctor,nurse',
            'license_no' => 'required|string|max:255',
            'health_facility' => 'nullable|string|max:255',
        ]);

        // Retrieve the display name of the health facility
        $healthFacilityName = $this->healthFacilities[$request->input('health_facility')] ?? null;

        // Create the user
        User::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make('defaultpassword'), // You can set a default password or generate one
            'type' => $request->input('type'),
            'license_no' => $request->input('license_no'),
            'health_facility' => $healthFacilityName, // Store the display name
        ]);

        return redirect()->route('admin.account-list')->with('success', 'Account created successfully.');
    }

    public function accountList(Request $request)
    {
        // Capture the search query from the GET request
        $search = $request->get('search');
    
        // Retrieve users based on search query if it exists, or all users if it doesn't
        $users = User::when($search, function ($query, $search) {
                return $query->where('first_name', 'like', "%{$search}%")
                             ->orWhere('middle_name', 'like', "%{$search}%")
                             ->orWhere('last_name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
            })
            ->get();
    
        // Pass the users to the view
        return view('admin.account_list', compact('users'));
    }

    public function editAccount($id)
{
    $user = User::findOrFail($id);
    return view('admin.edit_account', compact('user')); // Your edit account view
}


public function update(Request $request, $id)
{
    // Validate the incoming data
    $request->validate([
        'health_facility' => 'required|string|max:255',
        // You can add more validation rules for other fields here if needed
    ]);

    // Find the user by their ID
    $user = User::findOrFail($id);

    // Update the user's health facility and any other fields
    $user->health_facility = $request->input('health_facility');
    

    // Save the updated user record
    $user->save();

    // Redirect back with a success message or to another page
    return redirect()->route('admin.account-list')->with('success', 'Health Facility updated successfully');

}

public function updateStatus($id)
{
    $user = User::findOrFail($id); // Find user by ID
    $user->status = 'Inactive'; // Change the status to 'Inactive'
    $user->save(); // Save the changes
    
    return redirect()->route('admin.account-list')->with('success', 'User status updated to Inactive');
}

public function reports(Request $request)
{
    // Get the filters from the request
    $monthDiagnosis = $request->input('month_diagnosis');
    $yearDiagnosis = $request->input('year_diagnosis');

    $monthPrescription = $request->input('month_prescription');
    $yearPrescription = $request->input('year_prescription');

     // Get the filters from the request
     $monthAgeGroup = $request->input('month_agegroup');
     $yearAgeGroup = $request->input('year_agegroup');
     $medicationAgeGroup = $request->input('medication_agegroup');
    

    // Fetch the top 10 diagnoses
    $diagnoses = $this->getTopDiagnoses($monthDiagnosis, $yearDiagnosis);

    // Fetch the top 10 prescriptions
    $prescriptions = $this->getTopPrescriptions($monthPrescription, $yearPrescription);

    // Fetch distinct months and years for the filters
    $months = Diagnosis::selectRaw('MONTH(created_at) as month')->distinct()->pluck('month');
    $years = Diagnosis::selectRaw('YEAR(created_at) as year')->distinct()->pluck('year');
    

    // Fetch all medications for the medication filter dropdown
    $medications = Prescription::distinct()->pluck('medication');
    
     
    // Fetch drug utilization per age group if a medication is selected
    $ageGroupData = [];
    if ($medicationAgeGroup) {
        $ageGroupData = $this->getDrugUtilizationByAgeGroup($monthAgeGroup, $yearAgeGroup, $medicationAgeGroup);
    }

    // Pass the correct variable name 'ageGroupData' to the view
    return view('admin.dashboard', compact('diagnoses', 'prescriptions', 'months', 'years', 'medications', 'ageGroupData'));
}


public function getDrugUtilizationByAgeGroup($month = null, $year = null, $medication = null)
{
    // Initialize the age group data
    $ageGroups = ['<18', '18-59', '60-69', '>70'];
    $ageGroupData = array_fill_keys($ageGroups, 0);

    // Build the query to fetch prescriptions based on medication and optional month/year filter
    $query = Prescription::where('medication', $medication);

    // Apply month and year filters
    if ($month && $month !== 'all') {
        $query->whereMonth('last_prescribed', $month);
    }

    if ($year && $year !== 'all') {
        $query->whereYear('last_prescribed', $year);
    }

    // Fetch the prescriptions based on the filters
    $prescriptions = $query->get();

    // Use a set to track unique patient IDs already counted
    $countedPatients = [];

    // Iterate over the prescriptions to classify patients by age
    foreach ($prescriptions as $prescription) {
        // Get the record associated with the prescription
        $record = Record::where('id', $prescription->record_id)->first();

        // Get the patient associated with the record
        $patient = Patient::where('id', $record->patient_id)->first();

        // Check if the patient has already been counted for this medication
        if ($patient && !in_array($patient->id, $countedPatients)) {
            // Add patient ID to the counted set
            $countedPatients[] = $patient->id;

            // Classify by age group
            $age = $patient->age;
            if ($age < 18) {
                $ageGroupData['<18']++;
            } elseif ($age >= 18 && $age <= 59) {
                $ageGroupData['18-59']++;
            } elseif ($age >= 60 && $age <= 69) {
                $ageGroupData['60-69']++;
            } else {
                $ageGroupData['>70']++;
            }
        }
    }

    return $ageGroupData;
}



public function getTopDiagnoses($month = null, $year = null)
{
    // Build the query to get diagnoses count, grouped by 'diagnosis'
    $queryDiagnoses = Diagnosis::select('diagnosis', DB::raw('COUNT(*) as count'))
        ->groupBy('diagnosis');
    
    // Apply month and year filters for diagnoses if provided
    if ($month && $month !== 'all') {
        $queryDiagnoses->whereMonth('created_at', $month);
    }
    
    if ($year && $year !== 'all') {
        $queryDiagnoses->whereYear('created_at', $year);
    }

    // Get the diagnoses data, sorted by count, limited to top 10
    $diagnoses = $queryDiagnoses->orderByDesc('count')
        ->limit(10) // Limit to the top 10 diagnoses
        ->get();

    // Fill missing diagnoses with placeholder values
    $missingDiagnosesCount = 10 - $diagnoses->count();
    for ($i = 0; $i < $missingDiagnosesCount; $i++) {
        $diagnoses->push((object) ['diagnosis' => 'N/A', 'count' => 0]);
    }

    return $diagnoses;
}

public function getTopPrescriptions($month = null, $year = null)
{
    // Fetch the top 10 prescriptions with optional filters
    $queryPrescriptions = Prescription::select('medication', DB::raw('SUM(quantity) as total_quantity'))
        ->groupBy('medication');

    // Apply month and year filters for prescriptions if provided
    if ($month && $month !== 'all') {
        $queryPrescriptions->whereMonth('last_prescribed', $month);
    }

    if ($year && $year !== 'all') {
        $queryPrescriptions->whereYear('last_prescribed', $year);
    }

    // Get the prescriptions data, sorted by total_quantity, limited to top 10
    $prescriptions = $queryPrescriptions->orderByDesc('total_quantity')
        ->limit(10) // Limit to the top 10 prescriptions
        ->get();

    // Fill missing prescriptions with placeholder values
    $missingPrescriptionsCount = 10 - $prescriptions->count();
    for ($i = 0; $i < $missingPrescriptionsCount; $i++) {
        $prescriptions->push((object) ['medication' => 'N/A', 'total_quantity' => 0]);
    }

    return $prescriptions;
}


public function patientList(Request $request)
{
    // Fetch all health facilities from the users table for the dropdown
    $healthFacilities = DB::table('users')->pluck('health_facility')->unique();

    // Default query to get all records with service and date
    $query = DB::table('records')
        ->join('patients', 'records.patient_id', '=', 'patients.id')
        ->join('users', 'records.user_id', '=', 'users.id')
        ->select(
            'records.service',
            'records.date',
            'records.status', // Include the status field
            'patients.first_name',
            'patients.middle_name',
            'patients.last_name',
            'users.health_facility'
        )
        ->where('records.status', '!=', 'Deferred'); // Exclude Deferred records

    // Apply filters if they are provided
    if ($request->has('health_facility') && $request->health_facility != '') {
        $query->where('users.health_facility', $request->health_facility);
    }

    if ($request->has('month') && !empty($request->month)) {
        // Filter by selected months
        $query->whereIn(DB::raw('MONTH(records.date)'), $request->month);
    }

    if ($request->has('year') && $request->year != '') {
        // Filter by selected year
        $query->whereYear('records.date', $request->year);
    }

    // Search functionality: check if a search term is provided
    if ($request->has('search') && $request->search != '') {
        // Search by first_name, middle_name, or last_name
        $searchTerm = $request->search;
        $query->where(function ($q) use ($searchTerm) {
            $q->where('patients.first_name', 'LIKE', "%$searchTerm%")
                ->orWhere('patients.middle_name', 'LIKE', "%$searchTerm%")
                ->orWhere('patients.last_name', 'LIKE', "%$searchTerm%");
        });
    }

    // Fetch the filtered records
    $records = $query->get();

    // Fetch available years for the dropdown
    $years = DB::table('records')->select(DB::raw('YEAR(date) as year'))->distinct()->pluck('year');

    return view('admin.patient_list', compact('records', 'healthFacilities', 'years'));
}




public function prescriptionList(Request $request)
{
    // Fetch all health facilities and unique years for the dropdowns
    $healthFacilities = DB::table('users')->pluck('health_facility')->unique();
    $years = DB::table('prescriptions')
        ->select(DB::raw('YEAR(refill_date) as year'))
        ->distinct()
        ->pluck('year');

    // Query to get medication and total quantity prescribed
    $query = DB::table('prescriptions')
        ->join('records', 'prescriptions.record_id', '=', 'records.id')
        ->join('users', 'records.user_id', '=', 'users.id')
        ->select('prescriptions.medication', DB::raw('SUM(prescriptions.quantity) as total_prescribed'))
        ->groupBy('prescriptions.medication');

    // Apply filters
    if ($request->filled('health_facility')) {
        $query->where('users.health_facility', $request->health_facility);
    }

    if ($request->filled('month')) {
        $query->whereIn(DB::raw('MONTH(prescriptions.refill_date)'), $request->month);
    }

    if ($request->filled('year')) {
        $query->whereYear('prescriptions.refill_date', $request->year);
    }

    // Apply search functionality
    if ($request->filled('search')) {
        $query->where('prescriptions.medication', 'like', '%' . $request->search . '%');
    }

    // Execute query
    $prescriptions = $query->get();

    // Return view with data
    return view('admin.prescription_list', compact('prescriptions', 'healthFacilities', 'years'));
}


public function resetPassword($id)
{
    // Fetch the user by ID
    $user = User::findOrFail($id);

    // Reset the password to 'defaultpassword'
    $user->password = Hash::make('defaultpassword');
    $user->save();

    // Redirect back with a success message
    return redirect()->route('admin.account-list')->with('success', 'Password has been reset to the default password.');
}




}
