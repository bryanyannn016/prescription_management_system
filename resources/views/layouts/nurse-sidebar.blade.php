<!DOCTYPE html>
<html>
<head>
    <title>@yield('title') - Nurse</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap"> <!-- Include Inter font -->
    <!-- Add this in the head section of your layout file -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<!-- Add these in the head of your layout or blade file -->
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>


<style>
    body {
        font-family: 'Inter', Arial, sans-serif;
        margin: 0;
        padding: 0;
        height: 100vh;
        overflow: hidden;
    }

    .container {
        display: flex;
        height: 100%;
        
    }

    .sidebar {
        width: 180px;
        background-color: #2974E5;
        padding: 15px;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        overflow-y: auto;
        color: #fff;
        display: flex;
        flex-direction: column;
    }

    .sidebar img.logo {
        width: 90px;
        height: auto;
        margin: 20px auto;
        display: block;
    }

    .sidebar img.line {
        width: 180px;
        height: auto;
        margin-bottom: 10px;
    }

    .sidebar .admin-section {
        margin-top: 2px;
        margin-bottom: 50px;
        display: flex;
        align-items: flex-start;
        margin-left: 10px;
    }

    .sidebar .admin-section a {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        text-decoration: none;
        color: #fff;
        margin-left: 10px;
    }

    .sidebar .admin-section img {
        width: 40px;
        height: auto;
    }

    .sidebar .admin-section .admin-text {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 2px;
    }

    .sidebar .admin-section .logout-text {
        font-size: 12px;
    }

    .sidebar h2 {
        margin-top: 0;
        color: #fff;
    }

    .sidebar ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        font-weight: bold;
    }

    .sidebar ul li {
        
        display: flex;
        align-items: center;
        justify-content: flex-start;
        width: 100%;
    }

    .sidebar ul li a {
        text-decoration: none;
        color: #fff;
        display: flex;
        align-items: center;
        font-size: 15px;
        text-align: left;
        padding: 10px;
        border-radius: 5px;
        width: 100%;
        box-sizing: border-box;
        transition: background-color 0.3s, color 0.3s;
    }

    .sidebar ul li a:hover, .sidebar ul li a:focus {
        background-color: #09377B;
        color: #fff;
    }

    .sidebar ul li a.active {
        background-color: #09377B;
        color: #fff;
    }

    .sidebar ul li img {
        width: 22px;
        height: auto;
        margin-right: 8px;
    }

    .main-content {
        flex: 1;
        padding: 15px;
        overflow-y: auto;
    }

    .logout-button {
        display: inline;
        padding: 10px 15px;
        color: #fff;
        background-color: #007bff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .logout-button:hover {
        background-color: #0056b3;
    }

.patient-table {
    border-collapse: collapse; /* Ensures borders are collapsed to prevent extra spacing */
    width: 90%; /* Make sure table takes full width */
    table-layout: fixed; /* Fixes the layout of the table */
    height: 20%;
    margin-top: 20px;
}

.patient-table th, .patient-table td {
    padding: 4px; /* Reduced padding for smaller cell height */
    border: 1px solid #dee2e6; /* Ensure consistent borders */
    text-align: center; /* Center text horizontally */
    vertical-align: middle; /* Center text vertically */
}

.patient-table th {
    background-color: #C6E0FF; /* Light background for headers */
    font-weight: bold;
}

.patient-table td {
    vertical-align: middle; /* Ensure vertical alignment in cells */
}

/* Set a specific height for table rows */
.patient-table tr {
    height: 40px; /* Adjust height as needed */
}

/* Full-page overlay when pop-up is active */
.overlay {
    display: none; /* Initially hidden */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
    z-index: 9998;
}

/* Floating pop-up container */
.popup-container {
    display: none; /* Initially hidden */
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    padding: 30px;
    border-radius: 10px;
    z-index: 9999;
    text-align: center;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
}

/* Pop-up content with bold text */
.popup-container p {
    font-weight: bold; /* Make text bold */
    color: #333;
}

/* Pop-up buttons */
.popup-container button {
    margin: 10px;
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
}

/* Linear gradient for Confirm button */
.popup-container .confirm-btn {
    background: #112F7B;
    color: white;
    border: none;
}

/* Linear gradient for Cancel button */
.popup-container .cancel-btn {
    background: #112F7B;
    color: white;
    border: none;
}


.custom-form-margin {
    margin-left: 40px; /* Adjust as needed */
    margin-top: 20px; /* Adjust as needed */
}

.custom-label {
    font-weight: 600; /* Semi-bold */
    color: #286187; /* Font color */
    margin-left: 10px; /* Margin left */
    margin-right: 10px; /* Margin right for spacing between label and input */
    display: inline-block; /* Ensures labels and inputs are in the same line */
    width: 150px; /* Fixed width for alignment */
    vertical-align: top; /* Aligns labels to the top */
}

