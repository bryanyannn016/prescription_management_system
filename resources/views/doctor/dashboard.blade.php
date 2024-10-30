@extends('layouts.doctor-sidebar')

@section('title', 'Doctor Dashboard')

@section('content')

<h2>Find Patient</h2>

<form action="{{ route('doctor.findPatient') }}" method="GET">
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
                            <input type="text" id="middleName" name="middleName" class="form-control mb-2" style="margin-right: 20px; height:22px;" placeholder="Enter Middle Name">
                            <button type="submit" class="btn btn-primary" style="font-weight: bold;">FIND</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                    <th class="text-nowrap">Barangay</th>
                    <th class="text-nowrap">Service</th>
                    <th class="text-nowrap">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patients as $patient)
                    @foreach($patient->records as $record) <!-- Loop through each record for the patient -->
                        @if($record->status == 'Pending' || $record->service == 'Medical Consultation (Face to Face)')
                            <tr>
                                <td>{{ $patient->last_name }}</td>
                                <td>{{ $patient->first_name }}</td>
                                <td>{{ $patient->middle_name }}</td>
                                <td>{{ $patient->patient_number }}</td>
                                <td>{{ $patient->sex }}</td>
                                <td>{{ $patient->age }}</td>
                                <td>{{ $patient->barangay }}</td>
                                <td>{{ $record->service }}</td> <!-- Displaying Service from the record -->
                                <td>
                                    <form action="{{ route('doctor.selectPatient') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                        <input type="hidden" name="record_id" value="{{ $record->id }}"> <!-- Add the record_id -->
                                        <button type="submit" class="btn" style="border-color: #C6E0FF; background-color: #C6E0FF; color: #000;"><strong>SELECT</strong></button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <p>No patients found.</p>
@endif


@endsection
