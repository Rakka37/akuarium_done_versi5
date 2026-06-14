<?php
require "db.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ================= INPUT =================
$air = floatval($_GET['air'] ?? 0);
$ntu = floatval($_GET['ntu'] ?? 0);
$ph  = floatval($_GET['ph'] ?? 0);
$jarak = $_GET['jarak'] ?? 0;
$ph_status = $_GET['ph_status'] ?? 'NAIK';

// ================= UPDATE REALTIME =================

$conn->query("
  UPDATE realtime 
  SET suhu_air=$air, ntu=$ntu, ph=$ph, ph_status='$ph_status', jarak_air='$jarak' 
  WHERE id=1
");

// ================= AMBIL STATUS TERAKHIR =================

// SUHU
$lastSuhu = "";
$q = $conn->query("SELECT pesan FROM notifikasi WHERE pesan LIKE 'Suhu air%' ORDER BY id DESC LIMIT 1");
if($q && $q->num_rows){
  $lastSuhu = $q->fetch_assoc()['pesan'];
}

// PH
$lastPH = "";
$q = $conn->query("SELECT pesan FROM notifikasi WHERE pesan LIKE 'pH%' ORDER BY id DESC LIMIT 1");
if($q && $q->num_rows){
  $lastPH = $q->fetch_assoc()['pesan'];
}

// ================= LOGIKA SUHU =================
if($air >= 30){
  if($lastSuhu != 'Suhu air tinggi, kipas nyala'){
    $conn->query("INSERT INTO notifikasi(jenis,pesan,sumber) VALUES('WARNING','Suhu air tinggi, kipas nyala','SENSOR')");
  }
}
else if($air < 25){
  if($lastSuhu != 'Suhu air rendah, heater nyala'){
    $conn->query("INSERT INTO notifikasi(jenis,pesan,sumber) VALUES('WARNING','Suhu air rendah, heater nyala','SENSOR')");
  }
}
else{
  if($lastSuhu != 'Suhu air aman'){
    $conn->query("INSERT INTO notifikasi(jenis,pesan,sumber) VALUES('INFO','Suhu air aman','SENSOR')");
  }
}

// ================= LOGIKA PH =================
if($ph < 6.5){
  if($lastPH != 'pH air terlalu asam'){
    $conn->query("INSERT INTO notifikasi(jenis,pesan,sumber) VALUES('WARNING','pH air terlalu asam','SENSOR')");
  }
}
else if($ph > 8){
  if($lastPH != 'pH air terlalu basa'){
    $conn->query("INSERT INTO notifikasi(jenis,pesan,sumber) VALUES('WARNING','pH air terlalu basa','SENSOR')");
  }
}
else{
  if($lastPH != 'pH air normal'){
    $conn->query("INSERT INTO notifikasi(jenis,pesan,sumber) VALUES('INFO','pH air normal','SENSOR')");
  }
}

// ================= STATUS PH (SERVO) =================
$lastStatus = "";
$q = $conn->query("SELECT pesan FROM notifikasi WHERE pesan LIKE 'Status pH%' ORDER BY id DESC LIMIT 1");

if($q && $q->num_rows){
  $lastStatus = $q->fetch_assoc()['pesan'];
}

$statusText = "Status pH: ".$ph_status;

if($lastStatus != $statusText){
  $conn->query("INSERT INTO notifikasi(jenis,pesan,sumber) VALUES('INFO','$statusText','SYSTEM')");
}

echo "OK";