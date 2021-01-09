<?php 
include_once('../config/connection.php');
session_start();
$name =$phone =$email = $customer_id ="";
if (isset( $_SESSION['customer_id'])){
$customer_id =  $_SESSION['customer_id'];
$sql = "SELECT *FROM `users` WHERE `customer_id`= '$customer_id';";
$result = mysqli_query($conn,$sql);
$data = mysqli_fetch_assoc($result);
$name = $data['first_name']." ".$data['second_name'];
$fname = $data['first_name'];
$phone = $data['phone'];
$email = $data['email'];

$sql = "
	SELECT * FROM
	users
	INNER JOIN customer_order ON customer_order.customer_id = users.customer_id
	INNER JOIN uploads ON customer_order.upload_id = uploads.upload_id
	INNER JOIN houses ON uploads.room_no = houses.room_no 
	WHERE users.customer_id = $customer_id;
";
$result = mysqli_query($conn,$sql);
$image_folder = "/kingsview/Admin/";
$data = mysqli_fetch_assoc($result);
$order_id = $data['order_id'];
$upload_id = $data['upload_id'];
$time = $data['time'];
$path = $image_folder.$data['path'];
$price = $data['price'];
$type = $data['type'];
$roomNo = $data['room_no'];
$general_features = $data['general_features'];
$internal_features = $data['internal_features'];
}
else{
header('location: /homeview/login/login.php');
}
?>
<!DOCTYPE html>
<html>
<head>
<?php include_once('../Admin/dist.php'); ?>
	<title>Customer View</title>
	<style>
		body{
		    font-family: sans-serif;
		    padding-top: 50px;
		    padding-bottom: 70px;
		    background-color:#fafafa ;
		    }
		    .sidebar-fixed li{
		    padding: 10px;
		    border-bottom: 1px solid lightgray;
		    font-size: 14px;
		    }
		    .sidebar-fixed li a{
		    color: #757575;
		    }
		    .sidebar-fixed li a:hover{
		    color: #000;
		    transition: 0.3s;
		    }
		    .sidebar-fixed{
		    background-color: #e4eaec;
		    height: 100%;
		    width: 220px;
		    position: fixed;
		    left: 0;
		    z-index: 1;
		    padding-bottom: 140px;
		    overflow-y: scroll;
	    }
		@media (min-width: 768px) {
			.navbar-toggler {
			display: none;
			}
			.sidebar-fixed{
				overflow-y: hidden;
			}
		}
		@media (max-width: 768px) {
			.welcome{
				display: none;
			}
			.logout{
				display: none;
			}
			h2,h4,p{
				font-size: 16px;
			}
			.btn{
				font-size: 14px !important;
				padding: 5px !important;
			}
			.card-body img{
				height: 180px !important;
				width: 280px;
			}
			.greeting{
				font-size: 12px !important;
			}
			table{
				font-size: 12px !important;
			}
		}
		@media (max-width: 568px) {
			p{
				font-size: 14px !important;
			}
		}
	</style>
	<script type="text/javascript">
	  //Invoke Jquery
	  $(document).ready(function(){
	      $('[data-toggle="tooltip"]').tooltip();   
	  });
	</script>
</head>
<body>
<div class="container-fluid d-flex justify-content-md-between fixed-top" style="background-color:#3b5765;color:#efefef">
 
 <p class="pt-2">
 	<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidebar" ><i class="fa fa-bars text-light"></i></button>
 	 <a href="/kingsview" title="back to home" class="text-light " data-toggle='tooltip'><i class="fa fa-home pr-3 pt-3"></i></a>
 </p>
  
  <p class="pt-3 welcome">
  	Welcome, <?php echo $fname; ?>
  </p>
  <p class="pt-3 logout">
  	<a href="logout.php" class="text-light px-3"><i class="fa fa-sign-out-alt"></i> Sign out</a></p>
