<?php 
session_start();
include_once('../config/connection.php');
$id = $_GET['id'];
$sql = " DELETE FROM houses WHERE room_no = '$id'";
mysqli_query($conn,$sql);
$_SESSION['houseDeleted'] = "House ".$id." has been deleted successfull.";
header('location: houses.php');
 ?>