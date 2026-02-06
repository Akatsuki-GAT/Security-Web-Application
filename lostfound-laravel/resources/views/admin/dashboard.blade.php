<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
     <h1>Admin Dashboard</h1>

    <p>Welcome, {{ auth()->user()->username }}</p>

    <ul>
        <li><a href="">Manage Users</a></li>
        <li><a href="">Moderate Items</a></li>
    </ul>

    <form method="POST" action="{{ route('logout') }}">
       @csrf
        <button class="dropdown-item">Logout</button>
    </form>
</body>
</html>

   