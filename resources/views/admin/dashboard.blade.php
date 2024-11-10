@extends('layouts.admin-sidebar')

@section('title', 'Admin Dashboard')

<style>
    .text-center{
        color: #286187;
    }
</style>

@section('content')

<!-- Admin Dashboard Content -->
<div class="container mt-5">
    <!-- Filters for Top 10 Morbidity -->
    <div style="margin-right:100px; margin-left:50px;">
        <div>
            <h3 class="text-center">Top 10 Morbidity</h3>

        <div class="d-flex justify-content-center mb-4">
            <form method="GET" action="{{ route('admin.reports') }}">
                <select name="month_diagnosis" class="form-select" style="width: 150px; display: inline-block;">
                    <option value="" disabled {{ !request('month') ? 'selected' : '' }}>Month</option>
                    <option value="all" {{ request('month') == 'all' ? 'selected' : '' }}>All</option>
                    @foreach ($months as $month)
                        <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                        </option>
                    @endforeach
                </select>
                
                <select name="year_diagnosis" class="form-select" style="width: 150px; display: inline-block;">
                    <option value="" disabled {{ !request('year') ? 'selected' : '' }}>Year</option>
                    <option value="all" {{ request('year') == 'all' ? 'selected' : '' }}>All</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
                
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>

        <canvas id="morbidityChart" width="400" height="300"></canvas>
        </div>

        <div>
            <!-- Drug Utilization Per Age Group -->
    <div>
        <h3 class="text-center">Drug Utilization Per Age Group</h3>
    
        <div class="d-flex justify-content-center mb-4">
            <form method="GET" action="{{ route('admin.reports') }}">
        
                <div>
                    <select name="month_agegroup" class="form-select" style="width: 150px; display: inline-block;">
                        <option value="" disabled {{ !request('month_agegroup') ? 'selected' : '' }}>Month</option>
                        <option value="all" {{ request('month_agegroup') == 'all' ? 'selected' : '' }}>All</option>
                        @foreach ($months as $month)
                            <option value="{{ $month }}" {{ request('month_agegroup') == $month ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                            </option>
                        @endforeach
                    </select>
    
                    <select name="year_agegroup" class="form-select" style="width: 150px; display: inline-block;">
                        <option value="" disabled {{ !request('year_agegroup') ? 'selected' : '' }}>Year</option>
                        <option value="all" {{ request('year_agegroup') == 'all' ? 'selected' : '' }}>All</option>
                        @foreach ($years as $year)
                            <option value="{{ $year }}" {{ request('year_agegroup') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
    
                <select name="medication_agegroup" class="form-select" style="width: 200px; display: inline-block;">
                    <option value="" disabled {{ !request('medication_agegroup') ? 'selected' : '' }}>Select Medication</option>
                    @foreach ($medications as $index => $medication)
                        <option value="{{ $medication }}" 
                            {{ request('medication_agegroup') == $medication || ($index == 0 && !request('medication_agegroup')) ? 'selected' : '' }}>
                            {{ $medication }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
    
        <canvas id="drugUtilizationChart" width="300" height="150"></canvas>
    </div>
        </div>
        
    </div>

    <!-- Filters for Top 10 Prescriptions -->
    <div>
        <h3 class="text-center">Top 10 Medications</h3>

        <div class="d-flex justify-content-center mb-4">
            <form method="GET" action="{{ route('admin.reports') }}">
                <select name="month_prescription" class="form-select" style="width: 150px; display: inline-block;">
                    <option value="" disabled {{ !request('month_prescription') ? 'selected' : '' }}>Month</option>
                    <option value="all" {{ request('month_prescription') == 'all' ? 'selected' : '' }}>All</option>
                    @foreach ($months as $month)
                        <option value="{{ $month }}" {{ request('month_prescription') == $month ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                        </option>
                    @endforeach
                </select>
                
                <select name="year_prescription" class="form-select" style="width: 150px; display: inline-block;">
                    <option value="" disabled {{ !request('year_prescription') ? 'selected' : '' }}>Year</option>
                    <option value="all" {{ request('year_prescription') == 'all' ? 'selected' : '' }}>All</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}" {{ request('year_prescription') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
    
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
        <canvas id="prescriptionChart" width="400" height="300"></canvas>
    </div>

    
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Get the diagnosis data passed from the controller
    var diagnoses = @json($diagnoses);
    
    // Extract labels (diagnosis names) and data (counts)
    var labelsDiagnoses = diagnoses.map(function(diagnosis) {
        return diagnosis.diagnosis;
    });

    var dataDiagnoses = diagnoses.map(function(diagnosis) {
        return diagnosis.count;
    });

    // Set the bar color to #2974E5 for all diagnoses
    var barColorDiagnoses = '#2974E5';

    // Create the horizontal bar chart for diagnoses using Chart.js
    var ctxDiagnoses = document.getElementById('morbidityChart').getContext('2d');
    var morbidityChart = new Chart(ctxDiagnoses, {
        type: 'bar', // Use bar chart, with indexAxis set to 'y' for horizontal bars
        data: {
            labels: labelsDiagnoses, // Y-axis labels (diagnoses)
            datasets: [{
                label: 'Number of Diagnoses',
                data: dataDiagnoses, // X-axis data (counts)
                backgroundColor: barColorDiagnoses, // All bars will have the same color
                borderColor: barColorDiagnoses, // Border color for bars
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y', // This makes the bar chart horizontal
            plugins: {
                legend: {
                    display: false // Hide the legend
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Number of Cases'
                    },
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return Number.isInteger(value) ? value : '';
                        }
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Diagnosis'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Get the prescription data passed from the controller
var prescriptions = @json($prescriptions);

// Extract labels (medications) and data (quantities)
var labelsPrescriptions = prescriptions.map(function(prescription) {
    return prescription.medication;
});

var dataPrescriptions = prescriptions.map(function(prescription) {
    return prescription.total_quantity; // Use total_quantity instead of count
});

// Set the bar color to #2974E5 for all prescriptions
var barColorPrescriptions = '#2974E5';

// Create the horizontal bar chart for prescriptions using Chart.js
var ctxPrescriptions = document.getElementById('prescriptionChart').getContext('2d');
var prescriptionChart = new Chart(ctxPrescriptions, {
    type: 'bar', // Use bar chart, with indexAxis set to 'y' for horizontal bars
    data: {
        labels: labelsPrescriptions, // Y-axis labels (medications)
        datasets: [{
            label: 'Quantity of Prescriptions',
            data: dataPrescriptions, // X-axis data (quantities)
            backgroundColor: barColorPrescriptions, // All bars will have the same color
            borderColor: barColorPrescriptions, // Border color for bars
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        indexAxis: 'y', // This makes the bar chart horizontal
        plugins: {
            legend: {
                display: false // Hide the legend
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Quantity of Prescriptions'
                },
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    callback: function(value) {
                        return Number.isInteger(value) ? value : '';
                    }
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Medication'
                },
                grid: {
                    display: false
                }
            }
        }
    }
});


    var ctx = document.getElementById('drugUtilizationChart').getContext('2d');
var drugUtilizationChart = new Chart(ctx, {
    type: 'bar', // Use bar chart, with indexAxis set to 'y' for horizontal bars
    data: {
        labels: ['<18', '18-59', '60-69', '>70'], // Y-axis labels (age groups)
        datasets: [{
            label: 'Drug Utilization by Age Group',
            data: @json($ageGroupData), // Inject PHP data into JS
            backgroundColor: '#2974E5', // Set the bar color to match the other charts
            borderColor: '#2974E5', // Set the border color to match the bar color
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        indexAxis: 'y', // This makes the bar chart horizontal
        plugins: {
            legend: {
                display: false // Remove the legend
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Number of Prescriptions' // Title for the X-axis
                },
                beginAtZero: true,
                ticks: {
                    stepSize: 1, // Adjust step size to 1 for clarity
                    callback: function(value) {
                        return Number.isInteger(value) ? value : ''; // Ensure only integers are shown
                    }
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Age Group' // Title for the Y-axis
                },
                grid: {
                    display: false // Remove grid lines for cleaner appearance
                }
            }
        }
    }
});

</script>


@endsection
