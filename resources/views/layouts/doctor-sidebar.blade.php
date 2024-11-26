<!DOCTYPE html>
<html>
<head>
    <title>@yield('title') - Doctor</title>
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
    width: 100%; /* Make sure table takes full width */
    table-layout: fixed; /* Fixes the layout of the table */
    height: 20%;
    margin-top: 50px;
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
                <img src="{{ asset('doctor-logout.png') }}" alt="Doctor Logout Icon">
                <a href="{{ route('logout') }}" class="logout-link">
                    <span class="admin-text">Doctor</span>
                    <span class="logout-text">Logout</span>
                </a>
            </div>

            <ul>
                <li style=" margin-bottom: 10px;">
                    <a href="{{ route('doctor.dashboard') }}" class="{{ request()->routeIs('doctor.dashboard', 'doctor.findPatient', 'doctor.selectPatient', 'doctor.diagnosis', 'doctor.prescription',
                    'doctor.docRefill') ? 'active' : '' }}">
                        <img src="{{ asset('patient-list.png') }}" alt="Patient List Icon">
                        Patient List
                    </a>
                </li>
                <li style=" margin-bottom: 220px;">
                    <a href="{{ route('doctor.allPatients') }}" class="{{ request()->routeIs('doctor.allPatients', 'doctor.recordfindPatient', 'doctor.viewPatientRecord','doctor.viewExistingPatientRecord') ? 'active' : '' }}">
                        <img src="{{ asset('patient-records.png') }}" alt="Patient Records Icon">
                        Patient Records
                    </a>
                </li>

                <li>
                    <a href="{{ route('doctor.account_settings') }}" class="{{ request()->routeIs('doctor.account_settings') ? 'active' : '' }}">
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
