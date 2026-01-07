<?php
session_start();
require_once "db.php";

if ($_SESSION['role'] !== 'participant') {
    die("Access denied");
}

$uid = (int)$_SESSION['user_id'];

$events = $conn->query("
SELECT e.event_id, e.title
FROM events e
JOIN event_registrations r ON e.event_id = r.event_id
WHERE r.participant_id = $uid
AND r.status='registered'
AND e.status='approved'
");
?>

<h2>📝 Submit Feedback</h2>

<form method="POST" action="feedback_action.php">
    <label>Event</label><br>
    <select name="event_id" required>
        <?php while($e = $events->fetch_assoc()) { ?>
            <option value="<?= $e['event_id'] ?>"><?= $e['title'] ?></option>
        <?php } ?>
    </select><br><br>

    <label>Rating (1–5)</label><br>
    <input type="number" name="rating" min="1" max="5" required><br><br>

    <label>Comments</label><br>
    <textarea name="comments"></textarea><br><br>

    <button type="submit">Submit</button>
</form>
