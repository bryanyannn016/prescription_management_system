@extends('layouts.admin-sidebar')

@section('title', 'Create Account')

@section('content')
    <div class="container mt-4">
        <form id="create-account-form" method="POST" action="{{ url('admin/create-account') }}" class="custom-form-margin">
            @csrf
            <div class="mb-4">
                <label for="first_name" class="form-label custom-label">
                    <span class="required-asterisk">*</span> First Name:
                </label>
                <input type="text" class="form-control custom-input" id="first_name" name="first_name" placeholder="First Name" required>
            </div>
            <div class="mb-4">
                <label for="middle_name" class="form-label custom-label">
                    <span class="required-asterisk">*</span> Middle Name:
                </label>
                <input type="text" class="form-control custom-input" id="middle_name" name="middle_name" placeholder="Middle Name">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="no_middle_name" name="no_middle_name">
                    <label class="form-check-label custom-checkbox-label" for="no_middle_name">
                        Check if no middle name
                    </label>
                </div>
            </div>
            <div class="mb-4">
                <label for="last_name" class="form-label custom-label">
                    <span class="required-asterisk">*</span> Last Name:
                </label>
                <input type="text" class="form-control custom-input" id="last_name" name="last_name" placeholder="Last Name" required>
            </div>
            <div class="mb-4">
                <label for="email" class="form-label custom-label">
                    <span class="required-asterisk">*</span> Email:
                </label>
                <input type="email" class="form-control custom-input" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="mb-4">
                <label for="type" class="form-label custom-label">
                    <span class="required-asterisk">*</span> Type:
                </label>
                <select id="type" name="type" class="form-select custom-input">
                    <option value="doctor">Doctor</option>
                    <option value="nurse">Nurse</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="license_no" class="form-label custom-label">
                    <span class="required-asterisk">*</span> License No.:
                </label>
                <input type="text" class="form-control custom-input" id="license_no" name="license_no" placeholder="License Number" required>
            </div>
            <div class="mb-4">
                <label for="health_facility" class="form-label custom-label">
                    <span class="required-asterisk">*</span> Health Facility:
                </label>
                <select id="health_facility" name="health_facility" class="form-select custom-input" required>
                    <option value="bangkal_health_center">Bangkal Health Center</option>
                    <option value="bangkal_lying_in">Bangkal Lying-In</option>
                    <option value="carmona">Carmona</option>
                    <option value="kasilawan">Kasilawan</option>
                    <option value="la_paz">La Paz</option>
                    <option value="olympia">Olympia</option>
                    <option value="palanan">Palanan</option>
                    <option value="pio_del_pilar_pc">Pio Del Pilar PC</option>
                    <option value="pio_del_pilar_rhu">Pio Del Pilar RHU</option>
                    <option value="poblacion">Poblacion</option>
                    <option value="san_antonio">San Antonio</option>
                    <option value="san_isidro">San Isidro</option>
                    <option value="santa_cruz">Santa Cruz</option>
                    <option value="singkamas">Singkamas</option>
                    <option value="tejeros">Tejeros</option>
                    <option value="guadalupe_nuevo">Guadalupe Nuevo</option>
                    <option value="guadalupe_nuevo_lying_in">Guadalupe Nuevo Lying-In</option>
                    <option value="guadalupe_viejo">Guadalupe Viejo</option>
                    <option value="pinagkaisahan">Pinagkaisahan</option>
                </select>
            </div>            
            <div class="d-flex align-items-center" style="margin-left:200px; margin-top:50px;">
                <button type="submit" class="btn btn-primary" style="margin-right:100px; background-color: #C6E0FF; border-color: #C6E0FF; color: #000; font-weight: bold">SUBMIT</button>
                <button type="button" class="btn btn-secondary" id="clear-button" style="background-color: #C6E0FF; border-color: #C6E0FF; color: #000; font-weight: bold">CLEAR</button>
            </div>
        </form>
    </div>

    <!-- JavaScript to handle the checkbox functionality and clear button -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('no_middle_name');
            const middleNameInput = document.getElementById('middle_name');
            const clearButton = document.getElementById('clear-button');
            const form = document.getElementById('create-account-form');

            // Function to update the state of the middle name input
            function updateMiddleNameState() {
                if (checkbox.checked) {
                    middleNameInput.disabled = true;
                    middleNameInput.value = ''; // Clear the input field when disabled
                    middleNameInput.style.backgroundColor = '#f0f0f0'; // Light gray background
                } else {
                    middleNameInput.disabled = false;
                    middleNameInput.style.backgroundColor = ''; // Reset background color
                }
            }

            // Function to clear all form fields
            function clearForm() {
                form.reset(); // Reset form fields
                updateMiddleNameState(); // Ensure the state of the middle name field is correct
            }

            // Set initial state
            updateMiddleNameState();

            // Add event listener to the checkbox
            checkbox.addEventListener('change', updateMiddleNameState);

            // Add event listener to the clear button
            clearButton.addEventListener('click', clearForm);
        });
    </script>
@endsection
