@extends('layouts.admin-sidebar')

@section('title', 'Patient List')

@section('content')
<div class="custom-form-margin">
    <h2 style="color: #286187;">Record List</h2>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.patient-list') }}">
        <div class="form-group">
            <!-- Health Facility Dropdown -->
            <select name="health_facility" id="health_facility" class="filter-input" style="height:32px; width:200px; margin-right:10px;">
                <option value="">Select Health Facility</option>
                @foreach($healthFacilities as $facility)
                    <option value="{{ $facility }}" {{ request('health_facility') == $facility ? 'selected' : '' }}>{{ $facility }}</option>
                @endforeach
            </select>

            <!-- Month Dropdown with Select2 Multi-select -->
            <select name="month[]" id="month" class="filter-input" style="width:300px;" multiple>
                @foreach(range(1, 12) as $month)
                    <option value="{{ $month }}" {{ in_array($month, request('month', [])) ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($month)->format('F') }}</option>
                @endforeach
            </select>

            <!-- Year Dropdown -->
            <select name="year" id="year" class="filter-input" style="width:200px; height:32px; margin-left:10px; margin-right:10px;">
                <option value="">Select Year</option>
                @foreach($years as $year)
                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>

            <!-- Search Input Field -->
            <input type="text" name="search" id="search" class="filter-input" placeholder="Search by Name" value="{{ request('search') }}" style="height:25px; margin-right:15px;">

            <!-- Download CSV Icon -->
            <button type="button" class="btn btn-success" id="downloadCSV" style="background: none; border: none; margin-right:10px;">
                <img src="{{ asset('csv.png') }}" alt="Download CSV" style="width: 24px; height: 24px; vertical-align: middle;">
            </button>

            <!-- Apply Filters Button -->
            <button type="submit" class="btn btn-primary" style="background-color: #C6E0FF; border-color: #C6E0FF; color: #000; font-weight: bold">Apply Filters</button>
        </div>
    </form>

    <!-- Scrollable Table Wrapper -->
    <div class="table-container">
        <table class="account-table" id="patientTable">
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $record)
                    <tr>
                        <td>{{ $record->service }}</td>
                        <td>{{ \Carbon\Carbon::parse($record->date)->format('Y-m-d') }}</td>
                        <td>{{ $record->first_name }} {{ $record->middle_name }} {{ $record->last_name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Initialize Select2 -->
<script>
    $(document).ready(function() {
        // Apply Select2 to the month dropdown
        $('#month').select2({
            placeholder: "Select Months",
            allowClear: true
        });

        // Function to download table as CSV
        $('#downloadCSV').on('click', function() {
            var csv = [];
            var rows = document.querySelectorAll("#patientTable tr");

            // Loop through rows and extract text content
            for (var i = 0; i < rows.length; i++) {
                var row = [], cols = rows[i].querySelectorAll("td, th");
                for (var j = 0; j < cols.length; j++) {
                    row.push(cols[j].innerText);
                }
                csv.push(row.join(","));
            }

            // Create a CSV file and trigger the download
            var csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
            var link = document.createElement("a");
            link.href = URL.createObjectURL(csvFile);
            link.download = "record_list.csv";
            link.click();
        });
    });
</script>

<style>
    /* Container for table with fixed height and overflow scroll */
    .table-container {
        max-height: 450px;
        overflow-y: auto;
    }

    /* Custom scrollbar styling */
    .table-container::-webkit-scrollbar {
        width: 5px;
    }

    .table-container::-webkit-scrollbar-thumb {
        background-color: #C6E0FF; /* Light color for aesthetic */
        border-radius: 4px;
    }

    .table-container::-webkit-scrollbar-thumb:hover {
        background-color: #286187; /* Darker color on hover */
    }

    .table-container::-webkit-scrollbar-track {
        background: transparent;
    }
</style>
@endsection
