<?php
require_once '../db/config.php';

$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS,DB_NAME);
// Check connection
if (!$con) {
  die("Connection failed: " . mysqli_connect_error());
}

$lsitRoom=[];

$query='SELECT room.room_num, (SELECT 1 FROM rent WHERE rent.room_num=room.room_num  LIMIT 1) AS used_room FROM room';

$result = $con->query($query);

while($row = $result->fetch_assoc()) {
   array_push($lsitRoom,$row);
  }
  
  $con-> close();
  header('Content-Type: application/json');
  echo json_encode($lsitRoom);
?>