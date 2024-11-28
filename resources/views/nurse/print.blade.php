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

.column {
  float: left;
  width: 50%;
  padding: 10px;
}

.row:after {
  content: "";
  display: table;
  clear: both;
}

.image-container {
  width: 50px;
  height: auto;
  margin-right: 25px;
  float: left;
  margin-top:30px;
}

.row {
  margin-bottom: 20px;
  margin-left:20px;
}

h3 {
  color: #286187;
}

.refill-date {
  margin-top: 150px;
}




.site-header { 
  border-bottom: 1px solid #ccc;
  padding: .2em 1em;
}


.site-identity {
  float: left;
  margin:0;
}

.site-identity h1 {
  font-size: 1.5em;
  display: inline-block;
  color: #286187;
  text-align: center;
  font-weight: bold;
}


h3 {
  font-size: 1.2em;
  color: #286187;
  margin: 0; /* Remove default margin */
}


</style>
</head>
<body>

  <header class="site-header">
    <div class="site-identity">
      <img src="makatideplogo.png" alt="Makati Dep Logo" style="width: 90px; height:90px;margin-right: 40px; margin-left:20px;" />
      <strong><h1>MAKATI HEALTH DEPARTMENT</h1></strong>
      <img src="planetdrug.jpg" alt="PlanetDrugLogo" style="width:70px; height:70px; margin-left:40px;"/>
      
    </div>  
    <h3 style="margin-left:200px;">PLANET DRUGSTORE CORP.</h3>
  </header>

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
</div>

<h3>Prescriptions:</h3>
<ol>
  @foreach($prescriptions as $index => $prescription)
      <li>
          <strong style="color: #286187;">Medication:</strong> {{ $prescription->medication }} 
          
          <!-- Quantity aligned to the right -->
          <span style="float: right; color: #286187;"><strong>Quantity:</strong> {{ $prescription->quantity }}</span>
          
          <br> <!-- Break line -->
          <strong style="color: #286187;">Sig:</strong> {{ $prescription->sig }}
      </li>
  @endforeach
</ol>



<!-- Refill Date Section -->
<div class="refill-date">
  <p>
      <strong style="color: #286187;">Refill Date:</strong> {{ $prescription->refill_date }} 
      <strong style="margin-left:240px; color: #286187;">M.D.</strong> {{ $doctor_first_name }} {{ $doctor_middle_name }} {{ $doctor_last_name }}
  </p>
  <p>
      <strong style="color: #286187; margin-left:410px;">License No.</strong> {{ $doctor_license_no }}
  </p>
</div>

</body>
</html>
