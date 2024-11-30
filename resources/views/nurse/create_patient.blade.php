@extends('layouts.nurse-sidebar')

@section('title', 'Create Patient Record')

@section('content')

<h2 style="margin-left:50px;">Add Patient</h2>
    <div class="container mt-4">
        <form id="create-patient-form" method="POST" action="{{ route('nurse.savePatient') }}" class="custom-form-margin">
            @csrf
            <div class="mb-4">
                <label for="first_name" class="form-label custom-label">
                    <span class="required-asterisk">*</span> First Name:
                </label>
                <input type="text" class="form-control custom-input" id="first_name" name="first_name" placeholder="First Name" required>
            </div>
            <div class="mb-4">
                <label for="middle_name" class="form-label custom-label">
                    <span class="required-asterisk">*</span> Middle Name:
                </label>
                <input type="text" class="form-control custom-input" id="middle_name" name="middle_name" placeholder="Middle Name">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="no_middle_name" name="no_middle_name" value="1">
                    <label class="form-check-label custom-checkbox-label" for="no_middle_name">
                        Check if no middle name
                    </label>
                </div>
            </div>
            <div class="mb-4">
                <label for="last_name" class="form-label custom-label">
                    <span class="required-asterisk">*</span> Last Name:
                </label>
                <input type="text" class="form-control custom-input" id="last_name" name="last_name" placeholder="Last Name" required>
            </div>
            <div class="mb-4">
                <label for="birthday" class="form-label custom-label">
                    <span class="required-asterisk">*</span> Birthday:
                </label>
                <input type="date" class="form-control custom-input" id="birthday" name="birthday" required>
            </div>
            <div class="mb-4">
                <label for="sex" class="form-label custom-label">
                    <span class="required-asterisk">*</span> Sex:
                </label>
                <select id="sex" name="sex" class="form-select custom-input">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="mhp_no" class="form-label custom-label">
                    <span class="required-asterisk">*</span> MHP No:
                </label>
                <input type="text" class="form-control custom-input" id="mhp_no" name="mhp_no" placeholder="MHP No" required>
            </div>
            <div class="mb-4">
                <label for="mhp_exp" class="form-label custom-label">
                    <span class="required-asterisk">*</span> MHP Exp:
                </label>
                <input type="date" class="form-control custom-input" id="mhp_exp" name="mhp_exp" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}" required>
            </div>
            <div class="mb-4">
                <label for="address" class="form-label custom-label">
                    <span class="required-asterisk">*</span> Address:
                </label>
                <input type="text" class="form-control custom-input" id="address" name="address" placeholder="Address" required>
            </div>
            <div class="mb-4">
                <label for="barangay" class="form-label custom-label">
                    <span class="required-asterisk">*</span> Barangay:
                </label>
                <select id="barangay" name="barangay" class="form-select custom-input">
                    <option value="">Select Barangay</option>
                    <option value="Bel-Air">Bel-Air</option>
                    <option value="Carmona">Carmona</option>
                    <option value="Cembo">Cembo</option>
                    <option value="Comembo">Comembo</option>
                    <option value="East Rembo">East Rembo</option>
                    <option value="Forbes Park">Forbes Park</option>
                    <option value="Guadalupe Nuevo">Guadalupe Nuevo</option>
                    <option value="Guadalupe Viejo">Guadalupe Viejo</option>
                    <option value="Kasilawan">Kasilawan</option>
                    <option value="La Paz">La Paz</option>
                    <option value="Magallanes">Magallanes</option>
                    <option value="Makati Poblacion">Makati Poblacion</option>
                    <option value="Pitogo">Pitogo</option>
                    <option value="Rizal">Rizal</option>
                    <option value="San Antonio">San Antonio</option>
                    <option value="San Isidro">San Isidro</option>
                    <option value="San Lorenzo">San Lorenzo</option>
                    <option value="San Manuel">San Manuel</option>
                    <option value="San Miguel">San Miguel</option>
                    <option value="South Cembo">South Cembo</option>
                    <option value="West Rembo">West Rembo</option>
                    <option value="Urdaneta">Urdaneta</option>
                </select>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="outside_makati" name="outside_makati"  value="1">
                    <label class="form-check-label custom-checkbox-label" for="outside_makati">
                        Check if outside Makati
                    </label>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <button type="submit" class="btn btn-primary" style="font-weight: bold; margin-right: 30px; border: 1px solid #ADADAD;  width:100px; border-radius: 5px; margin-top:20px; margin-right:100px; margin-left:30px;">SUBMIT</button>
                <button type="button" class="btn btn-secondary" id="cancel-button" style="font-weight: bold; border: 1px solid #ADADAD;  width:100px; border-radius: 5px; margin-top:20px;">CANCEL</button>
            </div>
        </form>
    </div>

