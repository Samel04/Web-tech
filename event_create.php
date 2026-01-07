
<?php
session_start();
require_once __DIR__ . '/../db.php';
header('Content-Type: application/json');

$organizerId = (int)($_SESSION['organizer_id'] ?? 0);
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
if ($max_participants < 1) $errors['max_participants'] = 'Must be >= 1';
if (!in_array($status, ['draft','published','archived'])) $errors['status'] = 'Invalid status';
if ($status === 'published' && strtotime($event_date) < strtotime(date('Y-m-d'))) $errors['event_date'] = 'Event date cannot be in the past for published events';

if (!empty($errors)) { echo json_encode(['ok'=>false,'errors'=>$errors]); exit; }

$stmt = mysqli_prepare($conn, "INSERT INTO events (organizer_id,title,description,category_id,location,event_date,start_time,end_time,max_participants,status)
VALUES (?,?,?,?,?,?,?,?,?,?)");
mysqli_stmt_bind_param($stmt, 'ississssiss', $organizerId, $title, $description, $category_id, $location, $event_date, $start_time, $end_time, $max_participants, $status);

if (mysqli_stmt_execute($stmt)) {
  echo json_encode(['ok'=>true,'message'=>'Event created','data'=>['id'=>mysqli_insert_id($conn)]]);
} else {
  echo json_encode(['ok'=>false,'message'=>'DB error']);
}
