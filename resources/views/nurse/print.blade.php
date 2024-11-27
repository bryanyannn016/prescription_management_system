<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Print Prescription</title> 
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap"> <!-- Include Inter font -->

<style>
* {
  box-sizing: border-box;
}

body {
    font-family: 'Inter', Arial, sans-serif;
}

/* Create two equal columns that float next to each other */
.column {
  float: left;
  width: 50%;
  padding: 10px;
  height: 150px; /* Should be removed. Only for demonstration */
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}

/* Style for the image */
.image-container {
  width: 50px; /* Set the width of the image */
  height: auto;
  margin-right: 25px; /* Space between the image and text */
  float: left;
  margin-top:30px;
}

/* Adjusting bottom margin of the row to move the prescriptions section up */
.row {
  margin-bottom: 20px; /* Adjust this value as needed to bring the content closer */
}

h3 {
  color: #286187; /* Prescriptions section header color */
}

/* Refill date styling */
.refill-date {
  margin-top: 150px;
}
</style>
</head>
<body>

<div class="row">
    <div class="image-container">
        <!-- Displaying the image from public directory -->
        <img src="rxlogo.png" alt="RX logo" style="width: 50px; height:50px;">
    </div>
  <!-- Column 1: Name and Address with Image -->
  <div class="column">

    <p><strong style="color: #286187;">Name:</strong> {{ $first_name }} {{ $middle_name }} {{ $last_name }}</p>
    <p><strong style="color: #286187;">Address:</strong> {{ $address }}</p>
    <p><strong style="color: #286187;">Date:</strong> {{ $record_date }}</p>

  </div>

  <!-- Column 2: Age and Sex -->
  <div class="column">
    <p><strong style="color: #286187;">Age:</strong> {{ $age }}</p>
    <p><strong style="color: #286187;">Sex:</strong> {{ $sex }}</p>
  </div>

  <img src="planetdrug.jpg" alt="RX logo" style="width: 65px; height:65px; margin-left:150px; margin-top:20px;">
  
</div>

<h3>Prescriptions:</h3>
<ol>
    @foreach($prescriptions as $index => $prescription)
        <li>
            <strong style="color: #286187;">Medication:</strong> {{ $prescription->medication }} 
            <strong style="margin-left:30px; color: #286187;">Quantity:</strong> {{ $prescription->quantity }} 
            <strong style="margin-left:30px; color: #286187;">Sig:</strong> {{ $prescription->sig }}
        </li>
    @endforeach
</ol>

<!-- Refill Date Section -->
<div class="refill-date">
  <p><strong style="color: #286187;">Refill Date:</strong> {{ $prescription->refill_date }} 
      <strong style="margin-left:240px; color: #286187;">M.D.</strong> {{ $doctor_first_name }} {{ $doctor_middle_name }} {{ $doctor_last_name }}
  </p>
</div>

</body>
</html>
