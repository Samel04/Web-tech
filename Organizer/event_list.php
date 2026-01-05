<?php
session_start();
require_once __DIR__ . '/../db.php';
if (!isset($_SESSION['organizer_id'])) { $_SESSION['organizer_id'] = 1; /* demo only */ }
$organizerId = (int)$_SESSION['organizer_id'];

$q = trim($_GET['q'] ?? '');
$category = (int)($_GET['category'] ?? 0);
$status = $_GET['status'] ?? '';
$where = "organizer_id=$organizerId AND deleted_at IS NULL";
if ($q !== '') $where .= " AND title LIKE '%" . mysqli_real_escape_string($conn, $q) . "%'";
if ($category > 0) $where .= " AND category_id=$category";
if (in_array($status, ['draft','published','archived'])) $where .= " AND status='$status'";

$sql = "SELECT e.*, c.name AS category_name
        FROM events e JOIN categories c ON e.category_id=c.id
        WHERE $where ORDER BY event_date DESC";
$res = mysqli_query($conn, $sql);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Manage Events</title>
  <style> body{font-family:Arial,sans-serif;margin:24px;} table{border-collapse:collapse;width:100%;} th,td{border:1px solid #ddd;padding:8px;} th{background:#f1f1f1;} </style>
</head>
<body>
  <h2>Manage Events</h2>
  <p>
    event_form.php+ Create Event</a>
  </p>

  <table>
    <tr>
      <th>Title</th><th>Date</th><th>Category</th><th>Status</th><th>Regs</th><th>Actions</th>
    </tr>
    <?php if ($res && mysqli_num_rows($res)>0): while($row = mysqli_fetch_assoc($res)): ?>
      <tr>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['event_date']) ?> (<?= htmlspecialchars($row['start_time']) ?>–<?= htmlspecialchars($row['end_time']) ?>)</td>
        <td><?= htmlspecialchars($row['category_name']) ?></td>
        <td><?= htmlspecialchars($row['status']) ?></td>
        <td><?= (int)$row['current_registrations'] ?>/<?= (int)$row['max_participants'] ?></td>
        <td>
          event_form.php?id=<?= (int)$row[">Edit</a>
          <button class="btn-delete" data-id="<?= (int)$row['id'] ?>">Delete</button>
          <button class="btn-publish" data-id="<?= (int)$row['id'] ?>" data-status="<?= ($row['status']==='published')?'draft':'published' ?>">
            <?= ($row['status']==='published')?'Unpublish':'Publish' ?>
          </button>
        </td>
      </tr>
    <?php endwhile; else: ?>
      <tr><td colspan="6">No events found.</td></tr>
    <?php endif; ?>
  </table>

  ../assets/js/organizer_events.js</script>
</body>
</html>