</div>
 <div class="container-fluid">
  <div class="row">
    <nav class="col-md-3 d-md-block collapse navbar-collapse" id="sidebar">
      <div class="sidebar-fixed" >
    <ul class="nav flex-column">
		<li class="text-info py-3" style="font-size:24px;">
        <i class="fa fa-tachometer-alt fa-lg"> </i>Dashboard
      </li>
    	 <li class="nav-item">
       <img class="img-fluid" src="/kingsview/images/icon.png" alt="" style="height: 80px; width: 80px"> <br>
       <?php
       	echo "Customer Id: ".$customer_id."<br>"; 
		echo "Name: ".$name."<br>"; 
		echo "Phone: ".$phone."<br>"; 
		echo "Email: ".$email."<br>";
       ?>
      </li>
      <li class="nav-item">
        <a class="nav-link " href="customer_dashboard.php"><i class="fa fa-shopping-cart fa-lg"></i> Your Requests</a>
      </li>
       <li class="nav-item">
        <a class="nav-link " href="edit_account.php?id=<?php echo $customer_id; ?>"><i class="fa fa-user-plus fa-lg"></i> Manage your Account</a>
      </li>
       <li class="nav-item">
        <a class="nav-link " href="comment.php?ref=<?php echo $name."-"."&id=".uniqid(); ?>"><i class="fa fa-comments fa-lg"></i> Send us your Feedback</a>
      </li>
      <li class="nav-item">
        <a class="nav-link " href="logout.php"><i class="fa fa-sign-out-alt fa-lg"></i> Logout</a>
      </li>
    </ul>
  </div>
</nav>
 <div class="col-md-8 mx-auto">
	<div class="mx-auto">		
	<h2 class="text-info text-center py-3 header1">Customer Dashboard</h2>	
	</div>
	<?php 
	if (isset($_SESSION['password_update'])) {
	echo "<div class='col-sm-10 mx-auto alert alert-success' >";
	echo '<i class="fa fa fa-check text-success" style="font-size:18px"></i> '.$_SESSION['password_update'];
	unset($_SESSION['password_update']);
	echo "</div";
	
	}
	?>

	<div class="card ">
		<?php if (mysqli_num_rows($result)>0) {?>
		<div class="card-header">	
			<h4 class="text-muted">Here is what you have already booked.</h4>	
		</div>
		<div class="card-body">
			<div class="row">
				<div class='col-md-5 mr-auto pb-4'>
					<h4 class="text-muted"><?php echo $type; ?></h4>
	                <?php echo "<img src = '{$path}' class='img-fluid' style='height:240px'/>"; ?>	
	            </div>
	            <div class="col-md-7">
	            	<p class="lead">
	            		<i class="fa fa-star-half"></i><strong>General Features <br></strong>
						<?php echo $general_features; ?>
	            	</p>
	            	<p class="lead">
	            		<i class="fa fa-star-half"> </i><strong>Internal Features <br></strong>
						<?php echo $internal_features; ?>
	            	</p>
	            </div>
        	</div>
        	<div class="col-md-12">
			<table class="table table-bordered table-secondary table-sm table-responsive">
				<thead>
					<tr class="text-center" style="background-color:#ffe1c4; color: #757575">
						<th colspan="6">ORDER DETAILS</th>
					</tr>
					<tr >
						<th>Order No.</th>
						<th>Customer Id</th>
						<th>House Type</th>
						<th>Room No.</th>
						<th>Price</th>
						<th>Date/Time</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo $order_id; ?></td>
						<td><?php echo $customer_id; ?></td>
						<td><?php echo $type; ?></td>
						<td><?php echo $roomNo; ?></td>
						<td><?php echo $price; ?></td>
						<td><?php echo $time; ?></td>
					</tr>
				</tbody>
			</table>
			<a class="pl-3" href="../pdf/order_pdf.php?id=<?php echo $customer_id; ?>">
				<button class="btn btn-outline-secondary my-1"><i class="fa fa-file-pdf fa-lg text-danger"></i> Save PDF</button>
			</a>
			<a class="pl-3" href="../excel/excel_report.php?id=<?php echo $customer_id; ?>">
				<button class="btn btn-outline-secondary my-1"><i class="fa fa-file-excel fa-lg text-success"></i> Save Excel</button>
			</a>
			<a class="pl-3" href="delete_order.php?id=<?php echo $upload_id; ?>">
				<button class="btn btn-outline-danger my-1"><i class="fa fa-trash-alt fa-lg"></i> Delete this request</button>
			</a>	
		</div>
		</div>
		<?php } 
		else{
		echo "<div class='col-sm-8 py-5 mx-auto text-center'>";
		echo "<p class='lead text-info greeting'>Hi ".$fname.", you haven't booked anything yet or maybe your request was rejected. <br> Once you make some bookings they will appear here.</p>";
		echo "<a class='btn btn-outline-success ' href='book.php'> Book Now </a>";
		echo "</div>";
		}
		?>
		</div>
		</div>
	</div>
</div>
<div class="container-fluid  text-center bg-light mt-5">
    <div class="text-secondary py-2 small"> 
        &copy; <?php echo date('Y'); ?> KingsView Apartment ltd. <br> All rights Reserved. Designed by Peter Chege <br>
        Privacy Policy - <a href="#">Terms & Conditions</a>
    </div>
</div> 	
</body>
</html>