<?php 
 include_once('../config/connection.php');
if (isset($_GET['id'])) {
	$uploadId = $_GET['id'];
	$sql = "DELETE FROM customer_order WHERE upload_id = $uploadId";
	mysqli_query($conn,$sql);
	$sql = "UPDATE `uploads` SET `status`='Vaccant' WHERE `upload_id`= '$uploadId'";
        mysqli_query($conn, $sql);
	header('location: customer_dashboard.php');
}
 ?>