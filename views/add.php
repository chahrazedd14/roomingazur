<?php

if($_POST['room_id'] && $_POST['room_type'] && $_POST['firstname'] && $_POST['date'] && $_REQUEST['t']){
require_once '../db/config.php';


$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS,DB_NAME);
// Check connection
if (!$con) {
  die("Connection failed: " . mysqli_connect_error());
}


$room_id = mysqli_real_escape_string($con,$_POST['room_id']);
$room_type = mysqli_real_escape_string($con,$_POST['room_type']);
$firstname = mysqli_real_escape_string($con,$_POST['firstname']);
$lastname = mysqli_real_escape_string($con,$_POST['lastname']);
$date = mysqli_real_escape_string($con,$_POST['date']);

if($_REQUEST['t']=='add'){

$result = $con->query("SELECT id FROM rent WHERE room_num = $room_id");
if($result->num_rows != 0)
die("Already used the room number!"); 

$result = $con->query("SELECT id FROM room WHERE room_num = $room_id");
if($result->num_rows == 0)
die("Invalid room number!"); 

    
if(mysqli_query($con,"INSERT INTO rent (room_num,room_type,firstname,lastname,date) VALUES ($room_id,'$room_type','$firstname','$lastname','$date')"))
die("Added.");
else
die("Unable to add!");
}
else if($_REQUEST['t']=='update' && $_POST['id'] && is_numeric($_POST['id']))
{

$result = $con->query("SELECT id FROM room WHERE room_num = $room_id");
if($result->num_rows == 0)
die("Invalid room number!"); 

$result = $con->query("SELECT id FROM rent WHERE room_num = $room_id AND id<>".$_POST['id']);
if($result->num_rows != 0)
die("Already used the room number!"); 
    
if(mysqli_query($con,"UPDATE rent SET room_num=$room_id,room_type='$room_type',firstname='$firstname',lastname='$lastname', date='$date' WHERE id=".$_POST['id']))
die("Updated.");
else
die("Unable to add!");
}
else
die("Validation Error!!");


}
else
die("Validation Error!!");