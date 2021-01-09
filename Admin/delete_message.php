<?php 
 include_once('../config/connection.php');
if (isset($_GET['id'])) {
	$msgId = $_GET['id'];
	$sql = "DELETE FROM feedback WHERE sms_id = $msgId";
	mysqli_query($conn,$sql);
	header('location: feedback.php');
}
 ?>