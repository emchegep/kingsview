<?php
session_start();
include_once('../config/connection.php');
$fname = $sname = $phone = $email = $addr = $chk = $currentpassword = $password = "";
$fnameError = $snameError = $emailError = $addrError = $confirmpasswordError = $passwordError = $phoneError =  "";
if(isset($_POST['update'])){
	$customer_id =  $_SESSION['customer_id'];
 	$code = "254";
	if(empty($_POST['fname'])){
	     $fnameError = "*name cannot be blank."; 
	}
	else{
		$fname =test($_POST['fname']) ;
	    if(!preg_match("/^[a-zA-Z ]*$/",$fname)) {
	      $fnameError = "*Invalid name. Only letters and white space allowed.";
	    }	
	}
	if(empty($_POST['sname'])){
	     $snameError = "*name cannot be blank."; 
	}
	else{
		$sname =test($_POST['sname']) ;
	    if(!preg_match("/^[a-zA-Z ]*$/",$sname)) {
	      $snameError = "*Invalid name. Only letters and white space allowed.";
	    }	
	}
	 if (empty($_POST['email'])) {
        $emailError = "*Email is required";  
    }
    else{
    	$email = $_POST['email'];
    	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$emailError = "*Invalid Email";
		}
    }
    if(empty($_POST['addr'])){
	     $addrError = "*Address is required."; 
	}
   else{
		$addr =test($_POST['addr']);	
	}
	if (isset($_POST['checkbox1'])) {
		if (empty($_POST['currentpassword'])) {
		$confirmpasswordError = "*Please enter the current password ";  
		}
		else{
		//confirming whether the password match in the database
		if ($match = verify_password($conn, $customer_id )) {
		$currentpassword = $_POST['currentpassword'];
		}
		else{
		$confirmpasswordError = "*Wrong password";
		}
		}
		if (empty($_POST['password'])) {
		$passwordError = "*Please enter the new password ";  
		}
		else{
		$password = $_POST['password'];
		$newpassword = password_hash($password, PASSWORD_DEFAULT);
		}
	}
	if(empty($_POST['phone'])) {
	    $phoneError = "*Phone number cannot be blank.";  
	}
	else{
		$phone =test($_POST['phone']);
		if(preg_match("/[^0-9]/",$phone)) {
		$phoneError = "*Invalid phone number. Only numbers allowed.";  
			}
		elseif(strlen($phone)< 10) {
		$phoneError = "*Sorry, the phone number should contain 10 characters.";  
		}
	}
	if (empty($fnameError) && empty($snameError) && empty($addrError) && empty($emailError) && empty($phoneError)) {
		if(strlen($phone)>10){
			$newphone = $code.substr($phone, 3); 
		}
		else{
			$newphone = $code.substr($phone, 1);
		}
		$query = "UPDATE `users` SET `first_name`='$fname',`second_name`='$sname',`email`='$email',`phone`='$newphone',`postal_address`='$addr' WHERE `customer_id` = '$customer_id';";
		$execute_query = mysqli_query($conn,$query);
		if ($execute_query) {
			$_SESSION['updated'] = "Great! You have update your basic info.";

		}
	}
	if (empty($passwordError) &&  empty($confirmpasswordError) && !empty($_POST['checkbox1'])) {
		$query = "UPDATE `users` SET `password`='$newpassword' WHERE `customer_id` = '$customer_id';";
		$execute_query = mysqli_query($conn,$query);
		if ($execute_query) {
			$_SESSION['password_update'] = "You have changed your password successfully.";
			header('location: customer_dashboard.php');
		}
	}
}
else{
	if (isset( $_GET['id'])) {
	$update_id = $_GET['id'];	
	$sql = "SELECT *FROM `users` WHERE `customer_id` = '$update_id';";
		$result = mysqli_query($conn,$sql);
		$data = mysqli_fetch_array($result);
		$fname = $data['first_name'];
		$sname = $data['second_name'];
		$email = $data['email'];
		$phone = $data['phone'];
		$addr = $data['postal_address'];
	}
	else{
		exit('Sorry, You do not have the right privilege to view the content of this page. Please login as Admin to view the page content.');
	}	
}

function test($data) {
  $filtered_data = trim($data);
  $filtered_data = stripslashes($data);
  $filtered_data = htmlspecialchars($data);
  return $filtered_data;
}
 function verify_password($conn,$customer_id ){
	 	$password = $_POST['currentpassword'];
	 	$id = $customer_id ;  
	 	$query = " SELECT `customer_id`, `password` FROM `users` WHERE `customer_id` = '$id' ;";
        $result = mysqli_query($conn,$query);
        $row = mysqli_fetch_assoc($result);
        $db_password = $row['password'];
        // comparing the password enterered with db password
        if (password_verify($password, $db_password)) {
            $match = true;
             return $match;
        }
    }
 ?>
