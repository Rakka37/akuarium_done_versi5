<?php
require "db.php";
header("Content-Type: application/json");

$data=[];

$q=$conn->query("
  SELECT 'NOTIFIKASI' jenis,pesan,created_at waktu FROM notifikasi
  UNION ALL
  SELECT 'PAKAN',CONCAT(mode,' - ',keterangan),created_at FROM log_pakan
  ORDER BY waktu DESC
  LIMIT 30
");

while($r=$q->fetch_assoc()) $data[]=$r;

echo json_encode($data);
