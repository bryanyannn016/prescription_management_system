<!DOCTYPE html>
<html>
<head>
    <title>Create Account</title>
</head>
<body>
    <form method="POST" action="{{ url('admin/create-account') }}">
        @csrf
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="middle_name" placeholder="Middle Name">
        <input type="text" name="last_name" placeholder="Last Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <select name="type">
            <option value="doctor">Doctor</option>
            <option value="nurse">Nurse</option>
        </select>
        <input type="text" name="health_facility" placeholder="Health Facility">
        <button type="submit">Create Account</button>
    </form>
</body>
</html>
