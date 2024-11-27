<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
* {
  box-sizing: border-box;
}

/* Create two equal columns that float next to each other */
.column {
  float: left;
  width: 50%;
  padding: 10px;
  height: 300px; /* Should be removed. Only for demonstration */
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}

/* Style for the image */
.image-container {
  width: 100px; /* Set the width of the image */
  height: auto;
  margin-right: 15px; /* Space between the image and text */
  float: left;
}
</style>
</head>
<body>

<div class="row">
    <div class="image-container">
        <!-- Displaying the image from public directory -->
        <img src="{{ asset('rxlogo.png') }}" alt="RX Logo">
    </div>
  <!-- Column 1: Name and Address with Image -->
  <div class="column">
    <p><strong>Name:</strong> {{ $first_name }} {{ $middle_name }} {{ $last_name }}</p>
    <p><strong>Address:</strong> {{ $address }}</p>
  </div>

  <!-- Column 2: Age and Sex -->
  <div class="column">
    <p><strong>Age:</strong> {{ $age }}</p>
    <p><strong>Sex:</strong> {{ $sex }}</p>
  </div>
</div>

<h3>Prescriptions:</h3>
<ol>
    @foreach($prescriptions as $index => $prescription)
        <li>
            <strong>Medication:</strong> {{ $prescription->medication }} 
            <strong>Quantity:</strong> {{ $prescription->quantity }} 
            <strong>Sig:</strong> {{ $prescription->sig }}
        </li>
    @endforeach
</ol>

</body>
</html>
