<?php 
session_start();
include_once('../config/connection.php');
$usernameError = $passwordError = $success =  $fail = $username = "";
if (isset($_POST['submit'])) {
    if (empty($_POST['username'])) {
        $usernameError = "*Please enter your username ";  
    }
    else{
     $username = $_POST['username'];   
    }
    if (empty($_POST['password'])) {
        $passwordError = "*Please enter your password ";  
    }
    else{
        $password = $_POST['password'];
    }
   if(empty($usernameError) && empty($passwordError)){
        //select only the row which has the entered username
        $query = " SELECT *FROM `admin` WHERE `username` = '$username' ;";
        $result = mysqli_query($conn,$query);
        $row = mysqli_fetch_assoc($result);
         $db_username = $row['username'];
        $db_password = $row['password'];
        // comparing the password enterered with db password
        if (password_verify($password, $db_password)) {
            $_SESSION['name'] =  $db_username;
            header('location: index.php');
        }
        else{
            $_SESSION['fail'] = "*Username and password do not exists in our system"; 
        }
    }   
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once('dist.php'); ?>

    <link rel="stylesheet" href="../login/css/style.css">
    <title>Admin login</title>
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
                    <li class="nav-item">
                        <a class="nav-link" href="/kingsview" title="Back to Home" data-toggle="tooltip"><i class="fa fa-home"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/kingsview/home/book.php" title="Book" data-toggle="tooltip"><i class="fa fa-book"></i></a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/kingsview/login/login.php" title="Customer login" data-toggle="tooltip"> <i class="fa fa-user"></i> Customer</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>  
    <div class="modal-dialog">
        <div class="col-sm-11">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Admin Login</h2>  
                </div>
                <div class="modal-body">
                    <form action="" method="POST" >
                        <?php
                        //if login unsuccessful
                        if (isset($_SESSION['fail'])) {
                        echo '<div class ="alert alert-danger">';
                        echo $_SESSION['fail'];
                        echo '</div>';
                        unset($_SESSION['fail']);
                        }
                        ?>
                        <div class="form-group textbox">    
                            <input id="usrname" class="form-control" type="text" name="username" placeholder="User name" value="<?php echo $username; ?>">
                            <i class="fas fa-envelope fa-lg"></i>
                            <?php
                            echo '<div class="text-danger">';
                            echo $usernameError;
                            echo '</div>';
                            ?>

                        </div>
                        <div class="form-group textbox">    
                             <input id="pass" class="form-control" type="password" placeholder="Password" name="password">
                            <i class="fas fa-lock fa-lg"></i>
                            <?php
                            echo '<div class="text-danger">';
                            echo $passwordError;
                            echo '</div>';
                            ?>

                        </div>
                        <input id="chk1" type="checkbox" name="chk1" value="remembered">
                        <label for="chk1"><strong style="color: #0006">Remember me</strong></label>
                        <button class="btn btn-primary btn-block btn-lg" type="submit" name="submit"><i class="fa fa-sign-in-alt fa-lg"></i> Login</button>
                    </form>
                </div> 
            </div>
        </div>
    </div> 
</body>
</html>
