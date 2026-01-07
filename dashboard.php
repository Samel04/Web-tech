
<?php
session_start();
require_once __DIR__ . '/../db.php'; // go up one level from Organizer\ to include db.php

// Require organizer login
$organizerId = (int)($_SESSION['user_id'] ?? 0);
$role        = $_SESSION['role'] ?? '';
if ($organizerId <= 0 || $role !== 'organizer') {
    header('Location: ../login.php');
    exit;
}

/**
 * STATS SECTION
 * Removed `deleted_at IS NULL` because your events table doesn't have that column.
 */

// Total events
$stats = ['total_events' => 0, 'upcoming_events' => 0, 'total_registrations' => 0];

$stmt = $conn->prepare("SELECT COUNT(*) AS c FROM events WHERE organizer_id = ?");
$stmt->bind_param("i", $organizerId);
$stmt->execute();
$stats['total_events'] = (int)$stmt->get_result()->fetch_assoc()['c'];

// Upcoming published events (today or later)
$stmt = $conn->prepare("
    SELECT COUNT(*) AS c
    FROM events
    WHERE organizer_id = ?
      AND status = 'published'
      AND event_date >= CURDATE()
");
$stmt->bind_param("i", $organizerId);
$stmt->execute();
$stats['upcoming_events'] = (int)$stmt->get_result()->fetch_assoc()['c'];

// Sum of current registrations across organizer's events
$stmt = $conn->prepare("SELECT COALESCE(SUM(current_registrations), 0) AS s FROM events WHERE organizer_id = ?");
$stmt->bind_param("i", $organizerId);
$stmt->execute();
$stats['total_registrations'] = (int)$stmt->get_result()->fetch_assoc()['s'];

/**
 * RECENT EVENTS LIST
 */
$list = [];
$stmt = $conn->prepare("
    SELECT event_id, title, event_date, start_time, end_time, status, current_registrations
    FROM events
    WHERE organizer_id = ?
    ORDER BY event_date DESC
    LIMIT 20
");
$stmt->bind_param("i", $organizerId);
$stmt->execute();
$res = $stmt->get_result();
$list = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Organizer Dashboard</title>
  <style>
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ccc; padding: 8px; }
    th { background: #f5f5f5; text-align: left; }
    .actions a { margin-right: 8px; }
  </style>
</head>
<body>
  <h1>Organizer Dashboard</h1>

  <p>
    <strong>Total Events:</strong> <?php echo $stats['total_events']; ?> |
    <strong>Upcoming Events:</strong> <?php echo $stats['upcoming_events']; ?> |
    <strong>Total Registrations:</strong> <?php echo $stats['total_registrations']; ?>
  </p>

  <p class="actions">
    event_form.php">+ Create Event</a>
    events_list.php">Manage Events</a>
  </p>

  <h3>Recent Events</h3>
  <table>
    <tr>
      <th>Title</th>
      <th>Date</th>
      <th>Time</th>
      <th>Status</th>
      <th>Registrations</th>
      <th>Actions</th>
    </tr>

    <?php if (!empty($list)): ?>
      <?php foreach ($list as $row): ?>
        <tr>
          <td><?php echo htmlspecialchars($row['title']); ?></td>
          <td><?php echo htmlspecialchars($row['event_date']); ?></td>
          <td><?php echo htmlspecialchars(($row['start_time'] ?? '') . ' – ' . ($row['end_time'] ?? '')); ?></td>
          <td><?php echo htmlspecialchars($row['status']); ?></td>
          <td><?php echo (int)$row['current_registrations']; ?></td>
          <td class="actions">
            <!-- Edit -->
            event_form.php?id=<?php echo (int)$row[">
              Edit
            </a>

            <!-- Attendance -->
            attendance_page.php?event_id=<?php echo (int)$row[">
              Attendance
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="6">No events yet.</td></tr>
    <?php endif; ?>
  </table>
</body>
</html>
