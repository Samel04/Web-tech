
<?php
session_start();
require_once __DIR__ . '/../db.php';
header('Content-Type: application/json');

$organizerId = (int)($_SESSION['organizer_id'] ?? 0);
$id = (int)($_POST['id'] ?? 0);
$status = $_POST['status'] ?? 'draft';

if (!in_array($status, ['draft','published','archived'])) { echo json_encode(['ok'=>false,'errors'=>['status'=>'Invalid status']]); exit; }

$stmt0 = mysqli_prepare($conn, "SELECT event_date FROM events WHERE id=? AND organizer_id=? AND deleted_at IS NULL");
mysqli_stmt_bind_param($stmt0, 'ii', $id, $organizerId);
mysqli_stmt_execute($stmt0);
$res0 = mysqli_stmt_get_result($stmt0);
$ev = mysqli_fetch_assoc($res0);
if (!$ev) { echo json_encode(['ok'=>false,'message'=>'Not found']); exit; }

if ($status === 'published' && strtotime($ev['event_date']) < strtotime(date('Y-m-d'))) {
  echo json_encode(['ok'=>false,'errors'=>['event_date'=>'Cannot publish past event']]); exit; }

$stmt = mysqli_prepare($conn, "UPDATE events SET status=?, updated_at=NOW() WHERE id=? AND organizer_id=? AND deleted_at IS NULL");
mysqli_stmt_bind_param($stmt, 'sii', $status, $id, $organizerId);

$ok = mysqli_stmt_execute($stmt);
echo json_encode(['ok'=>$ok]);
