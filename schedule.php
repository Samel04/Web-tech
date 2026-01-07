<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['role'])) {
    die("Access denied");
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Event Schedule</title>
<script>
function loadEvents(){
    let d=document.getElementById('date').value;
    let c=document.getElementById('category').value;
    fetch('fetch_events.php?date='+d+'&category='+c)
    .then(r=>r.text())
    .then(t=>document.getElementById('result').innerHTML=t);
}
window.onload=loadEvents;
</script>
</head>
<body>

<h2>📅 Event Schedule</h2>

Date: <input type="date" id="date" onchange="loadEvents()">
Category:
<select id="category" onchange="loadEvents()">
<option value="">All</option>
<option value="Workshop">Workshop</option>
<option value="Charity">Charity</option>
<option value="Sports">Sports</option>
<option value="Club">Club</option>
</select>

<div id="result"></div>

</body>
</html>
