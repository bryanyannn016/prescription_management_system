@extends('layouts.nurse-sidebar')

@section('title', 'Nurse Patient List')

@section('content')

<h2>Find Patient</h2>

<form action="{{ route('nurse.findPatientRecord') }}" method="GET">
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

<div class="container mt-1">
    @if($records->isEmpty())
        <!-- Display this message if there are no records -->
        <p class="text-center mt-4" style="font-weight: bold; color: #286187; margin-top:30px;">No records found.</p>
    @else
        <!-- Display the table if there are records -->
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
                    <th class="text-nowrap service-column">Service</th>
                    <th class="text-nowrap">Status</th>
                    <th class="text-nowrap">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records->sortByDesc('created_at') as $record)
                    <tr>
                        <!-- Patient information -->
                        <td>{{ $record->patient->last_name }}</td>
                        <td>{{ $record->patient->first_name }}</td>
                        <td>{{ $record->patient->middle_name }}</td>
                        <td>{{ $record->patient->patient_number }}</td>
                        <td>{{ $record->patient->sex }}</td>
                        <td>{{ $record->patient->age }}</td>
                        <td>{{ $record->patient->barangay }}</td>
                        
                        <!-- Record-specific information -->
                        <td class="service-column">{{ $record->service }}</td>
                        <td>{{ $record->status }}</td>
                        
                        <!-- Action button -->
                        <td>
                            @if($record->status == 'Approved')
                                @if($record->service == 'Refill')
                                    <!-- Form for PRINT button -->
                                    <form action="{{ route('nurse.printRecord', $record->id) }}" method="GET" target="_blank">
                                        <button type="submit" class="btn btn-secondary" style="border-color: #C6E0FF; background-color: #C6E0FF; color: #000; font-weight: bold;">
                                            PRINT
                                        </button>
                                    </form>
                                @elseif($record->service == 'Medical Consultation (Face to Face)')
                                    <!-- Check if the record_id exists in the prescriptions table -->
                                    @php
                                        $prescription = \App\Models\Prescription::where('record_id', $record->id)->first();
                                    @endphp
                                    @if($prescription)
                                        <!-- Form for PRINT button -->
                                        <form action="{{ route('nurse.printRecord', $record->id) }}" method="GET" target="_blank">
                                            <button type="submit" class="btn btn-secondary" style="border-color: #C6E0FF; background-color: #C6E0FF; color: #000; font-weight: bold;">
                                                PRINT
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            @endif
                        </td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<style>
    /* Add CSS for wider Service column */
    .service-column {
        min-width: 150px; /* Adjust the width as needed */
    }
</style>

@endsection
