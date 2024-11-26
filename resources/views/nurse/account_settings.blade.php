@extends('layouts.nurse-sidebar')

@section('title', 'Account Settings')

@section('content')

<style>
    .label-color {
        color: #286187;
    }

    .custom-margin-right {
        margin-right: 10px;
    }

    .required-asterisk {
        color: red;
    }
</style>


<div class="container mt-4">
    @if (session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif

    <div class="row mb-3">
        <div class="col-md-6 custom-margin-right">
            <p><strong class="label-color">Name:</strong> {{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}</p>
        </div>
        <div class="col-md-6 text-end custom-margin-right">
            <p><strong class="label-color">Type:</strong>
                @if ($user->type == 'nurse')
                    Nurse
                @elseif ($user->type == 'doctor')
                    Doctor
                @elseif ($user->type == 'admin')
                    Admin
                @else
                    {{ $user->type }}
                @endif
            </p>
        </div>

        <!-- Email Field (Disabled) -->
        <div class="col-md-6 custom-margin-right" style="margin-top: 100px;">
            <span class="required-asterisk">*</span><label for="email" class="label-color" style="font-weight:bold;">Email:</label>
            <input type="email" name="email" id="email" value="{{ $user->email }}" class="form-control custom-input" disabled>
        </div>

        <div class="row mt-4">
            <div class="col-md-6 custom-margin-right">
                <form action="{{ route('nurse.change_password') }}" method="POST">
                    @csrf
                    <div class="mb-3" style="margin-top: 10px;">
                        <span class="required-asterisk">*</span>    
                        <label for="current_password" class="label-color" style="font-weight:bold;">Current Password:</label>
                        <input type="password" name="current_password" id="current_password" class="form-control custom-input" placeholder="Enter current password" required>
                        @error('current_password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3" style="margin-top:10px;">
                        <span class="required-asterisk">*</span>   
                        <label for="new_password" class="label-color" style="font-weight:bold;">New Password:</label>
                        <input type="password" name="new_password" id="new_password" class="form-control custom-input" placeholder="Enter new password" required>
                        @error('new_password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3" style="margin-top:10px;">
                        <span class="required-asterisk">*</span>    
                        <label for="new_password_confirmation" class="label-color" style="font-weight:bold;">Confirm New Password:</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control custom-input" placeholder="Re-enter new password" required>
                    </div>
                    <button type="submit" class="btn btn-primary" style="margin-right:100px; background-color: #C6E0FF; border-color: #C6E0FF; color: #000; font-weight: bold; margin-left:350px; margin-top:50px;">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
