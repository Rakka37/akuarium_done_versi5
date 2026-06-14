<?php
require "db.php";
header("Content-Type: application/json");

$data=[];

$q=$conn->query("
  SELECT created_at AS waktu, jenis, pesan
  FROM notifikasi
  ORDER BY id DESC
  LIMIT 100
");

while($r=$q->fetch_assoc()) $data[]=$r;

echo json_encode($data);
