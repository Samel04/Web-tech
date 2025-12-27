<?php
session_start();

$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>
        <?php 
            if ($role === 'admin') echo "Admin Dashboard";
            elseif ($role === 'user') echo "User Dashboard";
            else echo "Digital Service Booking System";
        ?>
    </title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav>
    <a href="index.php">Home</a>

    <?php if ($role === 'admin'): ?>
        <a href="service.php">Manage Services</a>
        <a href="booking.php">View Bookings</a>
        <a href="review.php">Manage Reviews</a>
        <a href="logout.php">Logout</a>

    <?php elseif ($role === 'user'): ?>
        <a href="service_user.php">Services</a>
        <a href="booking.php">My Bookings</a>
        <a href="review.php">Reviews</a>
        <a href="logout.php">Logout</a>

    <?php else: ?>
        <a href="login.php">Login</a>
    <?php endif; ?>
</nav>

<div class="container">
    <?php if ($role === 'admin'): ?>
        <h2>Welcome, Admin</h2>
        <p>You can manage services, bookings, and reviews.</p>

    <?php elseif ($role === 'user'): ?>
        <h2>Welcome, <?= htmlspecialchars($_SESSION['name']) ?></h2>
        <p>You can browse services, make bookings, and submit reviews.</p>

    <?php else: ?>
        <h1>Digital Service Booking & Management System</h1>
        <p>
            Welcome to Digital Service Booking & Management System.
            You can easily book any type of service available on this website.
        </p>
    <?php endif; ?>
</div>

<footer>
    <?php 
        if ($role === 'admin') echo "Admin Dashboard";
        elseif ($role === 'user') echo "User Dashboard";
        else echo "© 2025 DSBMS | A simple booking management";
    ?>
</footer>

</body>
</html>