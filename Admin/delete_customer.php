<?php 
session_start();
include_once('../config/connection.php');
$id = $_GET['id'];
$sql = " DELETE FROM users WHERE customer_id = '$id'";
mysqli_query($conn,$sql);
$_SESSION['deleted'] = "Customer has been deleted successfull.";
header('location: index.php');
 ?>