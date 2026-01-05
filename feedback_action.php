@ -0,0 +1,27 @@
<?php
include("../config/db.php");
session_start();

if ($_SESSION['role'] != 'participant') die("Access denied");

$event_id = $_POST['event_id'];
$rating = $_POST['rating'];
$comments = $_POST['comments'];
$participant_id = $_SESSION['user_id'];

/* Prevent duplicate feedback */
$check = $conn->query("
SELECT * FROM feedback
WHERE event_id=$event_id AND participant_id=$participant_id
");

if ($check->num_rows > 0) {
    die("You have already submitted feedback for this event.");
}

$conn->query("
INSERT INTO feedback (event_id, participant_id, rating, comments)
VALUES ($event_id, $participant_id, $rating, '$comments')
");

echo "Feedback submitted successfully.";