<!DOCTYPE html>
<html>
<head>
<?php include_once('../Admin/dist.php'); ?>

	<title>Update customer info</title>
	<style>
		body{
			background-color:#0003;
		}
		form{
			width: 70%;
			margin: auto;
		}
		.textbox{
			position: relative;
		}
		.textbox input{
			padding-left: 45px;
			height: 40px;
		}
		.textbox i{
			position: absolute;
			left:0;
			top: 0;
			padding: 9px;
			color: darkgray;
			height: 40px;
			width: 40px;
		}
		.textbox input:focus{
			border-color: dodgerblue;
		}
		.textbox input:focus + i{
			color: #fff;
			background-color: dodgerblue;
		}
	</style>
	<script>
		function checkPassword(x){
			var chk = x;
			document.getElementById('password').disabled =! chk.checked;
			document.getElementById('currentpassword').disabled =! chk.checked;	
		}
	</script>
</head>
<body>
<div class="container-fluid fixed-top py-2" style="background-color:#3b5765;color:#efefef;">
<a href="customer_dashboard.php" class="nav-link text-light"><i class="fa fa-arrow-left fa-lg"> go back</i></a>
</div>
<div class="col-sm-7 mx-auto">
<div class="card" style="margin-top: 150px;">
	<div class="card-header d-flex justify-content-between">
		<h2>Manage your Account</h2>
		<a href="customer_dashboard.php" style="font-size:30px; color: grey;">&times;</a>	
	</div>
        <div class="card-body">
		<form method="POST" action="">
			<p class="Lead">Manage your Basic Info</p>
			<hr>
			<div class="form-group textbox">
				<input class="form-control " placeholder="First name" type="text" name="fname" value="<?php echo $fname;?>">	
				<i class="fas fa-user fa-lg"></i>
				<?php 
				echo '<div class="text-danger">';
				echo $fnameError;
				echo '</div>';
				?>

			</div>
			<div class="form-group textbox">
				<input class="form-control " placeholder="Second name" type="text" name="sname" value="<?php echo $sname;?>">	
				<i class="fas fa-user fa-lg"></i>
				<?php 
				echo '<div class="text-danger">';
				echo $snameError;
				echo '</div>';
				?>

			</div>
			<div class="form-group textbox">
				<input class="form-control" type="text" name="phone" placeholder="Phone number" value="<?php echo $phone;?>" maxlength="10">	
				<i class="fas fa-phone fa-lg"></i>
				<?php 
				echo '<div class="text-danger">';
				echo $phoneError;
				echo '</div>';
				?>

			</div>
			<div class="form-group textbox">
				<input class="form-control" placeholder="Email Address" type="text" name="email" value="<?php echo $email;?>">	
				<i class="fas fa-envelope fa-lg"></i>
				<?php 
				echo '<div class="text-danger">';
				echo $emailError;
				echo '</div>';
				?>

			</div>
			<div class="form-group textbox">
				<input class="form-control" placeholder="Postal Address" type="text" name="addr" value="<?php echo $addr;?>">	
				<i class="fas fa-home fa-lg"></i>
				<?php 
				echo '<div class="text-danger">';
				echo $addrError;
				echo '</div>';
				?>

			</div>
			<?php 
			if (isset($_SESSION['updated'])) {
			echo "<div class='alert alert-success' >";
			echo '<i class="fa fa fa-check text-success" style="font-size:18px"></i> '.$_SESSION['updated'];
			unset($_SESSION['updated']);
			echo "</div";

			}
			?>

			<hr class="mt-5">
			<input id="chk" type="checkbox" name="checkbox1" onclick="checkPassword(this)">
			<label class="form-check-inline" for="chk"> Change Password</label>	
			<div class="form-group textbox">
				<input id="currentpassword" class="form-control" placeholder="Current password" type="password" name="currentpassword" disabled value="<?php echo $currentpassword;?>">	
				<i class="fas fa-lock fa-lg"></i>
				<?php 
				echo '<div class="text-danger">';
				echo $confirmpasswordError;
				echo '</div>';
				?>

			</div>
			<div class="form-group textbox">
				<input id="password" class="form-control" placeholder="New password" type="password" name="password" disabled value="<?php echo $password;?>">	
				<i class="fas fa-lock fa-lg"></i>
				<?php 
				echo '<div class="text-danger">';
				echo $passwordError;
				echo '</div>';
				?>

			</div>
			<div class="form-group">
				<button class="btn btn-primary" type="submit" name="update">
					Save Changes
				</button>
			</div>
		</form>
	</div>
</div>
</div>
</body>
</html>
