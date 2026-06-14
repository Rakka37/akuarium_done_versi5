<?php
require "db.php";

$pesan = $_GET['pesan'] ?? '';
$jenis = $_GET['jenis'] ?? 'INFO';

if($pesan != ""){

  $p = $conn->real_escape_string($pesan);
  $j = $conn->real_escape_string($jenis);

  $conn->query("
    INSERT INTO notifikasi(jenis,pesan,sumber)
    VALUES('$j','$p','ESP32')
  ");

  echo "OK";

}else{
  echo "NO DATA";
}
?>