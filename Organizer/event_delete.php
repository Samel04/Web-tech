
<?php
session_start();
require_once __DIR__ . '/../db.php';
header('Content-Type: application/json');

$organizerId = (int)($_SESSION['organizer_id'] ?? 0);
$id = (int)($_POST['id'] ?? 0);

$stmt = mysqli_prepare($conn, "UPDATE events SET deleted_at=NOW() WHERE id=? AND organizer_id=? AND deleted_at IS NULL");
mysqli_stmt_bind_param($stmt, 'ii', $id, $organizerId);

if (mysqli_stmt_execute($stmt)) {
  echo json_encode(['ok'=>true,'message'=>'Event archived']);
} else {
  echo json_encode(['ok'=>false,'message'=>'DB error']);
}
