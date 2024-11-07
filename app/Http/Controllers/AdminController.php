<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
            'password' => \Illuminate\Support\Facades\Hash::make('defaultpassword'), // You can set a default password or generate one
            'type' => $request->input('type'),
            'health_facility' => $healthFacilityName, // Store the display name
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Account created successfully.');
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


}
