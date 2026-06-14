<?php
require "db.php";

$folder="uploads/";

if(!is_dir($folder)){
  mkdir($folder,0777,true);
}

$filename=date("YmdHis").".jpg";
$path=$folder.$filename;

$data=file_get_contents("php://input");

if(file_put_contents($path,$data)){

  $conn->query("INSERT INTO foto_kamera(filename) VALUES('$filename')");

  echo "OK";

}else{

  echo "FAIL";
}
?>