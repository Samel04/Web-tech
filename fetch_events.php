<?php
require_once "db.php";

$date = $_GET['date'] ?? '';
$category = $_GET['category'] ?? '';

$sql = "SELECT * FROM events WHERE status='approved'";

if ($date !== '') {
    $sql .= " AND event_date='$date'";
}
if ($category !== '') {
    $sql .= " AND category='$category'";
}

$sql .= " ORDER BY event_date ASC";
$res = $conn->query($sql);

echo "<table>
<tr><th>Title</th><th>Date</th><th>Time</th><th>Location</th><th>Category</th></tr>";

if ($res->num_rows > 0) {
    while ($r = $res->fetch_assoc()) {
        echo "<tr>
                <td>{$r['title']}</td>
                <td>{$r['event_date']}</td>
                <td>{$r['start_time']} - {$r['end_time']}</td>
                <td>{$r['location']}</td>
                <td>{$r['category']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No events found</td></tr>";
}
echo "</table>";