.custom-input {
    border-radius: 0.5rem; /* Slightly rounded corners */
    height: 28px; /* Consistent height */
    border: 1px solid #ADADAD; /* Border color */
    width: 300px; /* Fixed width */
    box-sizing: border-box; /* Includes padding in width calculation */
    margin-bottom: 0.5rem; /* Margin for spacing between inputs */
}

.mb-4 {
    //margin-bottom: 0.4rem; /* Bottom margin for spacing */
}

.form-group {
    display: flex;
    align-items: center; /* Aligns labels and inputs vertically */
}
.required-asterisk {
    color: #FF0000; /* Red color for the asterisk */
    margin-right: 5px; /* Space between the asterisk and label text */
}

.form-check-input {
    margin-right: 5px; /* Space between checkbox and label */
    margin-left: 200px;
}

.form-check-label {
    color: #286187; /* Optional: Match label color */
}

.custom-checkbox-label {
    font-size: 0.7rem; /* Smaller font size */
    font-style: italic;  /* Italic text */
    color: #286187; /* Optional: Match label color */

}

/* Popup Styles */
.modal {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4); /* Black with opacity */
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 60%; /* Adjust width */
    max-width: 300px; /* Reduced width */
    text-align: center;
}

.modal-buttons {
    margin-top: 20px;
}

.admit-patient-container {
    display: flex;
    flex-direction: column;
}

.admit-patient-field {
    display: flex;
    flex-direction: row;
    margin-bottom: 0.6rem; /* Reduced spacing between fields */
}

.admit-patient-label {
    width: 200px; /* Adjust width as needed */
    font-weight: bold;
    color: #286187;
    margin-right: 0; /* Reduced space between label and value */
}

.admit-patient-value {
    margin: 0; /* Remove default margin for better alignment */
}

.admit-patient-select {
    margin: 0;
    padding: 0.25rem 0.5rem; /* Reduced padding for a smaller height */
    font-size: 0.8rem; /* Adjust font size to make the dropdown appear smaller */
    line-height: 1.2; /* Adjust line height for better vertical alignment */
    color: #111111;
    background-color: #ffffff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    width: auto; /* Adjust width as needed */
    height: auto; /* Ensure height is controlled by padding and font size */
}





</style>

</head>
<body>
    <div class="container">
        <div class="sidebar">
            <!-- Logo -->
            <img src="{{ asset('makati-logo.png') }}" alt="Makati Logo" class="logo">

            <!-- Line Image -->
            <img src="{{ asset('line.png') }}" alt="Line Image" class="line">

            <!-- Admin Section -->
            <div class="admin-section">
                <img src="{{ asset('nurse-logout.png') }}" alt="Admin Logout Icon">
                <a href="{{ route('logout') }}" class="logout-link">
                    <span class="admin-text">Nurse</span>
                    <span class="logout-text">Logout</span>
                </a>
            </div>

            <ul>
                <li style="margin-bottom: 10px;">
                    <a href="{{ route('nurse.dashboard') }}" 
                       class="{{ request()->routeIs('nurse.dashboard', 'nurse.createPatient', 'nurse.savePatient', 'nurse.admitPatient', 'nurse.findPatient', 'nurse.selectPatient', 'nurse.viewPatientRecord', 'nurse.viewExistingPatientRecord') ? 'active' : '' }}">
                        <img src="{{ asset('admitting.png') }}" alt="Admitting Icon">
                        Admitting
                    </a>
                </li>
                
                <li style="margin-bottom: 10px;">
                    <a href="{{ route('nurse.prescription_list') }}" class="{{ request()->routeIs('nurse.prescription_list', 'nurse.deferPatient', 'nurse.refillPatient', 'nurse.find_refillpatient') ? 'active' : '' }}">
                        <img src="{{ asset('prescription.png') }}" alt="Prescription Icon">
                        Prescription 
                    </a>
                </li>

                <li style="margin-bottom: 170px;">
                    <a href="{{ route('nurse.patient_list') }}" class="{{ request()->routeIs('nurse.patient_list', 'nurse.findPatientRecord') ? 'active' : '' }}">
                        <img src="{{ asset('patient-list.png') }}" alt="Patient List Icon">
                        Patient List
                    </a>
                </li>

                <li>
                    <a href="{{ route('nurse.account_settings') }}" class="{{ request()->routeIs('nurse.account_settings') ? 'active' : '' }}">
                        <img src="{{ asset('settings.png') }}" alt="Account Settings Icon">
                        Account Settings
                    </a>
                </li>
                
                <!-- Add more sidebar links here -->
            </ul>
        </div>
        <div class="main-content">
            @yield('content')
        </div>
    </div>

    
</body>


</html>
