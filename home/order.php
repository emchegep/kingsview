<?php 
include_once('../config/connection.php');
session_start();
$type =$price  = $general_features = $internal_features = $time= "";
$uploadId = $_GET['id'];
if (isset( $_SESSION['customer_id']) && isset($_GET['id']) && !empty(isset($_GET['id']))){
	$customer_id =  $_SESSION['customer_id'];
	$uploadId = $_GET['id'];
	date_default_timezone_set("Africa/Nairobi");
	$time = date('Y-m-d H:i:s');
	echo $time;
	$sql = "INSERT INTO `customer_order`(`customer_id`, `upload_id`, `time`) 
	VALUES ('$customer_id','$uploadId','$time')";
	$result = mysqli_query($conn, $sql);
	$sql = "UPDATE `uploads` SET `status`='Booked' WHERE `upload_id`= '$uploadId'";
	mysqli_query($conn, $sql);
	unset($_GET['id']);
	header('location: /kingsview/home/customer_dashboard.php');
	mysqli_close($conn);
}
else{
	header('location: /kingsview/login/login.php');
}
?>