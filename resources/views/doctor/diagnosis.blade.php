@extends('layouts.doctor-sidebar')

@section('title', 'Diagnosis Page')

<style>
    .label-color {
        color: #286187; /* Updated label color */
    }
    .diagnosis-table {
        margin-right: 50px; /* Adjust the margin as needed */
    }
    .file-table {
        margin-top: 10px; /* Add margin for the file table */
        margin-right: 50px;
        /* Adjust the margin as needed */
    }
    .table-header {
        background-color: #C6E0FF; /* Set the background color for the table header */
        color: #000; /* Optional: Set the text color to black for better contrast */
    }
    .remove-btn {
    color: red; /* Text color */
    cursor: pointer; /* Change cursor to pointer for better UX */
    background-color: transparent; /* Background transparent */
    border: none; /* Remove border */
    font-weight: bold; /* Optional: Bold text */
}

    .ongoing-checkbox {
        margin-right: 10px;
    }

    .table-header, .table-data {
        text-align: center; /* Center-align table headers and data */
        vertical-align: middle; /* Vertically center-align table contents */
        border: 1px solid #dee2e6;
    }

</style>

@section('content')

@if(isset($patient) && isset($record))
    <div class="container">
        <div class="row">
            <!-- First Stack (Left Column): Name, Age/Sex, Patient Number -->
            <div class="col-md-6" style="margin-right: 300px;">
                <p><strong class="label-color">Name:</strong> {{ $patient->first_name }} {{ $patient->middle_name }} {{ $patient->last_name }}</p>
                <p><strong class="label-color">Age/Sex:</strong> {{ $patient->age }} / {{ $patient->sex }}</p>
                <p><strong class="label-color" style="margin-bottom:100px;">Patient Number:</strong> {{ $patient->patient_number }}</p>
            </div>

            <div style="margin-top: 75px;">
                <h4><strong style="color: #286187;">Add Diagnosis</strong></h4>
                <form id="diagnosis-form">
                    @csrf
                    <div class="form-row align-items-center">
                        <div class="col-md-10">
                            <input name="diagnosis" id="diagnosis" class="form-control" style="height:40px; width:300px;" placeholder="Enter diagnosis..."></input>
                            <button type="button" class="btn btn-primary mt-2" id="add-diagnosis-btn" style="background-color: #C6E0FF; border:#C6E0FF; font-weight: bold; color: #000; width:90px;">ADD</button>
                        </div>
                    </div>
                </form>
            </div>

            <table class="table table-bordered diagnosis-table"> <!-- Added class here -->
                <thead>
                    <tr>
                        <th class="table-header" style="width: 200px;">Diagnosis</th>
                        <th class="table-header" style="width: 200px;">Ongoing</th>
                        <th class="table-header" style="width: 200px;">Action</th>
                    </tr>
                </thead>
                <tbody id="diagnosis-list">
                    <!-- Diagnoses will be appended here -->
                </tbody>
            </table>

            <input type="hidden" name="patient_id" value="{{ $patient->id }}">
            <input type="hidden" name="record_id" value="{{ $record->id }}">
            <button type="button" class="btn btn-success mt-3" id="next-btn" style="margin-top:200px; margin-left:300px; margin-right:50px; background-color: #C6E0FF; border:#C6E0FF; font-weight: bold; color: #000; width:90px;">NEXT</button> <!-- Added Next Button -->
            <button type="button" class="btn btn-danger mt-3" id="cancel-btn" style="font-weight: bold; color: #000; width:90px;"onclick="window.location.href='{{ route('doctor.dashboard') }}'">CANCEL</button>
        </div>

        <div class="col-md-6">
            <p><strong class="label-color">Barangay:</strong> {{ $patient->barangay }}</p>
            <p><strong class="label-color">Service:</strong> {{ $record->service }}</p>
            <p><strong class="label-color">Date:</strong> {{ \Carbon\Carbon::parse($record->date)->format('F j, Y') }}</p>

            <!-- New Table for Files -->
            <h4 style="margin-top: 80px; color: #286187;"><strong>Attached Files</strong></h4>
            <table class="table table-bordered file-table">
                <thead>
                    <tr>
                        <th class="table-header" style="width: 200px;">Filename</th>
                        <th class="table-header" style="width: 100px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($files as $file)
                        <tr>
                            <td class="table-data">{{ basename($file->file_path) }}</td> <!-- Show just the filename -->
                            <td class="table-data">
                                <a href="{{ route('files.view', $file->id) }}" class="btn btn-link btn-sm">
                                    <img src="{{ asset('view.png') }}" alt="View" style="width: 25px; height: 25px;"/>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const diagnosisList = [];

        document.getElementById('add-diagnosis-btn').addEventListener('click', function() {
            const diagnosisInput = document.getElementById('diagnosis');
            const diagnosisText = diagnosisInput.value;

            if (diagnosisText.trim() === '') {
                alert('Please enter a diagnosis.');
                return;
            }

            // Create a new row for the diagnosis
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td class="table-data">${diagnosisText}</td>
                <td class="table-data">
                    <input type="checkbox" class="ongoing-checkbox" checked>
                </td>
                            <td class="table-data">
                <button type="button" class="btn remove-btn" onclick="removeDiagnosis(this)">Remove</button>
            </td>
            `;

            // Append the new row to the diagnosis list
            document.getElementById('diagnosis-list').appendChild(newRow);

            // Store the diagnosis in the array
            diagnosisList.push({ diagnosis: diagnosisText, ongoing: true });

            // Clear the textarea after adding
            diagnosisInput.value = '';
        });

        // Function to remove a diagnosis row
        function removeDiagnosis(button) {
            // Remove the row that contains the button
            const row = button.closest('tr');
            const diagnosisText = row.cells[0].innerText;
            const index = diagnosisList.findIndex(item => item.diagnosis === diagnosisText);
            if (index !== -1) {
                diagnosisList.splice(index, 1); // Remove from the array
            }
            if (row) {
                row.remove();
            }
        }

        document.getElementById('next-btn').addEventListener('click', function() {
    const diagnoses = [];

    // Loop through the diagnosis list and gather data
    const rows = document.querySelectorAll('#diagnosis-list tr');
    rows.forEach(row => {
        const diagnosisText = row.cells[0].innerText;
        const ongoing = row.querySelector('.ongoing-checkbox').checked;

        diagnoses.push({ diagnosis: diagnosisText, ongoing: ongoing });
    });

    // Gather patient ID and record ID from hidden inputs
    const patientId = document.querySelector('input[name="patient_id"]').value;
    const recordId = document.querySelector('input[name="record_id"]').value;

    // Make an AJAX call to store the diagnosis in session and redirect to prescription
    fetch('{{ route("store.diagnosis") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ 
            diagnoses,
            patient_id: patientId,
            record_id: recordId
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // Redirect to the prescription view with the patient and record ID
        window.location.href = data.redirectUrl; // Redirect URL sent from the server
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing your request. Please try again.');
    });
});


    </script>
@else
    <p>No patient or record found.</p>
@endif

@endsection
