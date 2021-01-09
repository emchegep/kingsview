<?php
session_start();
include_once('../config/connection.php');
$emailError = $passwordError = $email = "";
if (isset($_SESSION['customer_id'])) {
	header('location: /kingsview/home/customer_dashboard.php');
}
if (isset($_POST['login'])) {
    if (empty($_POST['email'])) {
        $emailError = "*Please enter your username ";  
    }
    else{
    	$email = $_POST['email'];
    	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$emailError = "*Invalid Email";
		}
    }
    if (empty($_POST['password'])) {
        $passwordError = "*Please enter your password ";  
    }
    else{
    	$password = $_POST['password'];
    }
   if(empty($emailError) && empty($passwordError)){
    	//select only the row which has the supplied email
        $query = " SELECT `customer_id`,`email`, `password` FROM `users` WHERE `email` = '$email' ;";
        $result = mysqli_query($conn,$query);
        $row = mysqli_fetch_assoc($result);
        $db_password = $row['password'];
        $customer_id = $row['customer_id'];
        // comparing the password enterered with db password
        if (password_verify($password, $db_password)) {
            $_SESSION['customer_id'] =  $customer_id;
        	header('location: /kingsview/home/customer_dashboard.php');
        }
        else{
            $_SESSION['error'] = "*Username and password do not exists in our system"; 
        }
    }     
}       
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once('../Admin/dist.php'); ?>

	<!-- custom css -->
	<link rel="stylesheet" href="css/style.css">
	<title>KingsView | Login</title>
	<script>
		$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip();   
		});
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
                	<li class="nav-item"><a class="nav-link" href="register.php">Sign up</a></li>
                	<li class="nav-item"><a class="nav-link" href="login.php" style="color:#004">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>   
	<div class="modal-dialog">
		<div class="col-sm-11">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title">Please Login to continue</h2>  
				</div>
				<div class="modal-body">
					<form action="" method="POST" >
						<?php
						if(isset($_SESSION['error'])) {
						echo '<div class="alert alert-danger">';
						echo $_SESSION['error'];
						echo '  </div>';
						unset($_SESSION['error']);
						} 
						?>
						<div class="form-group textbox">	
							<input class="form-control " placeholder="Email Address" type="text" name="email"value="<?php echo $email; ?>">
							<i class="fas fa-envelope fa-lg"></i>
							<?php
							echo '<div class="text-danger">';
							echo $emailError;
							echo '</div>';
							?>
						</div>
						<div class="form-group textbox">	
							<input class="form-control " placeholder="Password" type="password" name="password">
							<i class="fas fa-lock fa-lg"></i>
							<?php
							echo '<div class="text-danger">';
							echo $passwordError;
							echo '</div>';
							?>
						</div>
						<input id="chk1" type="checkbox" name="chk1" value="remembered">
						<label for="chk1"><strong style="color: #0006">Remember me</strong></label>
						<button class="btn btn-primary btn-block btn-lg" type="submit" name="login"><i class="fa fa-sign-in-alt fa-lg"></i> Login</button>
						<p id="create">
						Don't have an Account?<a href="register.php"> Create new</a>
						</p>
					</form>
				</div> 
			</div>
		</div>
	</div>
	<div class="container copyright">
		<p>
		 &copy; <?php echo date('Y'); ?> KingsView Apartment ltd. All rights Reserved. <br> Designed by Peter Chege
		</p>
		<span>
			<a href="#" data-toggle = "tooltip" title="soicial-facebook"><i class="fab fa-facebook-f social-icon fb"></i></a>
			<a href="#" data-toggle = "tooltip" title="social-twitter"><i class="fab fa-twitter social-icon tt"></i></a>
			<a href="#" data-toggle = "tooltip" title="social-Linkedin"><i class="fab fa-linkedin social-icon wt"></i></a>
			<a href="#" data-toggle = "tooltip" title="social-youtube"><i class="fab fa-youtube social-icon tb"></i></a> 
		</span>
	</div>
<script src="/homeview/dist/js/jquery.js" ></script>
<script src="/homeview/dist/js/popper.min.js" ></script>
<script src="/homeview/dist/js/bootstrap.min.js" ></script>
</body>
</html>