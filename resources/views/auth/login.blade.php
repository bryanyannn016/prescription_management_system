<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    
    <!-- Link to Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Apply Inter font globally and reset margin/padding */
        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }
    
        /* Navbar styling */
        .navbar {
            background-color: #2974E5;
            padding: 15px; /* Increased padding for a taller navbar */
            color: white;
            display: flex;
            justify-content: center; /* Center elements horizontally */
            align-items: center; /* Center elements vertically */
            width: 100%; /* Full width */
            height: 80px; /* Added height */
        }
    
        .navbar img {
            height: 90px; /* Increased image height */
            margin-right: 30px; /* Increased margin-right for more spacing */
        }
    
        .navbar h1 {
            font-size: 24px;
            margin: 0;
        }
    
        /* Container for form and image */
        .content-container {
            display: flex;
            margin-top: 40px; /* Space below the navbar */
            padding: 20px;
        }
    
        /* Styling for the image on the left */
        .content-container img {
            height: 400px;
            margin-right: 100px; /* Space between image and form */
            margin-left: 200px;
        }
    
        /* Form container styling */
        .form-container {
            display: flex;
            flex-direction: column; /* Ensure elements stack vertically */
            align-items: flex-start; /* Align items to the start */
        }
    
        .form-container .form-group {
            margin-bottom: 15px; /* Space between form groups */
            width: 100%; /* Full width of the form container */
        }
    
        .form-container input {
            padding: 10px;
            width: 300px; /* Set width for input fields */
            font-size: 14px; /* Slightly smaller font size */
        }
    
        .form-container button {
            padding: 10px;
            background-color: #112F7B; /* Changed button color */
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            width: 120px; /* Adjusted width to fit both buttons */
            margin-right: 70px; /* Space between buttons */
            margin-top: 30px;
        }
    
        .form-container button:hover {
            background-color: #0d1a44; /* Slightly darker shade on hover */
        }
    
        .clear-button {
            background-color: #d9534f; /* Red color for the clear button */
        }
    
        .clear-button:hover {
            background-color: #c9302c; /* Slightly darker shade on hover */
        }
    
        /* Modal Styling */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.4); /* Black background with opacity */
            display: flex; /* Centering modal horizontally and vertically */
            justify-content: center;
            align-items: center;
        }
    
        .modal-content {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #888;
            border-radius: 5px;
            width: 80%; /* Can be adjusted based on preference */
            max-width: 700px; /* Restrict maximum width */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Optional shadow for better visibility */
        }
    
        .modal-header {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-size: 18px;
            text-align: center; /* Center text horizontally */
            display: flex;
            flex-direction: column;
            justify-content: center; /* Center content vertically */
            align-items: center; /* Center content horizontally */
        }
    
        .modal-header h2 {
            margin-bottom: 0; /* Remove bottom margin */
        }
    
        .modal-body {
            padding: 10px 0;
        }
    
        .modal-footer {
            padding: 10px;
            border-top: 1px solid #ddd;
            text-align: center; /* Center content in the footer */
        }
    
        .modal-footer button {
            padding: 10px;
            background-color: #112F7B; /* Button color */
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin: 0 auto; /* Center button */
        }
    
        .modal-footer button:hover {
            background-color: #0d1a44; /* Slightly darker shade on hover */
        }
    </style>
    
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <img src="{{ asset('/makati-logo.png') }}" alt="Makati Logo">
        <h1>MAKATI PRESCRIPTION MANAGEMENT SYSTEM</h1>
    </div>

    <!-- Content with image and form -->
    <div class="content-container">
        <!-- Image on the left -->
        <img src="{{ asset('/login-display.png') }}" alt="Login Display Image">
        
        <!-- Login Form on the right -->
        <div class="form-container">
            <form method="POST" action="{{ url('login') }}">
                @csrf

                <!-- Displaying error message if any -->
                @if ($errors->any())
                    <div class="form-group" style="color: red;">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <button type="submit">Login</button>
                    <button type="button" class="clear-button" onclick="clearForm()">Clear</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for Privacy Notice -->
    <div id="privacyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>JUST IN TIME PRIVACY NOTICE</h2>
                <h2>MAKATI HEALTH DEPARTMENT</h2>
                <h2>MAKATI PRESCRIPTION MANAGEMENT SYSTEM</h2>
            </div>
            <div class="modal-body">
                <p>The City Government of Makati (Makati Health Department) values the privacy and security of your personal data which shall be used to facilitate your Health Center Prescription. Rest assured that all personal and sensitive information that you may provide is kept secure and confidential in compliance with Republic Act No. 10173 otherwise known as the Data Privacy Act of 2012.</p>
                <p>To know more details on how we use, store, protect and handle your personal data, please read our Privacy Notice.</p>
                <label>
                    <input type="checkbox" id="privacyAgree" required>
                    I have read, understood, and agree to the processing of my personal data in accordance with the Privacy Notice.
                </label>
            </div>
            <div class="modal-footer">
                <button onclick="confirmPrivacy()">Confirm</button>
            </div>
        </div>
    </div>

    <!-- JavaScript for Modal -->
    <script>
        // Function to clear form inputs
        function clearForm() {
            document.querySelector('input[name="email"]').value = '';
            document.querySelector('input[name="password"]').value = '';
        }

        // Function to show the modal
        function showModal() {
            document.getElementById('privacyModal').style.display = 'flex';
        }

        // Function to hide the modal
        function confirmPrivacy() {
            if (document.getElementById('privacyAgree').checked) {
                document.getElementById('privacyModal').style.display = 'none';
            } else {
                alert('You must agree to the Privacy Notice to proceed.');
            }
        }

        // Show the modal on page load
        window.onload = showModal;
    </script>

</body>
</html>
