<?php
require "db.php";

header("Content-Type: application/json");

$q = $conn->query("SELECT status,last_update FROM kamera_status WHERE id=1");
$data = $q->fetch_assoc();

echo json_encode($data);