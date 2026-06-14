<?php
require "db.php";
header("Content-Type: application/json");

$data=[];

$q=$conn->query("
  SELECT filename, created_at
  FROM foto_kamera
  ORDER BY id DESC
  LIMIT 200
");

while($r=$q->fetch_assoc()){
  $data[]=[
    "path"=>"https://akuariumrakka.my.id/uploads/".$r['filename'],
    "waktu"=>$r['created_at']
  ];
}

echo json_encode($data);
?>