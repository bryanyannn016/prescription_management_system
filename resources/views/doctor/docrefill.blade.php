@extends('layouts.doctor-sidebar')

@section('title', 'Refill Prescription')

@section('content')

<style>
    .label-color {
        color: #286187;
    }
    .hidden {
        display: none;
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
        width: 50px;
        text-align: center;
        margin: auto;
    }
    .custom-margin-right {
        margin-right: 20px;
    }
    .table .action-column {
        width: 90px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .modal-container {
      z-index: 9999;
      position: fixed;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      display: none;
      align-items: center;
      justify-content: center;
    }

    .modal-open {
      display: flex;
    }

    .modal {
      max-width: 900px;
      max-height: 300px;
      background-color: #fefefe;
      border-radius: 3px;
    }

    .modal-button {
      text-transform: uppercase;
      padding: 0.5em 1em;
      border: none;
      color: #fff;
      background-color: rgba(0, 0, 0, 0.5);
      border-radius: 3px;
      margin-left: 0.5em;
    }

    .modal-confirm-button {
        background: #112F7B;
        color: white;
        border: none;
        margin-right: 50px;
    }

    .modal-header {
        background-color: #fefefe;
      display: flex;
      flex-direction: column;
    }

    .modal-header h2 {
      margin: 1em;
    }

    .modal-header span {
      padding-right: 0.3em;
      cursor: default;
      align-self: flex-end;
    }

    .modal-content {
      padding: 1em;
      flex-grow: 1;
      
    }

    .modal-footer {
      padding: 1em;
      background-color: #fefefe;
      display: flex;
      justify-content: flex-end;
      align-items: center; /* Center vertically */
    }

    .close-button {
      border: none;
      text-align: center;
      cursor: pointer;
      white-space: nowrap;
    }
</style>

<script>
    let prescriptionIdToRemove;

    function onDelete(id) {
        prescriptionIdToRemove = id; // Store the ID to remove
        document.getElementById("confirmation").classList.add("modal-open");
    }

    function onCancel() {
        document.getElementById("confirmation").classList.remove("modal-open");
    }

    function onConfirm() {
        fetch(`/doctor/prescription/remove/${prescriptionIdToRemove}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => {
            if (response.ok) {
                // Optionally, you can update the UI or show a success message here
                document.querySelector(`[data-prescription-id="${prescriptionIdToRemove}"]`).remove();
                alert('Prescription removed successfully.');
                onCancel();
            } else {
                alert('Failed to remove prescription.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        document
            .getElementById("confirmation")
            .addEventListener("click", onCancel);
        document
            .querySelector(".modal")
            .addEventListener("click", (e) => e.stopPropagation());
    });
</script>

<div class="container">
    <div class="row mb-3">
        <div class="col-md-6 mb-3">
            <p><strong class="label-color">Name:</strong> {{ $patient->first_name }} {{ $patient->middle_name }} {{ $patient->last_name }}</p>
        </div>
        <div class="col-md-6 mb-3">
            <p><strong class="label-color">Age/Sex:</strong> {{ $patient->age }}/{{ $patient->sex }}</p>
        </div>
        <div class="col-md-6 mb-3">
            <p><strong class="label-color">Patient Number:</strong> {{ $patient->patient_number }}</p>
        </div>
        <div style="margin-top:50px;">
            <h4 class="mt-4">Prescriptions</h4>
            <form id="prescriptionForm" method="POST" action="{{ route('doctor.submitRefill', $record->id) }}">
                @csrf
                <table class="table table-bordered" id="prescriptionTable">
                    <thead>
                        <tr class="table-header">
                            <th>Medication</th>
                            <th>Quantity</th>
                            <th>SIG</th>
                            <th>Refillable</th>
                            <th class="action-column">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prescriptions as $prescription)
                            <tr data-prescription-id="{{ $prescription->id }}">
                                <td>{{ $prescription->medication }}</td>
                                <td>
                                    <input type="number" name="quantity[{{ $prescription->id }}]" value="{{ $prescription->quantity }}" min="1" class="form-control" style="width: 70px;" required oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                </td>
                                <td>{{ $prescription->sig }}</td>
                                <td>
                                    <input type="checkbox" name="refillable[{{ $prescription->id }}]" {{ $prescription->refillable ? 'checked' : '' }} style="margin-left:30px;">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-prescription" id="delete" name="delete" onclick="onDelete({{ $prescription->id }})" style="margin-left:10px; background-color: #C6E0FF; border-color: #C6E0FF; color: #000; font-weight: bold;">Remove</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="form-group mt-3" style="margin-top:50px; margin-bottom:150px;">
                    <label for="refillDate" class="label-color"><strong>Refill Date:</strong></label>
                    <input type="date" id="refillDate" name="refill_date" class="form-control" 
                        value="{{ $refillDate }}" 
                        min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}" style="margin-right:20px;" required>
                    
                        <button type="button" class="btn btn-warning mt-3" onclick="window.location.href='{{ route('doctor.deferred', $record->id) }}'" style="background-color: #C6E0FF; border-color: #C6E0FF; color: #000; font-weight: bold; margin-botton:150px;">DEFER</button>

                </div>



                <!-- Buttons container -->
                <!-- Deferred Button -->
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary mt-3" style="background-color: #C6E0FF; border-color: #C6E0FF; color: #000; font-weight: bold; margin-left:300px; margin-right:200px;">SUBMIT</button>
                    <button type="button" class="btn btn-danger" onclick="window.location.href='{{ route('doctor.dashboard') }}'" style="background-color: #C6E0FF; border-color: #C6E0FF; color: #000; font-weight: bold">BACK</button>
                    <!-- New Deferred Button -->
                </div>

            </form>
            
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

<!-- Confirmation popup HTML with overlay -->
<div id="confirmation" class="modal-container">
    <div class="modal">
        <section>
            <section class="modal-content">
                <h3>Are you sure you want to remove this medication?</h3>
                <p>This action cannot be undone.</p>
            </section>
            <footer class="modal-footer">
                <div style="flex: 1; display: flex; justify-content: center;">
                    <button class="modal-button modal-confirm-button" onclick="onConfirm()" style="">Confirm</button>
                    <button class="modal-button" onclick="onCancel()" style="background: #112F7B; color: white; border: none; margin-left: 10px;">Cancel</button>
                </div>
            </footer>
        </section>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const refillDateInput = document.getElementById('refillDate');
        refillDateInput.value = '{{ $refillDate }}'; // Set the initial value from your controller
    });
</script>
@endsection

@endsection
