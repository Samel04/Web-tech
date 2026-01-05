@ -0,0 +1,41 @@
<?php
include("../config/db.php");
session_start();
if ($_SESSION['role'] == 'participant') die("Access denied");

$condition = "";
if ($_SESSION['role'] == 'organizer') {
    $uid = $_SESSION['user_id'];
    $condition = "WHERE e.organizer_id=$uid";
}

$sql = "
SELECT e.title,
       COUNT(f.feedback_id) AS total_feedback,
       AVG(f.rating) AS avg_rating
FROM events e
LEFT JOIN feedback f ON e.event_id = f.event_id
$condition
GROUP BY e.event_id
";

$result = $conn->query($sql);
?>

<h2>📊 Feedback Summary</h2>

<table border="1" cellpadding="10">
<tr>
    <th>Event</th>
    <th>Total Feedback</th>
    <th>Average Rating</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?= $row['title'] ?></td>
    <td><?= $row['total_feedback'] ?></td>
    <td><?= number_format($row['avg_rating'],2) ?></td>
</tr>
<?php } ?>
</table>
