<?php 
include_once('../config/connection.php');
$fname =$sname = $email = $phone= $addr = $pass = $confirmpass = "";
$nameError =$snameError = $EmailError = $phoneError = $addrError = $passwordError = $confirmpassError =  "";
if (isset($_POST['register'])) {
	$code = "254";
	if(empty($_POST['fname'])){
	     $nameError = "*First name is required."; 
	}
   else{
		$fname =test($_POST['fname']) ;
	    if(!preg_match("/^[a-zA-Z]/",$fname)) {
	      $nameError = "*Invalid name. Only letters allowed.";
	    }	
	}
	if(empty($_POST['sname'])){
	     $snameError = "*Second name is required"; 
	}
   else{
		$sname =test($_POST['sname']) ;
	    if(!preg_match("/^[a-zA-Z]/",$sname)) {
	      $snameError = "*Invalid name. Only letters allowed.";
	    }	
	}
    if(empty($_POST['email'])){
	     $EmailError = "*Email address cannot be blank"; 
	}
	//confirming whether the email exixts in the database
	elseif ($exists = verify_email($conn)) {
        $EmailError = "*Sorry, the email already exists in our system. Please provide a different email id.";  
    }
	else{
		$email =test($_POST['email']) ;
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$EmailError = "*Invalid Email";
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
	if(empty($_POST['addr'])){
	     $addrError = "*Address is required."; 
	}
   else{
		$addr =test($_POST['addr']);	
	}
    if(empty($_POST['password'])){
         $passwordError = "*Sorry, the password field cannot be blank"; 
    }
    elseif(empty($_POST['confpass'])){
         $confirmpassError = "*Sorry, you need to confirm your password"; 
    }
    else{
    	$pass =test($_POST['password']);
		$confirmpass =test($_POST['confpass']);
		//confirming passwords
		if($pass != $confirmpass) {
			$passwordError = "*Passwords do not match";  
		}
	}
    //insert record into the database  
    if (empty($nameError) && empty($EmailError) && empty($phoneError) && empty($passwordError) && empty($confirmpassError)) {
    	$newphone = $code.substr($phone, 1);
    	$pass_hash = password_hash($pass, PASSWORD_DEFAULT);
        $query = "INSERT INTO `users`(`first_name`,`second_name`, `email`,`postal_address`, `password`, `phone`) 
        VALUES ('$fname','$sname','$email','$addr','$pass_hash','$newphone')";
        $execute_query = mysqli_query($conn,$query);

        if ($execute_query) {
        	session_start();
          $_SESSION['msg'] = "Account is successfully created.";
        }
        else{
        	echo mysqli_error($conn);
        }
    }
}
 function verify_email($conn){
        $email_to_verify = $_POST['email'];
        $query = " SELECT `email` FROM `users`;";
        $result = mysqli_query($conn,$query);
        while ($row = mysqli_fetch_assoc($result)) {
            $db_email = $row['email'];
             // cheking if the email already exists in the database
            if ($db_email == $email_to_verify) {
              $exists = true;
              return $exists;
            }
        }
    }
function test($data) {
  $filtered_data = trim($data);
  $filtered_data = stripslashes($data);
  $filtered_data = htmlspecialchars($data);
  return $filtered_data;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once('../Admin/dist.php'); ?>

	<!-- custom css -->
	<link rel="stylesheet" href="css/style.css">
	<title>Homeview | Create Account</title>
	<script>
		$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip();   
		});
		function trancateName() {
			var fname = document.getElementById("fname");
			fname.value = fname.value.split(" ")[0];
			var sname = document.getElementById("sname");
			sname.value = sname.value.split(" ")[0];
			
		}
	</script>
