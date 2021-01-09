<?php
include_once('../config/connection.php');
 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<?php include_once('dist.php'); ?>
 	<title>Admin | Customer Requests</title>
 </head>
 <body>
	<div class="container-fluid sticky-top py-2" style="background-color:#3b5765;color:#efefef;">
	<a href="index.php" class="nav-link text-light"><i class="fa fa-arrow-left fa-lg"> go back</i></a>
	</div>
 	<div class="col-sm-10 mx-auto pt-3 text-center">		
		<h2 class="display-4">House Requests</h2>	
 	</div>
 	<?php 
		$sql = "
			SELECT *FROM
			users
			INNER JOIN customer_order ON customer_order.customer_id = users.customer_id
			INNER JOIN uploads ON customer_order.upload_id = uploads.upload_id
			INNER JOIN houses ON uploads.room_no = houses.room_no 
		";
		$result = mysqli_query($conn,$sql);
		$total_rows = mysqli_num_rows($result);
		
		if (empty($total_rows)) {
			echo "<div class='col-sm-8 py-5 mx-auto text-center'>";
			echo "<p class='lead text-info'> No bookings have been made yet </p>";
			echo "</div>";
		}
		$x = 0;
		while ($data = mysqli_fetch_assoc($result)) {
			$name = $data['first_name']." ".$data['second_name'];
			$phone = $data['phone'];
			$email = $data['email'];
			$customer_id = $data['customer_id'];
			$time = $data['time'];
			$price = $data['price'];
			$type = $data['type'];
			$roomNo = $data['room_no'];
			$general_features = $data['general_features'];
			$internal_features = $data['internal_features'];
			$order_id = $data['order_id'];
			$upload_id = $data['upload_id'];
 	 ?>

 	<div class="col-sm-10 mx-auto">	
		<div class="card mb-3">
			<div class="card-header">
				<?php echo "CustomerNo. #".$customer_id."<br>"; ?>
				<?php echo "Name: ".$name."<br>"; ?>
				<?php echo "Phone: ".$phone."<br>"; ?>
				<?php echo "Email: ".$email."<br>"; ?>
				<?php
				$contact= $name."-".$phone;
				?>
			</div>
			<div class="card-body">
				<p >
				<?php echo "<strong>Type - </strong>".$type."<br> "; ?>
				<?php echo "<strong>Room No.</strong> ".$roomNo."<br> "; ?>
				<?php echo "<strong>Features - </strong>".$general_features.". ".$internal_features."<br>"; ?>
				<?php echo "<strong class='text-info'>KES ".$price."</strong>"; ?>
				</p>
				<div class="col-sm-6">
					<table class="table table-dark table-bordered table-sm">
						<thead>
							<tr>
								<th>#</th>
								<th>Order Id.</th>
								<th>Date</th>
							</tr>
							
						</thead>
						<tbody>
							<tr>
								<td><?php echo $x+=1; ?></td>
								<td><?php echo $order_id; ?></td>
								<td><?php echo $time; ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="card-footer">
				<p>
				<a class="text-succes" href="messaging.php?contact=<?php echo $contact; ?>"><i class="fa fa-reply text-success"> Send a message</i></a>
				<a class="text-danger pl-3" href="delete_request.php?id=<?php echo $upload_id; ?>">Revoke request</a>	
				</p>
			</div>
		</div>
	</div>
	<?php 
	}
	mysqli_close($conn);
	?>
 </body>
 </html>