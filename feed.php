<?php
require "db.php";

$f="feed.txt";
if(!file_exists($f)) file_put_contents($f,"0");

if(isset($_GET['feed'])){
  file_put_contents($f,"1");

  $conn->query("
    INSERT INTO log_pakan(mode,jumlah_putaran,keterangan)
    VALUES('MANUAL',1,'Pakan manual dari web')
  ");

  $conn->query("
    INSERT INTO notifikasi(jenis,pesan,sumber)
    VALUES('INFO','Pakan manual diberikan','WEB')
  ");

  echo "1";
  exit;
}

if(isset($_GET['reset'])){
  file_put_contents($f,"0");
  echo "0";
  exit;
}

echo trim(file_get_contents($f));
