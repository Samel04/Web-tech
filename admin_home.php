<?php
session_start();
include("db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}

$result = $conn->query("SELECT COUNT(*) AS total FROM users");
$totalUsers = $result->fetch_assoc()['total'];

$result = $conn->query("SELECT COUNT(*) AS total FROM events");
$totalEvents = $result->fetch_assoc()['total'];

$result = $conn->query(
    "SELECT COUNT(*) AS total FROM events WHERE status = 'pending'"
);
$pendingEvents = $result->fetch_assoc()['total'];

$result = $conn->query(
    "SELECT COUNT(*) AS total 
     FROM users 
     WHERE role = 'organizer' AND status = 'inactive'"
);
$pendingOrganizers = $result->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>

<h1>Admin Dashboard</h1>

<p>Welcome, <strong><?= $_SESSION['name'] ?? 'Admin'; ?></strong></p>

<hr>

<h3>System Overview</h3>
<ul>
    <li>Total Users: <strong><?= $totalUsers ?></strong></li>
    <li>Total Events: <strong><?= $totalEvents ?></strong></li>
    <li>Pending Event Approvals: <strong><?= $pendingEvents ?></strong></li>
    <li>Pending Organizer Approvals: <strong><?= $pendingOrganizers ?></strong></li>
</ul>

<hr>

<h3>Admin Actions</h3>
<ul>
    <li><a href="event_list.php">Approve Events</a></li>
    <li><a href="organizer_list.php">Approve Organizers</a></li>
    <li><a href="user_list.php">Manage Users</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>

</body>
</html>
