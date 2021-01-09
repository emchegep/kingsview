<?php
include_once('../config/connection.php');
 ?>
 <!DOCTYPE html>
 <html>
 <head>
<?php include_once('dist.php'); ?>
	<title>Notifications</title>
 </head>
 <body>
	<div class="container-fluid fixed-top py-2" style="background-color:#3b5765;color:#efefef;">
		<a href="index.php" class="nav-link text-light"><i class="fa fa-arrow-left fa-lg"> go back</i></a>
	</div>
 	<div class="col-sm-10 mx-auto pt-3 text-center">		
		<h2 >Customer Feedback</h2>	
 	</div>
 	<?php 
 	if (isset($_GET['ref'])) {
 		$status = $_GET['ref'];
 		$query = "UPDATE `feedback` SET `status`='read' WHERE `status`='$status' ORDER BY `feedback`.`sms_id` DESC LIMIT 1";
 		mysqli_query($conn,$query);	
 	}
		$sql = "SELECT *FROM `feedback` ORDER BY sms_id DESC;";
		$result = mysqli_query($conn,$sql);
		$total_rows = mysqli_num_rows($result);
		if (empty($total_rows)) {
			echo "<div class='col-sm-8 py-5 mx-auto text-center'>";
			echo "<p class='lead text-info'> No message sent </p>";
			echo "</div>";
		}
		while ($data = mysqli_fetch_assoc($result)) {
			$name = $data['sender_name'];
			$phone = $data['phone'];
			$email = $data['email'];
			$message = $data['message'];
			$time = $data['time'];
			$msgId = $data['sms_id'];
 	 ?>

 	<div class="col-sm-10 mx-auto">	
		<div class="card mb-3">
			<div class="card-header">
				<?php echo "Name: ".$name."<br>"; ?>
				<?php echo "Phone: ".$phone."<br>"; ?>
				<?php echo "Email: ".$email."<br>"; ?>
				<?php
				$contact= $name."-".$phone;
				?>

			</div>
			<div class="card-body">
				<p class="lead">
				<?php echo "Message: <br> ".$message; ?>

				</p>
			</div>
			<div class="card-footer d-flex justify-content-between">
				<p>
					<a href="messaging.php?contact=<?php echo $contact; ?>"><i class="fa fa-reply text-success"> Reply</i></a>
					<a class="text-danger pl-3" href="delete_message.php?id=<?php echo $msgId; ?>">Delete Message</a>	
				</p>
				<?php echo "Message sent on: ".$time; ?>

			</div>
		</div>
	</div>
	<?php 
	}
	mysqli_close($conn);
	?>
	
 </body>
 </html>