<?php
include_once('../config/connection.php');
session_start(); 
$username = $_SESSION['name'];
 if (isset( $username)) {
	$name = $id = $email = "";
	if (isset( $_GET['id']) && !empty($_GET['id'])) {
		$id = trim($_GET['id']);	
		$sql = "SELECT `customer_id`, `first_name`,`second_name`, `email`, `phone`, `postal_address` FROM `users` WHERE `customer_id` = '$id';";
			$result = mysqli_query($conn,$sql);
			$data = mysqli_fetch_array($result);
			$name = $data['first_name']." ".$data['second_name'];
			$email = $data['email'];
			$phone = $data['phone'];
			$addr = $data['postal_address'];

			mysqli_close($conn);
		}
		else{
			echo "<div class='col-8 mx-auto mt-5 alert alert-danger'>";
			echo "<h3 class='alert-heading'>Oops!</h3>";
			echo "No customer has been Selected.";
			echo "</div>";
			exit();
		}
}
else{
	echo "<div class='col-8 mx-auto mt-5 alert alert-danger'>";
	echo "Sory you can't view the content of this page. You need to first <a class='alert-link' href='index.php'> Login </a>as Admin to fix this problem.";
	echo "</div>";
	exit();
}
 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<?php include_once('dist.php'); ?>
 	<title>Customer View</title>
 </head>
 <body>
 	<div class="container">
 		<div class="modal-dialog  modal-lg p-3 mt-sm-5">
 			<div class="modal-content">
	 			<div class="modal-header ">
	 				<h2>Customer Information</h2>
	 			</div>
				<div class="modal-body">
					<h3>
						<?php echo "Customer # ".$id; ?>
					</h3>
					<p class="lead">
						<?php echo "Name: ".$name; ?>
						<?php echo "<br> "; ?>
						<?php echo "Phone: ".$phone; ?>
						<?php echo "<br>"; ?>
						<?php echo "Email: ".$email; ?>
						<?php echo "<br>"; ?>
						<?php echo "Postal Address: ".$addr; ?>
					</p>
				</div>
				<div class="modal-footer">
					<a href="index.php" style="font-size:20px;">Back</i></a>	
				</div>
 			</div>	
 		</div>
 	</div>
 </body>
 </html>