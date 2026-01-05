
<?php
// Organizer Dashboard (Homepage)
// MARZUQI: Buat organizer homepage, kasi dia link semua site yang ada dalam homepage
session_start();
require_once __DIR__ . '/../db.php';

// TODO: integrate with your real auth/session
if (!isset($_SESSION['organizer_id'])) { $_SESSION['organizer_id'] = 1; /* demo only */ }
$organizerId = (int)$_SESSION['organizer_id'];

// Basic stats
$stats = ['total_events'=>0,'upcoming_events'=>0,'total_registrations'=>0];

$q1 = mysqli_query($conn, "SELECT COUNT(*) AS c FROM events WHERE organizer_id=$organizerId AND deleted_at IS NULL");
if ($q1) { $stats['total_events'] = (int) mysqli_fetch_assoc($q1)['c']; }

$q2 = mysqli_query($conn, "SELECT COUNT(*) AS c FROM events WHERE organizer_id=$organizerId AND status='published' AND event_date >= CURDATE() AND deleted_at IS NULL");
if ($q2) { $stats['upcoming_events'] = (int) mysqli_fetch_assoc($q2)['c']; }

$q3 = mysqli_query($conn, "SELECT COALESCE(SUM(current_registrations),0) AS s FROM events WHERE organizer_id=$organizerId AND deleted_at IS NULL");
if ($q3) { $stats['total_registrations'] = (int) mysqli_fetch_assoc($q3)['s']; }
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Organizer Dashboard</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 24px; }
    .cards { display: flex; gap: 16px; margin-bottom: 16px; }
    .card { padding: 12px 16px; border: 1px solid #ddd; border-radius: 6px; background:#f9f9f9; }
    nav a { margin-right: 12px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ddd; padding: 8px; }
    th { background: #f1f1f1; }
    .actions a, .actions button { margin-right: 8px; }
  </style>
</head>
<body>
  <h1>Organizer Dashboard</h1>

  <div class="cards">
    <div class="card">Total Events: <?= $stats['total_events'] ?></div>
    <div class="card">Upcoming Events: <?= $stats['upcoming_events'] ?></div>
    <div class="card">Total Registrations: <?= $stats['total_registrations'] ?></div>
  </div>

  <nav>
    event_form.php+ Create Event</a>
    events_list.phpManage Events</a>
    #Attendance (coming soon)</a>
    #Feedback (coming soon)</a>
    #Reports (coming soon)</a>
    #Profile (coming soon)</a>
  </nav>

  <h2>Recent Events</h2>
  <?php
    $res = mysqli_query($conn, "SELECT id,title,event_date,start_time,end_time,status,current_registrations,max_participants
                                FROM events
                                WHERE organizer_id=$organizerId AND deleted_at IS NULL
                                ORDER BY event_date DESC LIMIT 10");
  ?>
  <table>
    <tr>
      <th>Title</th><th>Date</th><th>Time</th><th>Status</th><th>Regs</th><th>Actions</th>
    </tr>
    <?php if ($res && mysqli_num_rows($res)>0): while($row = mysqli_fetch_assoc($res)): ?>
      <tr>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['event_date']) ?></td>
        <td><?= htmlspecialchars($row['start_time']) ?>–<?= htmlspecialchars($row['end_time']) ?></td>
        <td><?= htmlspecialchars($row['status']) ?></td>
        <td><?= (int)$row['current_registrations'] ?>/<?= (int)$row['max_participants'] ?></td>
        <td class="actions">
          event_form.php?id=<?= (int)$row[">Edit</a>
          <button class="btn-delete" data-id="<?= (int)$row['id'] ?>">Delete</button>
          <button class="btn-publish" data-id="<?= (int)$row['id'] ?>" data-status="<?= ($row['status']==='published')?'draft':'published' ?>">
            <?= ($row['status']==='published')?'Unpublish':'Publish' ?>
          </button>
        </td>
      </tr>
    <?php endwhile; else: ?>
      <tr><td colspan="6">No events yet.</td></tr>
    <?php endif; ?>
  </table>

  ../assets/js/organizer_events.js</script>
</body>
</html>
