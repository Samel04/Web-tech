@ -0,0 +1,41 @@
<?php
include("../config/db.php");

$date = $_GET['date'] ?? '';
$category = $_GET['category'] ?? '';

$sql = "SELECT * FROM events WHERE status='approved'";

if ($date != '') {
    $sql .= " AND event_date='$date'";
}
if ($category != '') {
    $sql .= " AND category='$category'";
}

$sql .= " ORDER BY event_date ASC";
$result = $conn->query($sql);

echo "<table>";
echo "<tr>
        <th>Title</th>
        <th>Date</th>
        <th>Time</th>
        <th>Location</th>
        <th>Category</th>
      </tr>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['title']}</td>
                <td>{$row['event_date']}</td>
                <td>{$row['start_time']} - {$row['end_time']}</td>
                <td>{$row['location']}</td>
                <td>{$row['category']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No events found</td></tr>";
}
echo "</table>";
