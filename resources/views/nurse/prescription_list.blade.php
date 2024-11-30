@extends('layouts.nurse-sidebar') 
@section('content')
<style>
    .container {
        display: flex;
        height: 100%;
        
    }
    .refillpatient-table {
    border-collapse: collapse; /* Ensures borders are collapsed to prevent extra spacing */
    width: 80%; /* Make sure table takes full width */
    table-layout: fixed; /* Fixes the layout of the table */
    height: 20%;
    margin-top: 10px;
}

.refillpatient-table th, .refillpatient-table td {
    padding: 4px; /* Reduced padding for smaller cell height */
    border: 1px solid #dee2e6; /* Ensure consistent borders */
    text-align: center; /* Center text horizontally */
    vertical-align: middle; /* Center text vertically */
}

.refillpatient-table th {
    background-color: #C6E0FF; /* Light background for headers */
    font-weight: bold;
}

.refillpatient-table td {
    vertical-align: middle; /* Ensure vertical alignment in cells */
}

/* Set a specific height for table rows */
.refillpatient-table tr {
    height: 40px; /* Adjust height as needed */
}
</style>
<h2>Find Patient</h2>

<form action="{{ route('nurse.find_refillpatient') }}" method="GET">
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
    <input type="hidden" name="searched" value="true">
</form>

{{-- Table for Admit Patients for Refill --}}
@if(isset($admitPatientDetails) && $admitPatientDetails->count() > 0)
    <h3 class="mt-4">Admitted Patients for Refill</h3>
    <div style="margin-bottom:100px;">
        <table class="table table-bordered refillpatient-table">
            <thead>
                <tr>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Patient Number</th>
                    <th>Sex</th>
                    <th>Age</th>
                    <th>Barangay</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admitPatientDetails as $patient)
                    @php
                        $record = $admitPatientRecords->firstWhere('patient_id', $patient->id);
                    @endphp
                    <tr>
                        <td>{{ $patient->last_name }}</td>
                        <td>{{ $patient->first_name }}</td>
                        <td>{{ $patient->middle_name }}</td>
                        <td>{{ $patient->patient_number }}</td>
                        <td>{{ $patient->sex }}</td>
                        <td>{{ $patient->age }}</td>
                        <td>{{ $patient->barangay }}</td>
                        <td>
                            @if($record && $record->status === 'Deferred')
                                <span class="text-muted" style="font-weight: bold">DONE</span>
                            @elseif($record && !$record->has_prescription && $record->status === 'Pending')
                                <form action="{{ route('nurse.refillPatient') }}" method="GET">
                                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                    <input type="hidden" name="record_id" value="{{ $record->id }}">
                                    <button type="submit" class="btn" style="background-color: #C6E0FF; border-color: #C6E0FF; color: #000;">
                                        <strong>SELECT</strong>
                                    </button>
                                </form>
                            @else
                                <span class="text-muted" style="font-weight: bold">DONE</span>
                            @endif
                        </td>
                        
                        
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif


{{-- Table for Refill Patients --}}
@if(isset($refillPatientDetails) && $refillPatientDetails->count() > 0)
    <h3 class="mt-5">Refill Patients</h3>
    <div class="container mt-1 mb-4">
        <table class="table table-bordered refillpatient-table">
            <thead>
                <tr>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Patient Number</th>
                    <th>Sex</th>
                    <th>Age</th>
                    <th>Barangay</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($refillPatientDetails as $patient)
                    <tr>
                        <td>{{ $patient->last_name }}</td>
                        <td>{{ $patient->first_name }}</td>
                        <td>{{ $patient->middle_name }}</td>
                        <td>{{ $patient->patient_number }}</td>
                        <td>{{ $patient->sex }}</td>
                        <td>{{ $patient->age }}</td>
                        <td>{{ $patient->barangay }}</td>
                        <td>
                            <form action="{{ route('nurse.selectPatient') }}" method="GET">
                                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                <button type="submit" class="btn" 
                                        style="background-color: #C6E0FF; border-color: #C6E0FF; color: #000;">
                                    <strong>ADMIT</strong>
                                </button>
                            </form>
                            <form action="{{ route('nurse.deferPatient') }}" method="GET">
                                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                <button type="submit" class="btn" style="background-color: #C6E0FF;border-color: #C6E0FF; color: #000;">
                                    <strong>DEFER</strong>
                                </button>
                            </form>                            
                        </td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@if((!isset($admitPatientDetails) || $admitPatientDetails->count() === 0) && 
    (!isset($refillPatientDetails) || $refillPatientDetails->count() === 0) && request()->input('searched'))
    <div class="alert alert-warning mt-3" role="alert" style="margin-top:20px;">
        No patient found.
    </div>
@endif

@endsection
