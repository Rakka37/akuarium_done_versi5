<?php
require "db.php";

$conn->query("
INSERT INTO log_pakan(mode,jumlah_putaran,keterangan)
VALUES('OTOMATIS',1,'Pakan otomatis sesuai jadwal')
");

$conn->query("
INSERT INTO notifikasi(jenis,pesan,sumber)
VALUES('INFO','Pakan otomatis diberikan','ESP32')
");

echo "OK";
