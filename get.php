<?php
require "db.php";
header("Content-Type: application/json");

$q=$conn->query("
SELECT suhu_air, ntu, ph, ph_status, jarak_air
FROM realtime
WHERE id=1
");

$d=$q->fetch_assoc();

echo json_encode([
  "air"       => round($d['suhu_air'],1),
  "ntu"       => round($d['ntu'],1),
  "ph"        => round($d['ph'],2),
  "ph_status" => $d['ph_status'] ?? 'NAIK',
  "jarak"     => round($d['jarak_air'],1)
]);