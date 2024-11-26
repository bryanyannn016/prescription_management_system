@extends('layouts.admin-sidebar')

@section('title', 'Edit Account')

@section('content')

<style>
    .label-color {
        color: #286187; /* Updated label color */
    }

    .custom-margin-right {
        margin-right: 10px;
    }
</style>

<div class="container mt-4">
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
                    {{ $user->type }} <!-- For any other type that may exist -->
                @endif
            </p>
        </div>

        

        <!-- Email Field (Disabled) -->
        <div class="col-md-6 custom-margin-right" style="margin-top:100px;">
            <span class="required-asterisk">*</span><label for="email" class="label-color" style="font-weight:bold;">Email:</label>
            <input type="email" name="email" id="email" value="{{ $user->email }}" class="form-control custom-input" disabled>
        </div>

        <!-- Health Facility Dropdown -->
        <div class="col-md-6 custom-margin-right" style="margin-top:10px;">
            <form action="{{ route('admin.update', ['id' => $user->id]) }}" method="POST">
                @csrf
                @method('POST')
            
                <span class="required-asterisk">*</span> <label for="health_facility" class="label-color" style="font-weight:bold;">Health Facility:</label>
                <select id="health_facility" name="health_facility" class="form-select custom-input" required>
                    <option value="Bangkal Health Center" {{ $user->health_facility == 'Bangkal Health Center' ? 'selected' : '' }}>Bangkal Health Center</option>
                    <option value="Bangkal Lying-In" {{ $user->health_facility == 'Bangkal Lying-In' ? 'selected' : '' }}>Bangkal Lying-In</option>
                    <option value="Carmona" {{ $user->health_facility == 'Carmona' ? 'selected' : '' }}>Carmona</option>
                    <option value="Kasilawan" {{ $user->health_facility == 'Kasilawan' ? 'selected' : '' }}>Kasilawan</option>
                    <option value="La Paz" {{ $user->health_facility == 'La Paz' ? 'selected' : '' }}>La Paz</option>
                    <option value="Olympia" {{ $user->health_facility == 'Olympia' ? 'selected' : '' }}>Olympia</option>
                    <option value="Palanan" {{ $user->health_facility == 'Palanan' ? 'selected' : '' }}>Palanan</option>
                    <option value="Pio Del Pilar PC" {{ $user->health_facility == 'Pio Del Pilar PC' ? 'selected' : '' }}>Pio Del Pilar PC</option>
                    <option value="Pio Del Pilar RHU" {{ $user->health_facility == 'Pio Del Pilar RHU' ? 'selected' : '' }}>Pio Del Pilar RHU</option>
                    <option value="Poblacion" {{ $user->health_facility == 'Poblacion' ? 'selected' : '' }}>Poblacion</option>
                    <option value="San Antonio" {{ $user->health_facility == 'San Antonio' ? 'selected' : '' }}>San Antonio</option>
                    <option value="San Isidro" {{ $user->health_facility == 'San Isidro' ? 'selected' : '' }}>San Isidro</option>
                    <option value="Santa Cruz" {{ $user->health_facility == 'Santa Cruz' ? 'selected' : '' }}>Santa Cruz</option>
                    <option value="Singkamas" {{ $user->health_facility == 'Singkamas' ? 'selected' : '' }}>Singkamas</option>
                    <option value="Tejeros" {{ $user->health_facility == 'Tejeros' ? 'selected' : '' }}>Tejeros</option>
                    <option value="Guadalupe Nuevo" {{ $user->health_facility == 'Guadalupe Nuevo' ? 'selected' : '' }}>Guadalupe Nuevo</option>
                    <option value="Guadalupe Nuevo Lying-In" {{ $user->health_facility == 'Guadalupe Nuevo Lying-In' ? 'selected' : '' }}>Guadalupe Nuevo Lying-In</option>
                    <option value="Guadalupe Viejo" {{ $user->health_facility == 'Guadalupe Viejo' ? 'selected' : '' }}>Guadalupe Viejo</option>
                    <option value="Pinagkaisahan" {{ $user->health_facility == 'Pinagkaisahan' ? 'selected' : '' }}>Pinagkaisahan</option>
                </select>
            
                <div style="margin-left:380px; margin-top:250px;">
                    <button type="submit" class="btn btn-primary mt-3" style="margin-right:100px; background-color: #C6E0FF; border-color: #C6E0FF; color: #000; font-weight: bold">SUBMIT</button>
                    <button type="button" class="btn btn-secondary mt-3 ml-2" onclick="window.location.href='{{ route('admin.account-list') }}'" style="background-color: #C6E0FF; border-color: #C6E0FF; color: #000; font-weight: bold">CANCEL</button>
                </div>
                <!-- Submit Button -->
            </form>     
            
    
            
           
        </div>
    </div>
    <form action="{{ route('admin.reset_password', ['id' => $user->id]) }}" method="POST" id="reset-password-form">
        @csrf
        @method('POST')
        <button type="submit" class="btn btn-warning mt-3 ml-2" 
            style="background-color: #FFC107; border-color: #FFC107; color: #000; font-weight: bold; margin-left:300px; margin-top:20px;"
            onclick="return confirm('Are you sure you want to reset the password for this user?')">
            Reset Password
        </button>
    </form>
</div>

@endsection
