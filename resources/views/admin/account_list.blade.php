@extends('layouts.admin-sidebar')

@section('title', 'Account List')

@section('content')
    <div class="container">
        <!-- Search Bar -->
        <div class="d-flex justify-content-end mb-3">
            <form method="GET" action="{{ route('admin.account-list') }}" class="d-flex">
                <input type="text" name="search" value="{{ request()->get('search') }}" class="form-control form-control-sm" placeholder="Search..." style="margin-left:800px; margin-top:20px;">
                <button type="submit" class="btn btn-primary btn-sm ml-2">Search</button>
            </form>


            <table class="table table-bordered account-table">
                <thead>
                    <tr>
                        <th class="text-nowrap">First Name</th>
                        <th class="text-nowrap">Last Name</th>
                        <th class="text-nowrap">Email</th>
                        <th class="text-nowrap">Health Facility</th>
                        <th class="text-nowrap">Status</th> <!-- Added Status column -->
                        <th class="text-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->first_name }}</td> <!-- Display first name -->
                            <td>{{ $user->last_name }}</td> <!-- Display last name -->
                            <td>{{ $user->email }}</td> <!-- Display email -->
                            <td>{{ $user->health_facility }}</td> <!-- Display health facility -->
    
                            <td>
                                <!-- Display the status (Active/Inactive) -->
                                <span class="badge {{ $user->status == 'Active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->status }}
                                </span>
                            </td> <!-- Status column with badge -->
    
                            <td class="action-column">
                                <!-- Edit Action as Button with Image -->
                                <form action="{{ route('admin.edit-account', $user->id) }}" method="GET" style="display:inline;">
                                    <button type="submit" style="background: none; border: none;">
                                        <img src="{{ asset('edit.png') }}" alt="Edit" class="btn-action" style="width: 30px; height: 30px;">
                                    </button>
                                </form>
    
                                <!-- Update Status to Inactive Action with Image -->
                                <form action="{{ route('admin.update-status', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH') <!-- Use PATCH method to update -->
                                    <button type="submit" style="background: none; border: none;">
                                        <img src="{{ asset('disable.png') }}" alt="Disable" class="btn-action" style="width: 30px; height: 30px;">
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

       
        
    </div>
@endsection
