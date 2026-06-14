<?php
$conn = new mysqli(
  "localhost",
  "znbsejka_raka",
  "latifannyrakka1412",
  "znbsejka_akuarium_raka"
);

if($conn->connect_error){
  die("DB ERROR: ".$conn->connect_error);
}
$conn->query("SET time_zone = '+07:00'");
?>