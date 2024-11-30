@extends('layouts.doctor-sidebar')

@section('content')

<style>
    .label-color {
        color: #286187; /* Updated label color */
    }
    .table-header {
        background-color: #C6E0FF; /* Set the background color for the table header */
        color: #000; /* Set the text color to black for better contrast */
    }
    .remove-btn {
    color: red; /* Text color */
    cursor: pointer; /* Change cursor to pointer for better UX */
    background-color: transparent; /* Background transparent */
    border: none; /* Remove border */
    font-weight: bold; /* Optional: Bold text */
    
}

    .refill-date-container {
        display: none; /* Initially hide the refill date input */
        margin-top: 20px; /* Add some margin for spacing */
    }
    .table-header {
        background-color: #C6E0FF; /* Set the background color for the table header */
        color: #000; /* Set the text color to black for better contrast */
        text-align: center; /* Center-align table headers */
        vertical-align: middle; /* Vertically center-align table headers */
    }
    .table-data {
        text-align: center; /* Center-align table data */
        vertical-align: middle; /* Vertically center-align table contents */
        border: 1px solid #dee2e6; /* Add border to table data */
    }
   
</style>

<div class="container">
    <div class="row">
        <!-- First Stack (Left Column): Name, Age/Sex, Patient Number -->
        <div class="col-md-6" style="margin-right: 300px;">
            <p><strong class="label-color">Name:</strong> {{ $patientDetails['first_name'] }} {{ $patientDetails['middle_name'] }} {{ $patientDetails['last_name'] }}</p>
            <p><strong class="label-color">Age/Sex:</strong> {{ $patientDetails['age'] }} / {{ $patientDetails['sex'] }}</p>
            <p><strong class="label-color" style="margin-bottom:100px;">Patient Number:</strong> {{ $patientDetails['patient_number'] }}</p>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <h4 style="margin-top:50px;" class="label-color">Medication</h4>

                <!-- Medication Selection -->
                <div class="form-group">
                    <label for="medication" class="text-color">Choose Medication:</label>
                    <select id="medication" style="width:500px;" class="form-control" required>
                        <option value="">Select Medication</option>
                        @foreach($medications as $medication)
                        <option value="{{ $medication->id }}" 
                            data-refillable="{{ $medication->isRefillable }}">{{ $medication->medication }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Quantity Input -->
                <div class="form-group" style="margin-top:10px;">
                    <label for="quantity" class="text-color" style="margin-left:85px;">Quantity:</label>
                    <input type="number" id="quantity" class="form-control" min="1" placeholder="Enter quantity" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>

                <!-- Sig Input -->
                <div class="form-group" style="margin-top:10px;">
                    <label for="sig" class="text-color" style="margin-left:125px;">Sig:</label>
                    <input type="text" id="sig" class="form-control" placeholder="Enter Sig instructions" required>
                </div>

                <!-- Add Button -->
                <button class="btn btn-primary mt-3" style="margin-top:10px; margin-left:200px; background-color: #C6E0FF; border:#C6E0FF; font-weight: bold; color: #000; width:90px;  border-radius: 0.3rem;" id="add-medication-btn">ADD</button>

                <!-- Prescription Table -->
                <h4 class="label-color mt-4">Prescription</h4>
                <table class="table table-bordered" id="prescription-table">
                    <thead>
                        <tr>
                            <th class="table-header">Medication</th>
                            <th class="table-header">Quantity</th>
                            <th class="table-header">Sig</th>
                            <th class="table-header">Refillable</th>
                            <th class="table-header">Action</th>
                        </tr>
                    </thead>
                    <tbody id="prescription-list">
                        <!-- Medications will be appended here -->
                    </tbody>
                </table>

                <!-- Refill Date Input -->
                <div class="refill-date-container" id="refill-date-container">
                    <label for="refill-date" class="text-color">Refill Date:</label>
                    <input type="date" id="refill-date" class="form-control" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"  placeholder="Select refill date">
                </div>

                <!-- Buttons for Submit and Back -->
                <div class="mt-4" style="margin-top: 50px; margin-left:400px;">
                    <button class="btn btn-success" id="submit-prescription-btn" style="margin-right: 30px; background-color: #C6E0FF; border:#C6E0FF; font-weight: bold; color: #000; width:90px;  border-radius: 0.3rem;">SUBMIT</button>
                    <button class="btn btn-secondary" id="cancel-btn" style="font-weight: bold; color: #000; width:90px;  border-radius: 0.3rem;">CANCEL</button>
                </div>
                <input type="hidden" id="patient_id" value="{{ $patientDetails['patient_number'] }}">
                <input type="hidden" id="record_id" value="{{ $record->id }}">


            </div>
        </div>
    </div>
    <div class="col-md-6">
        <p><strong class="label-color">Barangay:</strong> {{ $patientDetails['barangay'] }}</p>
        <p><strong class="label-color">Service:</strong> {{ $patientDetails['service'] }}</p>
        <p><strong class="label-color">Date:</strong> {{ $patientDetails['date'] }}</p>
    </div>
    
</div>

<script>
    document.getElementById('add-medication-btn').addEventListener('click', function() {
    const medicationSelect = document.getElementById('medication');
    const quantityInput = document.getElementById('quantity');
    const sigInput = document.getElementById('sig');

    const medicationId = medicationSelect.value;
    const medicationText = medicationSelect.options[medicationSelect.selectedIndex].text;
    const refillable = medicationSelect.options[medicationSelect.selectedIndex].dataset.refillable;
    const quantity = quantityInput.value;
    const sig = sigInput.value;

    if (!medicationId || !quantity || !sig) {
        alert('Please fill in all fields.');
        return;
    }

    // Create a new row for the prescription table
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td class="table-data" style="width:300px;">${medicationText}</td>
        <td class="table-data">${quantity}</td>
        <td class="table-data">${sig}</td>
        <td class="table-data">${refillable === '1' ? 'Yes' : 'No'}</td>
        <td class="table-data"><button class="remove-btn" onclick="removeMedication(this)">Remove</button></td>
    `;

    // Append the new row to the prescription list
    document.getElementById('prescription-list').appendChild(newRow);

    // Check if the medication is refillable and toggle the refill date input
    toggleRefillDateInput(refillable === '1');

    // Clear the inputs after adding
    medicationSelect.value = '';
    quantityInput.value = '';
    sigInput.value = '';
});


    // Function to remove a medication row
    function removeMedication(button) {
        const row = button.closest('tr');
        if (row) {
            const refillableCell = row.cells[3].innerText; // Check the refillable status
            row.remove();
            // Check if any refillable medications are left and toggle the refill date input
            const hasRefillable = Array.from(document.querySelectorAll('#prescription-list tr')).some(row => row.cells[3].innerText === 'Yes');
            toggleRefillDateInput(hasRefillable);
        }
    }

      // Function to check if there is at least one refillable medication and toggle the refill date input
      function toggleRefillDateInput() {
        const rows = Array.from(document.querySelectorAll('#prescription-list tr'));
        const hasRefillable = rows.some(row => row.cells[3].innerText === 'Yes'); // Check if any row has 'Yes' for refillable

        const refillDateContainer = document.getElementById('refill-date-container');
        refillDateContainer.style.display = hasRefillable ? 'block' : 'none'; // Show or hide based on the condition
    }



// Ensure the diagnoses are correctly passed to JavaScript
const diagnoses = @json($diagnoses); // Log the diagnoses on page load
console.log('Diagnoses:', diagnoses); // Log the diagnoses to verify it's captured

// Add event listener for the submit button
document.getElementById('submit-prescription-btn').addEventListener('click', function() {
    const prescriptionList = [];
    const prescriptionRows = document.querySelectorAll('#prescription-list tr');

    prescriptionRows.forEach(row => {
        const medication = row.cells[0].innerText; // Medication name
        const quantity = row.cells[1].innerText; // Quantity
        const sig = row.cells[2].innerText; // Sig
        const isRefillable = row.cells[3].innerText === 'Yes'; // Refillable status
        const refillDate = document.getElementById('refill-date').value; // Refill date

        prescriptionList.push({
            medication,
            quantity,
            sig,
            isRefillable,
            refillDate: isRefillable ? refillDate : null // Handle nullable refill date
        });
    });

    // Log the prescription list to the console
    console.log('Prescription List:', prescriptionList);

    // Log the diagnoses in the submit event to check if it is available
    console.log('Diagnoses during submit:', diagnoses); // Log the diagnoses again during submission

    // Get patient_id and record_id from hidden fields or directly
    const patientId = "{{ $patientDetails['patient_number'] }}"; // Replace as necessary
    const recordId = "{{ $record->id }}"; // Make sure this is correctly set in your view

    // Log patientId and recordId to verify their values
    console.log('Patient ID:', patientId); // Log Patient ID
    console.log('Record ID:', recordId); // Log Record ID

    // Send the data to the server
    fetch('{{ route("doctor.storePrescription") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token
        },
        body: JSON.stringify({
            diagnoses: diagnoses, // Correctly set diagnoses from the PHP variable
            prescriptions: prescriptionList,
            patient_id: patientId,
            record_id: recordId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = "{{ route('doctor.dashboard') }}"; // Redirect on success
        } else {
            alert(data.message); // Show error message
        }
    })
    .catch(error => console.error('Error:', error));
});



    document.getElementById('cancel-btn').addEventListener('click', function() {
    window.location.href = "{{ route('doctor.dashboard') }}";
});

</script>

@endsection
