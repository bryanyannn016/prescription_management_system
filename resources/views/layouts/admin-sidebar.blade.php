<!DOCTYPE html>
<html>
<head>
    <title>@yield('title') - Admin</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap"> <!-- Include Inter font -->
    <!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<!-- jQuery (required for Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>


    <!-- Add this in the head section of your layout file -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            font-family: 'Inter', Arial, sans-serif; /* Use Inter font */
            margin: 0;
            padding: 0;
            height: 100vh; /* Full viewport height */
            overflow: hidden; /* Prevents scrollbars */
        }

        .container {
            display: flex;
            height: 100vh; /* Full viewport height */
        }

        .sidebar {
            width: 180px; /* Reduced width */
            background-color: #2974E5;
            padding: 15px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            overflow-y: auto; /* Scroll if content exceeds height */
            color: #fff; /* Text color for readability */
            display: flex;
            flex-direction: column;
        }

        .sidebar img.logo {
            width: 90px; /* Adjust the width as needed */
            height: auto;
            margin: 20px auto; /* Center horizontally with space above and below */
            display: block; /* Ensures the image is treated as a block-level element */
        }

        .sidebar img.line {
            width: 180px; /* Adjust width as needed */
            height: auto;
            margin-bottom: 10px; /* Space below the line image */
        }

        .sidebar .admin-section {
            margin-top: 2px; /* Space between the line image and admin section */
            margin-bottom: 50px; /* Space below the admin section */
            display: flex; /* Use flexbox for alignment */
            align-items: flex-start; /* Align items to the start of the flex container */
            margin-left: 10px; /* Move the section to the right */
        }

        .sidebar .admin-section a {
            display: flex; /* Use flexbox for alignment */
            flex-direction: column; /* Stack text vertically */
            align-items: flex-start; /* Align items to the left */
            text-decoration: none; /* Remove underline from links */
            color: #fff; /* White color for the text and icon */
            margin-left: 10px; /* Space between icon and text */
        }

        .sidebar .admin-section img {
            width: 40px; /* Size of the icon */
            height: auto;
        }

        .sidebar .admin-section .admin-text {
            font-size: 18px; /* Font size for the admin text */
            font-weight: bold; /* Make admin text bold */
            margin-bottom: 2px; /* Space between the admin text and logout text */
        }

        .sidebar .admin-section .logout-text {
            font-size: 12px; /* Smaller font size for logout text */
        }

        .sidebar h2 {
            margin-top: 0;
            color: #fff; /* White color for the heading */
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0; /* Remove default margin */
            width: 100%; /* Full width for the list */
            display: flex;
            flex-direction: column;
            align-items: flex-start; /* Align items to the left */
            font-weight: bold; /* Make list items bold */
        }

        .sidebar ul li {
            margin-bottom: 10px;
            display: flex;
            align-items: center; /* Align items vertically */
            justify-content: flex-start; /* Align items to the left */
            width: 100%; /* Ensure the li takes full width */
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #fff; /* White color for the links */
            display: flex;
            align-items: center; /* Align text with the icon */
            font-size: 15px; /* Increased font size */
            text-align: left; /* Align text to the left */
            padding: 10px; /* Add padding around the link */
            border-radius: 5px; /* Rounded corners */
            width: 100%; /* Make sure the link fills the entire li width */
            box-sizing: border-box; /* Include padding and border in element's total width and height */
            transition: background-color 0.3s, color 0.3s; /* Smooth transition for background color */
        }

        .sidebar ul li a:hover, .sidebar ul li a:focus {
            background-color: #09377B; /* Background color when hovering or focusing */
            color: #fff; /* Ensure text color remains white on hover */
        }

        .sidebar ul li a.active {
            background-color: #09377B; /* Background color for active state */
            color: #fff; /* Ensure text color remains white in active state */
            
        }

        .sidebar ul li img {
            width: 22px; /* Size of the icon */
            height: auto;
            margin-right: 8px; /* Space between icon and text */
            margin-left: 0; /* Remove additional space for alignment */
        }

        .main-content {
            flex: 1;
            padding: 15px;
            overflow-y: auto; /* Scroll if content exceeds height */
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
        .custom-form-margin {
    margin-left: 20px; /* Adjust as needed */
    margin-top: 40px; /* Adjust as needed */
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
    margin-bottom: 1rem; /* Margin for spacing between inputs */
}

.mb-4 {
    margin-bottom: 0.8rem; /* Bottom margin for spacing */
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

.account-table {
    border-collapse: collapse; /* Collapses borders for a seamless design */
    width: 100%; /* Ensures the table takes full width */
    table-layout: fixed; /* Ensures fixed column layout */
    height: 10%; /* Adjusts height based on content */
    margin-top: 30px; /* Adds spacing at the top */
}

.account-table th, .account-table td {
    padding: 4px; /* Provides comfortable padding for content */
    border: 1px solid #ddd; /* Light gray border for clarity */
    text-align: center; /* Centers text horizontally */
    vertical-align: middle; /* Centers text vertically */
}

.account-table th {
    background-color: #C6E0FF; /* Light background for headers */
    font-weight: bold;
}

.account-table td {
    vertical-align: middle; /* Ensure vertical alignment in cells */
}

.account-table tr {
    height: 40px; /* Adjust height as needed */
}

.account-table tr:hover {
    background-color: #f1f8ff; /* Adds a subtle hover effect */
}

.account-table .action-buttons {
    display: flex; /* Display buttons in row */
    justify-content: center; /* Center align buttons */
    gap: 10px; /* Adds space between action buttons */
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
                <img src="{{ asset('admin-logout.png') }}" alt="Admin Logout Icon">
                <a href="{{ route('logout') }}" class="logout-link">
                    <span class="admin-text">Admin</span>
                    <span class="logout-text">Logout</span>
                </a>
            </div>

            <ul>
                <li>
                    <a href="{{ route('admin.create-account') }}" class="{{ request()->routeIs('admin.create-account') ? 'active' : '' }}">
                        <img src="{{ asset('account-creation.png') }}" alt="Account Creation Icon">
                        Account Creation
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.account-list') }}" class="{{ request()->routeIs('admin.account-list', 'admin.edit-account') ? 'active' : '' }}">
                        <img src="{{ asset('account-list.png') }}" alt="Account List Icon">
                        Account List
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.reports') }}" class="{{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                        <img src="{{ asset('reports.png') }}" alt="Reports Icon">
                        Reports
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.patient-list') }}" class="{{ request()->routeIs('admin.patient-list') ? 'active' : '' }}">
                        <img src="{{ asset('patient-list.png') }}" alt="Patient List Icon">
                        Record List
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.prescription-list') }}" class="{{ request()->routeIs('admin.prescription-list') ? 'active' : '' }}">
                        <img src="{{ asset('prescription.png') }}" alt="Prescription List Icon">
                        Prescription List
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
