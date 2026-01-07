
<?php
session_start();
require_once __DIR__ . '/../db.php';
header('Content-Type: application/json');

$organizerId = (int)($_SESSION['organizer_id'] ?? 0);
$id = (int)($_POST['id'] ?? 0);

$stmt0 = mysqli_prepare($conn, "SELECT current_registrations FROM events WHERE id=? AND organizer_id=? AND deleted_at IS NULL");
mysqli_stmt_bind_param($stmt0, 'ii', $id, $organizerId);
mysqli_stmt_execute($stmt0);
$res0 = mysqli_stmt_get_result($stmt0);
$cur = mysqli_fetch_assoc($res0);
if (!$cur) { echo json_encode(['ok'=>false,'message'=>'Not found']); exit; }
$current_regs = (int)$cur['current_registrations'];

$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$category_id = (int)($_POST['category_id'] ?? 0);
$location = trim($_POST['location'] ?? '');
$event_date = $_POST['event_date'] ?? '';
$start_time = $_POST['start_time'] ?? '';
$end_time = $_POST['end_time'] ?? '';
$max_participants = (int)($_POST['max_participants'] ?? 0);
$status = $_POST['status'] ?? 'draft';

$errors = [];
if (strlen($title) < 5 || strlen($title) > 150) $errors['title'] = 'Title must be 5-150 chars';
if ($category_id <= 0) $errors['category_id'] = 'Category is required';
if ($location === '') $errors['location'] = 'Location is required';
if ($event_date === '') $errors['event_date'] = 'Event date is required';
if ($start_time === '' || $end_time === '' || $end_time <= $start_time) $errors['time'] = 'Invalid time range';
if ($max_participants < $current_regs) $errors['max_participants'] = 'Cannot be less than current registrations';
if (!in_array($status, ['draft','published','archived'])) $errors['status'] = 'Invalid status';
if ($status === 'published' && strtotime($event_date) < strtotime(date('Y-m-d'))) $errors['event_date'] = 'Event date cannot be in the past for published events';

if (!empty($errors)) { echo json_encode(['ok'=>false,'errors'=>$errors]); exit; }

$stmt = mysqli_prepare($conn, "UPDATE events SET
title=?, description=?, category_id=?, location=?, event_date=?, start_time=?, end_time=?,
max_participants=?, status=?, updated_at=NOW()
WHERE id=? AND organizer_id=? AND deleted_at IS NULL");
mysqli_stmt_bind_param($stmt, 'ssissssissi', $title, $description, $category_id, $location, $event_date, $start_time, $end_time, $max_participants, $status, $id, $organizerId);

if (mysqli_stmt_execute($stmt)) {
  echo json_encode(['ok'=>true,'message'=>'Event updated']);
} else {
  echo json_encode(['ok'=>false,'message'=>'DB error']);
}
