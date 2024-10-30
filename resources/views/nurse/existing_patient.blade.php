@extends('layouts.nurse-sidebar')

@section('content')
    <h2>Admit Patient</h2>

    @if(session('selected_patient'))
    @php
        $patient = session('selected_patient');
    @endphp
    <form action="{{ route('nurse.admitexisting') }}" method="POST" enctype="multipart/form-data">
        @csrf <!-- Include CSRF token for security -->
        
        <div class="admit-patient-field">
            <label for="full_name" class="admit-patient-label">
                Full Name:
            </label>
            <p id="full_name" class="admit-patient-value">
                {{ $patient['full_name'] }}
            </p>
        </div>

        <div class="admit-patient-field">
            <label for="sex" class="admit-patient-label">
                Sex:
            </label>
            <p id="sex" class="admit-patient-value">{{ $patient['sex'] }}</p>
        </div>

        <div class="admit-patient-field">
            <label for="birthday" class="admit-patient-label">
                Birthday:
            </label>
            <p id="birthday" class="admit-patient-value">{{ $patient['birthday'] }}</p>
        </div>

        <div class="admit-patient-field">
            <label for="patient_number" class="admit-patient-label">
                Patient Number:
            </label>
            <p id="patient_number" class="admit-patient-value">{{ $patient['patient_number'] }}</p>
        </div>

        <div class="admit-patient-field">
            <label for="barangay" class="admit-patient-label">
                Barangay:
            </label>
            <p id="barangay" class="admit-patient-value">{{ $patient['barangay'] }}</p>
        </div>

        <div class="admit-patient-field">
            <label for="service" class="admit-patient-label">
                Service:
            </label>
            <select id="service" name="service" class="admit-patient-select" required>
                <option value="Medical Consultation (Face to Face)">Medical Consultation (Face to Face)</option>
                <option value="Refill">Refill</option>
            </select>
        </div>

        <!-- File Upload Field -->
        <div class="admit-patient-field">
            <label for="attachments" class="admit-patient-label">
                Attach Files:
            </label>
            <input type="file" id="attachments" name="attachments[]" class="admit-patient-file" multiple>
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
@else
    <p>No patient selected. Please select a patient before proceeding with the admission.</p>
    <a href="{{ route('nurse.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
@endif

<script>
    document.getElementById('cancel-btn').addEventListener('click', function () {
        window.location.href = "{{ route('nurse.dashboard') }}";
    });
</script>

@endsection