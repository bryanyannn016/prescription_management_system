@extends('layouts.nurse-sidebar')

@section('title', 'Nurse Dashboard')

@section('content')

<h2>Find Patient</h2>

<form action="{{ route('nurse.findPatient') }}" method="GET">
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="lastName" class="form-label" style="font-weight: bold; margin-right: 115px; color: #286187;">Last Name</label>
                            <label for="firstName" class="form-label" style="font-weight: bold; margin-right: 115px; color: #286187;">First Name</label>
                            <label for="dob" class="form-label" style="font-weight: bold; margin-right: 40px; color: #286187;">Date of Birth</label>
                            <label for="middleName" class="form-label" style="font-weight: bold; color: #286187;">Middle Name</label>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" id="lastName" name="lastName" class="form-control mb-2" style="margin-right: 20px; height:22px;" placeholder="Enter Last Name">
                            <input type="text" id="firstName" name="firstName" class="form-control mb-2" style="margin-right: 20px; height:22px;" placeholder="Enter First Name">
                            <input type="date" id="dob" name="dob" class="form-control mb-2" style="margin-right: 20px; height:22px;">
                            <input type="text" id="middleName" name="middleName" class="form-control mb-2" style="margin-right: 30px; height:22px;" placeholder="Enter Middle Name">
                            <button type="submit" class="btn btn-primary" style="font-weight: bold; height:30px;">FIND</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hidden input to indicate a search was made -->
    <input type="hidden" name="searched" value="true">
</form>

@if(isset($patients) && $patients->count() > 0)
    <div class="container mt-1">
        <table class="table table-bordered patient-table">
            <thead>
                <tr>
                    <th class="text-nowrap">Last Name</th>
                    <th class="text-nowrap">First Name</th>
                    <th class="text-nowrap">Middle Name</th>
                    <th class="text-nowrap">Patient Number</th>
                    <th class="text-nowrap">Sex</th>
                    <th class="text-nowrap">Age</th>
                    <th class="text-nowrap">Record</th>
                    <th class="text-nowrap">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patients as $patient)
                    <tr>
                        <td>{{ $patient->last_name }}</td>
                        <td>{{ $patient->first_name }}</td>
                        <td>{{ $patient->middle_name }}</td>
                        <td>{{ $patient->patient_number }}</td>
                        <td>{{ $patient->sex }}</td>
                        <td>{{ $patient->age }}</td>
                        <td>
                            <form action="{{ route('nurse.viewPatientRecord', $patient->id) }}" method="GET">
                                <button type="submit" class="btn" style="border-color: #C6E0FF; background-color: #C6E0FF; color: #000;"><strong>VIEW</strong></button>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('nurse.selectPatient') }}" method="GET">
                                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                <button type="submit" class="btn" style="border-color: #C6E0FF; background-color: #C6E0FF; color: #000;"><strong>SELECT</strong></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@elseif(isset($patients) && $patients->count() === 0 && request()->input('searched'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var popup = document.getElementById('noRecordsPopup');
            var overlay = document.getElementById('overlay');

            // Show the pop-up and overlay if no records are found and a search was performed
            popup.style.display = 'block';
            overlay.style.display = 'block';
        });

        // Function to close the pop-up and hide the overlay
        function closePopup() {
            var popup = document.getElementById('noRecordsPopup');
            var overlay = document.getElementById('overlay');
            popup.style.display = 'none';
            overlay.style.display = 'none';
            window.location.href = "{{ route('nurse.dashboard') }}";
        }

        // Function to confirm and route to the patient creation page
        function confirmPopup() {
            window.location.href = "{{ route('nurse.createPatient') }}";
        }
    </script>
@endif

<!-- Overlay and Pop-up -->
<div class="overlay" id="overlay" style="display: none;"></div>
<div class="popup-container" id="noRecordsPopup" style="display: none;">
    <p>No records found!</p>
    <p>Would you like to add new patient?</p>
    <button class="confirm-btn" onclick="confirmPopup()">CONFIRM</button>
    <button class="cancel-btn" onclick="closePopup()">CANCEL</button>
</div>

<script>
 
</script>

@endsection