</head>
<body>
	<nav class="navbar navbar-expand-md fixed-top" style="background-color: teal"> 
        <div class="container-fluid header">
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarcollapse" ><i class="fa fa-bars"></i></button>
            <div class="collapse navbar-collapse justify-content-between"    id="navbarcollapse">
                  <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="/kingsview" title="Back to Home" data-toggle="tooltip"><i class="fa fa-home"></i></a></li>
                    <li class="nav-item"><a class="nav-link" href="/kingsview/home/book.php" title="Book" data-toggle="tooltip"><i class="fa fa-book"></i></a></li>
                </ul>
                <ul class="navbar-nav">
                	<li class="nav-item"><a class="nav-link" href="register.php" style="color:#004">Sign up</a></li>
                	<li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>   
		<?php if (isset($_SESSION['msg'])) { ?>
		<div class="container msg">
			<?php 
			echo "<p>".$_SESSION['msg'].
			"Please use your email and password to <a href='login.php'> Login</a> </p>";
			unset($_SESSION['msg']);
			?>
		</div>
	<?php } ?>
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="modal-title">Create a Free Account</h2>  
			</div>
			<div class="modal-body">
				<form action="" method="POST" >
					<div class="form-row">
					<div class="form-group textbox col-md-4">	
					<input id="fname" onkeyup="trancateName()" class="form-control" placeholder="First name" type="text" name="fname" value="<?php echo $fname; ?>">
					<i class="fas fa-user fa-lg"></i>
					<?php 
						echo '<div class="text-danger" >';
						echo $nameError;
						echo '  </div>';
					?>
					</div>
					<div class="form-group textbox col-md-4">	
					<input id="sname" onkeyup="trancateName()" class="form-control" placeholder="Last name" type="text" name="sname" value="<?php echo $sname; ?>">
					<i class="fas fa-user fa-lg"></i>
					<?php 
						echo '<div class="text-danger" >';
						echo $snameError;
						echo '  </div>';
					?>
					</div>
					<div class="form-group textbox col-md-4">	
					<input class="form-control " type="tel" name="phone" placeholder="Phone number" value="<?php echo $phone; ?>" maxlength="10">
					<i class="fas fa-phone fa-lg"></i>
					<?php 
						echo '<div class="text-danger">';
						echo $phoneError;
						echo '  </div>';
					?>
					</div>
					</div>
					<div class="form-row">
					<div class="form-group textbox col-md-6">	
					<input class="form-control " placeholder="Email Address" type="text" name="email" value="<?php echo $email; ?>">
					<i class="fas fa-envelope fa-lg"></i>
					<?php 
						echo '<div class="text-danger" >';
						echo $EmailError;
						echo '  </div>';
					?>
					</div>
					<div class="form-group textbox col-md-6">	
					<input class="form-control " placeholder="Postal Address" type="text" name="addr"  value="<?php echo $addr; ?>">
					<i class="fas fa-home fa-lg"></i>
					<?php 
						echo '<div class="text-danger" >';
						echo $addrError;
						echo '  </div>';
					?>
					</div>
					</div>
					<div class="form-row">
					<div class="form-group textbox col-md-6">	
					<input class="form-control " placeholder="Password" type="password" name="password">
					<i class="fas fa-lock fa-lg"></i>
					<?php 
						echo '<div class="text-danger" >';
						echo $passwordError;
						echo '  </div>';
					?>
					</div>
					<div class="form-group textbox col-md-6">	
					<input class="form-control " placeholder="Confirm Password" type="password" name="confpass">
					<i class="fas fa-lock fa-lg"></i>
					<?php 
						echo '<div class="text-danger" >';
						echo $confirmpassError;
						echo '  </div>';
					?>
					</div>
					</div>
				<div class="form-group text-center">
				<button class="btn btn-primary btn-lg" type="submit" name="register"><i class="fa fa-sign-in-alt fa-lg"></i> Register Now</button>
				</div>
				<p class="text-center" id="create">Already have an Account? Click <a href="login.php">Login</a></p>
				</form>
			</div> 
		</div>
	</div>
	<div class="container copyright">
		   <p>
		        &copy; <?php echo date('Y'); ?> KingsView Apartment ltd. All rights Reserved. <br> Designed by Peter Chege
		   </p>
		<span>
			<a href="#" data-toggle = "tooltip" title="social-facebook"><i class="fab fa-facebook-f social-icon fb"></i></a>
			<a href="#" data-toggle = "tooltip" title="social-twitter"><i class="fab fa-twitter social-icon tt"></i></a>
			<a href="#" data-toggle = "tooltip" title="social-linkedin"><i class="fab fa-linkedin social-icon wt"></i></a>
			<a href="#" data-toggle = "tooltip" title="youtube"><i class="fab fa-youtube social-icon tb"></i></a> 
		</span>
	</div>
</div>		
</body>
</html>