<!-- Popup Modal -->
<div id="cancel-popup" class="modal">
    <div class="modal-content">
        <p style="font-weight: bold;">Are you sure you want to cancel?</p>
        <div class="modal-buttons">
            <button id="confirm-cancel" class="btn btn-primary" style="font-weight: bold; background-color: #112F7B; color: white; margin-right: 10px; border: 1px solid #ADADAD; width: 100px; height:25px; border-radius: 5px;">Confirm</button>
            <button id="cancel-popup-close" class="btn btn-secondary" style="font-weight: bold; background-color: #112F7B; color: white; border: 1px solid #ADADAD;  width: 100px; height:25px; border-radius: 5px;">Cancel</button>
        </div>
    </div>
</div>



    <!-- JavaScript to handle the checkbox functionality and clear button -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const noMiddleNameCheckbox = document.getElementById('no_middle_name');
            const middleNameInput = document.getElementById('middle_name');
            const outsideMakatiCheckbox = document.getElementById('outside_makati');
            const barangaySelect = document.getElementById('barangay');
            const clearButton = document.getElementById('clear-button');
            const form = document.getElementById('create-patient-form');

            // Function to update the state of the middle name input
            function updateMiddleNameState() {
                if (noMiddleNameCheckbox.checked) {
                    middleNameInput.disabled = true;
                    middleNameInput.value = ''; // Clear the input field when disabled
                    middleNameInput.style.backgroundColor = '#f0f0f0'; // Light gray background
                } else {
                    middleNameInput.disabled = false;
                    middleNameInput.style.backgroundColor = ''; // Reset background color
                }
            }

            // Function to update the state of the barangay dropdown
            function updateBarangayState() {
                if (outsideMakatiCheckbox.checked) {
                    barangaySelect.value = ''; // Clear the dropdown selection
                    barangaySelect.disabled = true; // Disable the dropdown
                    barangaySelect.style.backgroundColor = '#f0f0f0'; // Light gray background
                } else {
                    barangaySelect.disabled = false; // Enable the dropdown
                    barangaySelect.style.backgroundColor = ''; // Reset background color
                }
            }

            // Function to clear all form fields
            function clearForm() {
                form.reset(); // Reset form fields
                updateMiddleNameState(); // Ensure the state of the middle name field is correct
                updateBarangayState(); // Ensure the state of the barangay dropdown is correct
            }

            // Set initial state
            updateMiddleNameState();
            updateBarangayState();

            // Add event listener to the middle name checkbox
            noMiddleNameCheckbox.addEventListener('change', updateMiddleNameState);

            // Add event listener to the outside Makati checkbox
            outsideMakatiCheckbox.addEventListener('change', updateBarangayState);

            // Add event listener to the clear button
            clearButton.addEventListener('click', clearForm);
        });

        document.addEventListener('DOMContentLoaded', function() {
    const cancelButton = document.getElementById('cancel-button');
    const cancelPopup = document.getElementById('cancel-popup');
    const cancelPopupClose = document.getElementById('cancel-popup-close');
    const confirmCancel = document.getElementById('confirm-cancel');

    // Function to show the popup
    function showPopup() {
        cancelPopup.style.display = 'flex';
    }

    // Function to hide the popup
    function hidePopup() {
        cancelPopup.style.display = 'none';
    }

    // Event listener for the cancel button to show the popup
    cancelButton.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default form reset behavior
        showPopup();
    });

    // Event listener for the cancel button in the popup
    cancelPopupClose.addEventListener('click', hidePopup);

    // Event listener for the confirm button in the popup
    confirmCancel.addEventListener('click', function() {
        hidePopup();
        // Redirect to nurse dashboard after confirmation
        window.location.href = '{{ route("nurse.dashboard") }}';
    });
});


    </script>
@endsection
