@extends('layouts.nurse-sidebar')

@section('content')
    <h2>Admit Patient</h2>
    <form action="{{ route('admit.patient.store') }}" method="POST" class="admit-patient-container" enctype="multipart/form-data">
        @csrf

        <div class="admit-patient-field">
            <label for="full_name" class="admit-patient-label">Full Name:</label>
            @php
                $patient = session('patient_data');
            @endphp
            <p id="full_name" class="admit-patient-value">
                {{ $patient['first_name'] }} {{ $patient['middle_name'] ?? '' }} {{ $patient['last_name'] }}
            </p>
        </div>

        <div class="admit-patient-field">
            <label for="sex" class="admit-patient-label">Sex:</label>
            <p id="sex" class="admit-patient-value">{{ $patient['sex'] }}</p>
        </div>

        <div class="admit-patient-field">
            <label for="birthday" class="admit-patient-label">Birthday:</label>
            <p id="birthday" class="admit-patient-value">{{ $patient['birthday'] }}</p>
        </div>

        <div class="admit-patient-field">
            <label for="barangay" class="admit-patient-label">Barangay:</label>
            <p id="barangay" class="admit-patient-value">{{ $patient['barangay'] }}</p>
        </div>

        <div class="admit-patient-field">
            <label for="service" class="admit-patient-label">Service:</label>
            <select id="service" name="service" class="admit-patient-select" required>
                <option value="Medical Consultation (Face to Face)">Medical Consultation (Face to Face)</option>
                <option value="Refill">Refill</option>
            </select>
        </div>

        <div class="admit-patient-field">
            <label for="attachments" class="admit-patient-label">Attach Files:</label>
            <input type="file" id="attachments" name="attachments[]" class="admit-patient-file" multiple>
            @error('attachments.*')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="admit-patient-actions" style="margin-top: 50px;">
            <button type="submit" class="admit-patient-submit" 
                    style="margin: 0 10px; background-color: #C6E0FF; border: #C6E0FF; 
                           font-weight: bold; color: #000; width: 90px; border-radius: 0.3rem; margin-left:150px; margin-right:100px;">
                ADMIT
            </button>

            <button type="button" id="cancel-btn" class="btn btn-secondary" 
                    style="font-weight: bold; color: #000; width: 90px; border-radius: 0.3rem;">
                CANCEL
            </button>
        </div>
    </form>

    <script>
        // Add event listener for Cancel button to prevent form submission
        document.getElementById('cancel-btn').addEventListener('click', function () {
            window.location.href = "{{ route('nurse.dashboard') }}";
        });
    </script>
@endsection
