@extends('layouts.nurse-sidebar') 
@section('content')

<style>
    .label-color {
        color: #286187;
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

        <!-- Defer Refill Date Form -->
        <div style="margin-top:50px;">
            <form action="{{ route('nurse.deferRefillDate') }}" method="POST">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                <div class="row mb-3 mt-3">
                    <div class="col-md-6 custom-margin-right" style="margin-right:200px;">
                        <label for="defer_date" class="label-color" style="margin-top: 100px; margin-right:20px;">
                            <strong>Defer Refill Date to:</strong>
                        </label>
                        <input type="date" id="defer_date" name="defer_date" class="form-control" required 
                               min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary" style="font-weight: bold; margin-top:30px; margin-left:65px; margin-right:70px; background-color: #C6E0FF;border-color: #C6E0FF; color: #000;">SUBMIT</button>
                        <button type="button" class="btn btn-secondary" style="font-weight: bold; margin-top:30px; margin-left:10px; background-color: #C6E0FF;border-color: #C6E0FF; color: #000;" onclick="window.location.href='{{ route('nurse.prescription_list') }}'">CANCEL</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
       
    <div class="row mb-3">
        <div class="col-md-6 text-end custom-margin-right">
            <p><strong class="label-color" style="margin-left: 100px;">Barangay:</strong> {{ $patient->barangay }}</p>
        </div>
        <div class="col-md-6 custom-margin-right">
            <p><strong class="label-color" style="margin-left:100px;">Patient Number:</strong> {{ $patient->patient_number }}</p>
        </div>
    </div>
</div>  

@endsection
