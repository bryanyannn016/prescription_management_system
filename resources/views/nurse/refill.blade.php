@extends('layouts.nurse-sidebar') 
@section('content')

<style>
    .label-color {
        color: #286187;
    }
    .table-header {
        background-color: #C6E0FF;
        color: #000;
        text-align: center;
        vertical-align: middle;
    }
    .table-data, .table-header {
        text-align: center;
        vertical-align: middle;
        border: 1px solid #dee2e6;
    }
    .table td input[type="number"] {
        width: 80px;
        text-align: center;
        margin: auto;
    }
    .custom-margin-right {
        margin-right: 20px;
    }

    .table .action-column {
        width: 90px; /* Adjust the width as needed */
    }
</style>

<div class="container">
    <div class="row mb-3">
        <div class="col-md-6 custom-margin-right">
            <p><strong class="label-color">Name:</strong> {{ $patient->first_name }} {{ $patient->middle_name }} {{ $patient->last_name }}</p>
        </div>
        <div class="col-md-6">
            <p><strong class="label-color">Age/Sex:</strong> {{ $patient->age }}/{{ $patient->sex }}</p>
        </div>
        <div class="col-md-6">
            <p><strong class="label-color">Patient Number:</strong> {{ $patient->patient_number }}</p>
        </div>

        <!-- Refillable Medications Table -->
    <div class="mt-4" style="margin-top:50px;">
        <h4 class="label-color">Refillable Medications</h4>
        <table class="table table-bordered mt-2" id="refillable-medications-table">
            <thead>
                <tr>
                    <th class="table-header">Medication</th>
                    <th class="table-header">Quantity</th>
                    <th class="table-header">Sig</th>
                    <th class="table-header">Date Started</th>
                    <th class="table-header">Last Prescribed</th>
                    <th class="table-header action-column">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prescriptions as $prescription)
                    <tr>
                        <td class="table-data">{{ $prescription->medication }}</td>
                        <td class="table-data">
                            <input type="number" class="form-control" min="1" step="1" value="{{ $prescription->quantity }}" 
                                   required oninput="this.value = this.value.replace(/[^0-9]/g, '');" />
                        </td>                        
                        <td class="table-data">{{ $prescription->sig }}</td>
                        <td class="table-data">{{ $prescription->date_started }}</td>
                        <td class="table-data">{{ $prescription->last_prescribed }}</td>
                        <td class="table-data">
                            <button class="btn btn-primary btn-sm add-btn"
                                    onclick="addMedication(this, '{{ $prescription->medication }}', '{{ $prescription->sig }}')" style="background-color: #C6E0FF; border-color: #C6E0FF; color: #000; font-weight: bold">
                                ADD
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="table-data" colspan="6">No refillable medications found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Added Medications Table -->
    <div class="mt-4">
        <h4 class="label-color">Added Medications</h4>
        <table class="table table-bordered mt-2" id="added-medications-table">
            <thead>
                <tr>
                    <th class="table-header">Medication</th>
                    <th class="table-header">Quantity</th>
                    <th class="table-header">Sig</th>
                    <th class="table-header action-column">Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Rows will be added here dynamically -->
            </tbody>
        </table>

        <!-- Refill Date Input -->
        <div class="mt-3" style="margin-top:50px;">
            <label class="label-color" for="refill-date" style="font-weight: bold">Refill Date:</label>
            <input type="date" id="refill-date" class="form-control" min="" />
        </div>

        <!-- Submit Button -->
        <div class="mt-4" style="margin-top:80px; margin-left:400px;">
            <button class="btn btn-success" onclick="submitRefill()" style="background-color: #C6E0FF; border-color: #C6E0FF; color: #000; margin-right:75px; font-weight: bold">SUBMIT</button>
            <button class="btn btn-danger" onclick="window.location.href='{{ route('nurse.prescription_list') }}'" style="background-color: #C6E0FF; border-color: #C6E0FF; color: #000; margin-right: 10px; font-weight: bold">BACK</button>
        </div>
    </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <p><strong class="label-color">Barangay:</strong> {{ $patient->barangay }}</p>
        </div>
        <div class="col-md-6">
            <p><strong class="label-color">Service:</strong> {{ $record->service }}</p>        
        </div>
    </div>
</div>  

<script>
// Set minimum date to tomorrow for refill date input
document.addEventListener("DOMContentLoaded", function() {
    let today = new Date();
    today.setDate(today.getDate() + 1); // Set to tomorrow
    let dd = String(today.getDate()).padStart(2, '0');
    let mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
    let yyyy = today.getFullYear();
    today = yyyy + '-' + mm + '-' + dd; // Format: YYYY-MM-DD
    document.getElementById('refill-date').min = today; // Set the min attribute
});

// Function to add medication to the Added Medications table
// Function to add medication to the Added Medications table
function addMedication(button, medication, sig) {
    // Get the quantity from the input field in the same row
    let quantityInput = button.parentNode.parentNode.querySelector('input[type="number"]');
    let quantity = quantityInput ? quantityInput.value : '';

    // Get the date_started from the current row
    let dateStarted = button.parentNode.parentNode.cells[3].innerText; // Access the 4th cell directly

    // Check if medication is already added
    let addedTable = document.getElementById('added-medications-table').getElementsByTagName('tbody')[0];
    let rows = addedTable.getElementsByTagName('tr');
    for (let row of rows) {
        if (row.getAttribute('data-medication') === medication) {
            alert("This medication is already added.");
            return;
        }
    }

    // Add a new row to the Added Medications table
    let newRow = addedTable.insertRow();
    newRow.setAttribute('data-medication', medication);
    newRow.innerHTML = `
        <td class="table-data">${medication}</td>
        <td class="table-data">${quantity}</td>
        <td class="table-data">${sig}</td>
        <td class="table-data"><button class="btn btn-danger btn-sm" onclick="removeMedication(this)" style="background-color: #C6E0FF; border-color: #C6E0FF; color: #000; font-weight: bold">REMOVE</button></td>
    `;

    // Store the date_started as a data attribute in the row
    newRow.setAttribute('data-date-started', dateStarted); // Correctly set the date started value
}


// Function to remove medication from the Added Medications table
function removeMedication(button) {
    let row = button.parentNode.parentNode;
    row.parentNode.removeChild(row);
}

// Function to submit refill data
// Function to submit refill data
function submitRefill() {
    const addedMedications = [];
    const addedTable = document.getElementById('added-medications-table').getElementsByTagName('tbody')[0];
    const rows = addedTable.getElementsByTagName('tr');
    const refillDate = document.getElementById('refill-date').value;

    for (let row of rows) {
        const medication = row.getElementsByTagName('td')[0].innerText;
        const quantity = row.getElementsByTagName('td')[1].innerText;
        const sig = row.getElementsByTagName('td')[2].innerText;
        const dateStarted = row.getAttribute('data-date-started'); // Get the stored date_started

        addedMedications.push({ medication, quantity, sig, date_started: dateStarted });
    }

    // Make an AJAX request to store the refill data
    fetch('/nurse/refill', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for security
        },
        body: JSON.stringify({ medications: addedMedications, refill_date: refillDate, record_id: '{{ $record->id }}' })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json(); // Parse the JSON response
    })
    .then(data => {
        // Check if the submission was successful
        if (data.success) {
            // Redirect to the prescription list page
            window.location.href = '{{ route("nurse.prescription_list") }}';
        } else {
            // Handle error response (if any)
            alert('Error: ' + data.message || 'An unknown error occurred.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('There was a problem with the request. Please try again later.');
    });
}



</script>

@endsection
