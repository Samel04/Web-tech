<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['role'])) {
    die("Access denied");
}

/*
ADMIN  → see ALL events
ORGANIZER → see ONLY own events
*/
$where = "";
if ($_SESSION['role'] === 'organizer') {
    $uid = (int)$_SESSION['user_id'];
    $where = "WHERE e.organizer_id = $uid";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Feedback Summary</title>
</head>
<body>

<h2>📊 Feedback Summary</h2>

<table border="1" cellpadding="10">
<tr>
    <th>Event</th>
    <th>Total Feedback</th>
    <th>Average Rating</th>
</tr>

<?php
$sql = "
SELECT e.title,
       COUNT(f.feedback_id) AS total_feedback,
       IFNULL(AVG(f.rating),0) AS avg_rating
FROM events e
LEFT JOIN feedback f ON e.event_id = f.event_id
$where
GROUP BY e.event_id
ORDER BY e.event_date DESC
";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['title']}</td>
            <td>{$row['total_feedback']}</td>
            <td>".number_format($row['avg_rating'],2)."</td>
          </tr>";
}
?>

</table>

<a href="javascript:history.back()">← Back</a>

</body>
</html>
