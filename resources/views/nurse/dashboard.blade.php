<!DOCTYPE html>
<html>
<head>
    <title>Nurse Dashboard</title>
</head>
<body>
    <h1>Nurse Dashboard</h1>

    <!-- Logout Form -->
    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
