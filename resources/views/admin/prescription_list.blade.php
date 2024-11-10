@extends('layouts.admin-sidebar')
@section('title', 'Prescription List')

<style>
    .prescription-table-wrapper {
        width: 75%;
        max-height: 450px; /* Adjust this value based on the height you want for the table */
        overflow-y: auto;
        -ms-overflow-style: none;  /* For Internet Explorer */
        scrollbar-width: thin;     /* For Firefox */
    }

    .prescription-table-wrapper::-webkit-scrollbar {
        width: 5px; /* Set width of the scrollbar */
    }

    .prescription-table-wrapper::-webkit-scrollbar-thumb {
        background-color: transparent; /* Make scrollbar thumb transparent */
    }

    .prescription-table {
        border-collapse: collapse;
        width: 100%;
        table-layout: fixed;
        margin-top: 30px;
    }

    .prescription-table th, .prescription-table td {
        padding: 4px;
        border: 1px solid #ddd;
        text-align: center;
        vertical-align: middle;
    }

    .prescription-table th {
        background-color: #C6E0FF;
        font-weight: bold;
    }

    .prescription-table td {
        vertical-align: middle;
    }

    .prescription-table tr {
        height: 40px;
    }

    .prescription-table tr:hover {
        background-color: #f1f8ff;
    }

    .prescription-table .action-buttons {
        display: flex;
        justify-content: center;
        gap: 10px;
    }
</style>

@section('content')
<div class="custom-form-margin">
    <h2 style="color: #286187;">Prescription List</h2>

    <!-- Filter and Search Section -->
    <form method="GET" action="{{ route('admin.prescription-list') }}" class="mb-4">
        <div class="form-group">
            <select id="health_facility" name="health_facility" class="filter-input" style="height:32px; width:200px; margin-right:10px;">
                <option value="">Select Health Facility</option>
                @foreach($healthFacilities as $facility)
                    <option value="{{ $facility }}" {{ request('health_facility') == $facility ? 'selected' : '' }}>{{ $facility }}</option>
                @endforeach
            </select>

            <select name="month[]" id="month" class="filter-input" style="width:300px;" multiple>
                @foreach(range(1, 12) as $month)
                    <option value="{{ $month }}" {{ in_array($month, request('month', [])) ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($month)->format('F') }}</option>
                @endforeach
            </select>
            
            <select name="year" id="year" class="filter-input" style="width:200px; height:32px; margin-left:10px; margin-right:10px;">
                <option value="">Select Year</option>
                @foreach($years as $year)
                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>

            <input type="text" name="search" id="search" class="filter-input" placeholder="Search by medication" style="height:32px; margin-right:15px;" value="{{ request('search') }}">

            <!-- Download CSV Icon -->
            <button type="button" class="btn btn-success" id="downloadCSV" style="background: none; border: none; margin-right:10px;">
                <img src="{{ asset('csv.png') }}" alt="Download CSV" style="width: 24px; height: 24px; vertical-align: middle;">
            </button>

            <button type="submit" class="btn btn-primary" style="background-color: #C6E0FF; border-color: #C6E0FF; color: #000; font-weight: bold">Apply Filters</button>
        </div>
    </form>

    <!-- Prescription Table with Vertical Scroll -->
    <div class="prescription-table-wrapper">
        <table class="prescription-table" id="prescriptionTable">
            <thead>
                <tr>
                    <th>Medication</th>
                    <th>Total Quantity Prescribed</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prescriptions as $prescription)
                    <tr>
                        <td>{{ $prescription->medication }}</td>
                        <td>{{ $prescription->total_prescribed }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">No prescriptions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Apply Select2 to the month dropdown for multiple selection
        $('#month').select2({
            placeholder: "Select Months",
            allowClear: true,
            multiple: true
        });

        // CSV download function
        $('#downloadCSV').click(function() {
            downloadCSV();
        });
    });

    function downloadCSV() {
        // Get table data
        const table = document.getElementById('prescriptionTable');
        let csv = [];
        
        // Iterate over rows
        for (let row of table.rows) {
            let rowData = [];
            // Iterate over columns
            for (let cell of row.cells) {
                rowData.push(cell.innerText);
            }
            csv.push(rowData.join(','));
        }

        // Create a Blob and download the CSV
        const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
        const downloadLink = document.createElement('a');
        downloadLink.href = URL.createObjectURL(csvFile);
        downloadLink.download = 'prescription_list.csv';
        
        // Append link and trigger download
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }
</script>
@endsection
