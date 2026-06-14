<?php
require "db.php";

/* ===== ESP32CAM UPDATE STATUS ===== */
if(isset($_GET['status'])){

  $status = $_GET['status'];

  $conn->query("
  UPDATE kamera_status
  SET status='$status', last_update=NOW()
  WHERE id=1
  ");

  echo "OK";
  exit;
}


/* ===== DASHBOARD CEK STATUS ===== */
if(isset($_GET['check'])){

  $q = $conn->query("SELECT status,last_update FROM kamera_status WHERE id=1");
  $d = $q->fetch_assoc();

  $last = strtotime($d['last_update']);
  $now  = time();

  $diff = $now - $last;

  if($diff > 20){
      $d['status'] = "OFFLINE";
  }

  echo json_encode($d);
}
?>