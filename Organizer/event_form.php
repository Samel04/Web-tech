
<?php
session_start();
require_once __DIR__ . '/../db.php';
if (!isset($_SESSION['organizer_id'])) { $_SESSION['organizer_id'] = 1; /* demo only */ }
$organizerId = (int)$_SESSION['organizer_id'];

$eventId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$event = null;
if ($eventId > 0) {
  $stmt = mysqli_prepare($conn, "SELECT * FROM events WHERE id=? AND organizer_id=? AND deleted_at IS NULL");
  mysqli_stmt_bind_param($stmt, 'ii', $eventId, $organizerId);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  $event = mysqli_fetch_assoc($res);
}

$catsRes = mysqli_query($conn, "SELECT id,name FROM categories ORDER BY name ASC");
$categories = [];
if ($catsRes) { while($row = mysqli_fetch_assoc($catsRes)) { $categories[] = $row; } }
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title><?= $event ? 'Edit' : 'Create' ?> Event</title>
  <style> body { font-family: Arial, sans-serif; margin: 24px; } label { display:block; margin-top:12px; } input, select, textarea { width: 100%; padding: 8px; } button { margin-top: 16px; padding: 10px 16px; } </style>
</head>
<body>
  <h2><?= $event ? 'Edit' : 'Create' ?> Event</h2>
  <?= $event ? ">
    <?php if ($event): ?>
      <input type="hidden" name="id" value="<?= (int)$event['id'] ?>">
    <?php endif; ?>

    <label>Title</label>
    <input type="text" name="title" required minlength="5" maxlength="150" value="<?= htmlspecialchars($event['title'] ?? '') ?>">

    <label>Description</label>
    <textarea name="description" maxlength="2000"><?= htmlspecialchars($event['description'] ?? '') ?></textarea>

    <label>Category</label>
    <select name="category_id" required>
      <option value="">-- Select --</option>
      <?php foreach ($categories as $c): ?>
        <option value="<?= (int)$c['id'] ?>" <?= ($event && $event['category_id']==$c['id'])?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
      <?php endforeach; ?>
    </select>

    <label>Location</label>
    <input type="text" name="location" maxlength="200" required value="<?= htmlspecialchars($event['location'] ?? '') ?>">

    <label>Event Date</label>
    <input type="date" name="event_date" required value="<?= htmlspecialchars($event['event_date'] ?? '') ?>">

    <label>Start Time</label>
    <input type="time" name="start_time" required value="<?= htmlspecialchars($event['start_time'] ?? '') ?>">

    <label>End Time</label>
    <input type="time" name="end_time" required value="<?= htmlspecialchars($event['end_time'] ?? '') ?>">

    <label>Max Participants</label>
    <input type="number" name="max_participants" min="1" required value="<?= htmlspecialchars($event['max_participants'] ?? 1) ?>">

    <label>Status</label>
    <select name="status" required>
      <?php $statuses=['draft','published','archived']; $cur=$event['status'] ?? 'draft'; foreach($statuses as $s): ?>
        <option value="<?= $s ?>" <?= ($cur===$s)?'selected':'' ?>><?= ucfirst($s) ?></option>
      <?php endforeach; ?>
    </select>

    <button type="submit"><?= $event ? 'Update' : 'Create' ?></button>
    events_list.phpCancel</a>
  </form>

  ../assets/js/organizer_events.js</script>
</body>
</html>
