@ -0,0 +1,54 @@
<?php
include("../config/db.php");
session_start();
if (!isset($_SESSION['role'])) die("Access denied");
?>

<!DOCTYPE html>
<html>
<head>
<title>Event Schedule</title>
<style>
body { font-family: Arial; margin:20px; }
.filter { margin-bottom:15px; }
table { width:100%; border-collapse: collapse; }
th, td { border:1px solid #ccc; padding:10px; text-align:left; }
th { background:#f0f0f0; }
</style>

<script>
function loadEvents() {
    const date = document.getElementById("date").value;
    const category = document.getElementById("category").value;

    fetch("fetch_events.php?date=" + date + "&category=" + category)
    .then(res => res.text())
    .then(data => document.getElementById("result").innerHTML = data);
}

window.onload = loadEvents;
</script>
</head>

<body>

<h2>📅 Event Schedule</h2>

<div class="filter">
    <label>Date:</label>
    <input type="date" id="date" onchange="loadEvents()">

    <label>Category:</label>
    <select id="category" onchange="loadEvents()">
        <option value="">All</option>
        <option value="Workshop">Workshop</option>
        <option value="Charity">Charity</option>
        <option value="Sports">Sports</option>
        <option value="Club">Club</option>
    </select>
</div>

<div id="result"></div>

</body>
</html>
