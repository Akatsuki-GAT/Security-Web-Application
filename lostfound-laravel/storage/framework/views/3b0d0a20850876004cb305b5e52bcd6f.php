<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
     <h1>Admin Dashboard</h1>

    <p>Welcome, <?php echo e(auth()->user()->username); ?></p>

    <ul>
        <li><a href="">Manage Users</a></li>
        <li><a href="">Moderate Items</a></li>
    </ul>

    <form method="POST" action="<?php echo e(route('logout')); ?>">
       <?php echo csrf_field(); ?>
        <button class="dropdown-item">Logout</button>
    </form>
</body>
</html>

   <?php /**PATH C:\xampp\htdocs\lostfound-laravel\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>