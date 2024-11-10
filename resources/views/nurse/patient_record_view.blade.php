@extends('layouts.nurse-sidebar')

@section('title', 'Patient Record Details')

@section('content')

<style>
    .label-color {
        color: #286187;
    }
    .table thead th {
        background-color: #C6E0FF;
        border: 1px solid #dee2e6;
        text-align: center;
        width: 200px;
    }
    .table tbody td {
        border: 1px solid #dee2e6;
        text-align: center;
    }
</style>

<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-6 custom-margin-right" style="margin-right:200px;">
            <p><strong class="label-color">Name:</strong> 
               {{ $patient->first_name }} {{ $patient->middle_name }} {{ $patient->last_name }}</p>
        </div>
        <div class="col-md-6 custom-margin-right">
            <p><strong class="label-color">Age/Sex:</strong> {{ $patient->age }}/{{ $patient->sex }}</p>
        </div>
        <div class="col-md-6 custom-margin-right">
            <p><strong class="label-color">Patient Number:</strong> {{ $patient->patient_number }}</p>
        </div>

        <!-- Diagnosis Table -->
        <div class="mt-4">
            <h4 style="margin-top:50px; color:#286187">Final Diagnosis</h4>
            <table class="table table-bordered" style=" border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>Diagnosis</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($diagnoses as $diagnosis)
                        <tr>
                            <td>{{ $diagnosis->diagnosis }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($diagnoses->isEmpty())
                <p>No ongoing diagnoses found for this record.</p>
            @endif
        </div>

        <!-- Prescriptions Table -->
        <div class="mt-4">
            <h4 style="margin-top:50px; color:#286187">Prescriptions</h4>
            <table class="table table-bordered" style=" border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>Medication</th>
                        <th>Quantity</th>
                        <th>SIG</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($prescriptions as $prescription)
                        <tr>
                            <td>{{ $prescription->medication }}</td>
                            <td>{{ $prescription->quantity }}</td>
                            <td>{{ $prescription->sig }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($prescriptions->isEmpty())
                <p>No prescriptions found for this record.</p>
            @endif
        </div>

        <!-- Files Table -->
        <div class="mt-4">
            <h4 style="margin-top:50px; color:#286187">Files</h4>
            <table class="table table-bordered" style=" border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($files as $file)
                        <tr>
                            <td>{{ $file->file_path }}</td>
                            <td>
                                <a href="{{ route('files.view', $file->id) }}" class="btn btn-link btn-sm">
                                    <img src="{{ asset('view.png') }}" alt="View" style="width: 25px; height: 25px;"/>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($files->isEmpty())
                <p>No files found for this record.</p>
            @endif
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6 text-end custom-margin-right">
            <p><strong class="label-color">Barangay:</strong> {{ $patient->barangay }}</p>
        </div>
        <div class="col-md-6 text-end">
            <p><strong class="label-color">Service:</strong> {{ $record->service }}</p>
        </div>
        <div class="col-md-6 text-end">
            <p><strong class="label-color">Date:</strong> {{ $record->date }}</p>
        </div>


    </div>
</div>

@endsection
