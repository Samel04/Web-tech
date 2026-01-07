<?php
session_start();
require_once "db.php";

if ($_SESSION['role'] !== 'participant') {
    die("Access denied");
}

$event_id = (int)$_POST['event_id'];
$rating = (int)$_POST['rating'];
$comments = mysqli_real_escape_string($conn, $_POST['comments']);
$uid = (int)$_SESSION['user_id'];

$check = $conn->query("
SELECT 1 FROM feedback
WHERE event_id=$event_id AND participant_id=$uid
");

if ($check->num_rows > 0) {
    die("Feedback already submitted.");
}

$conn->query("
INSERT INTO feedback (event_id, participant_id, rating, comments)
VALUES ($event_id, $uid, $rating, '$comments')
");

echo "Feedback submitted successfully.<br><a href='feedback_submit.php'>Back</a>";
