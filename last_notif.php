<?php
require "db.php";
header("Content-Type: application/json");

$q=$conn->query("SELECT id,pesan FROM notifikasi ORDER BY id DESC LIMIT 1");
echo $q->num_rows?json_encode($q->fetch_assoc()):json_encode(null);
