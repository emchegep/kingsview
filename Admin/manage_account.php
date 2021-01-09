<?php 
	session_start(); 
	include_once('../config/connection.php');
	$currentpassword = $password = "";
	$confirmpasswordError = $passwordError = "";
  if (isset($_SESSION['name'])) {
  	if(isset($_POST['change_password'])){
	  	$username = $_SESSION['name'];
	  	if (empty($_POST['currentpassword'])) {
	        $confirmpasswordError = "*Please enter the current password ";  
	    }
		else{
			//confirming whether the password match in the database
			if ($match = verify_password($conn, $username)) {
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
	    if (empty($passwordError) &&  empty($confirmpasswordError)) {
			$query = "UPDATE `admin` SET `password`='$newpassword' WHERE `username` = '$username';";
			$execute_query = mysqli_query($conn,$query);
			if ($execute_query) {
			session_start(); 
			$_SESSION['password_update'] = "You have successfully changed your password";
			header('location: index.php');
			}
		}
	}
}
else{
    //if session is not set redirect the user to the index page to login
    header('location:admin_login.php');
    exit();
  }
  function verify_password($conn,$username){
	 	$password = $_POST['currentpassword'];
	 	$id = $username;  
	 	$sql = "SELECT * FROM `admin` WHERE `username`='$id';";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);
        $db_password = $row['password'];
        // comparing the password enterered with db password
        if (password_verify($password, $db_password)) {
            $match = true;
            //return true is  the password exists
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
			background-color: #0003;
		}
		form{
			width: 80%;
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
</head>
<body>
	<div class="col-sm-7 mx-auto">
<div class="card" style="margin-top: 50px;">
	<div class="card-header d-flex justify-content-between">
		<h2>Manage Admin Account</h2>
		<a href="index.php" style="font-size:30px; color: grey; text-decoration: none">&times;</a>	
	</div>
        <div class="card-body">
		<form method="POST" action="">
			<h4 class="text-muted"><i class="fa fa-key"></i> Change Password</h4>
			<hr>	
			<div class="form-group textbox">
				<input class="form-control" placeholder="Current password" type="password" name="currentpassword" value="<?php echo $currentpassword;?>">	
				<i class="fas fa-lock fa-lg"></i>
				<?php 
				echo '<div class="text-danger">';
				echo $confirmpasswordError;
				echo '</div>';
				?>

			</div>
			<div class="form-group textbox">
				<input class="form-control" placeholder="New password" type="password" name="password" value="<?php echo $password;?>">	
				<i class="fas fa-lock fa-lg"></i>
				<?php 
				echo '<div class="text-danger">';
				echo $passwordError;
				echo '</div>';
				?>

			</div>
			<div class="form-group">
				<button class="btn btn-primary" type="submit" name="change_password">
					Save Changes
				</button>
			</div>
		</form>
	</div>
</div>
</div>
</body>
</html>
		