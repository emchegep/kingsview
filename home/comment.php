<?php 
 session_start();
// php code to process the feedback form
include_once('../config/connection.php');
$message  = $messageErr =  "";
if (isset( $_SESSION['customer_id']) && isset( $_GET['ref']) && !empty($_GET['ref'])){
    if (isset($_POST['send'])) {
        $customer_id =  $_SESSION['customer_id'];  
        $sql = "SELECT *FROM `users` WHERE `customer_id`= '$customer_id';";
        $result = mysqli_query($conn,$sql);
        $data = mysqli_fetch_assoc($result);
        $name = $data['first_name']." ".$data['second_name'];
        $phone = $data['phone'];
        $email = $data['email'];
        if (empty($_POST['message'])) {
            $messageErr = "*message cannot be blank";
        }
        else{
            $message = test($_POST['message']);   
        }
        if (empty($messageErr) ) {
            date_default_timezone_set("Africa/Nairobi");
            $time = date('Y-m-d H:i:s');
            $sql = "INSERT INTO `feedback`(`sender_name`, `phone`, `email`, `message`, `time`)
            VALUES ('$name','$phone','$email','$message','$time')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $_SESSION['msg'] = "Message sent successfully.";
            }
        }
    }
}
else{
    exit('Sorry, your url request is invalid!');
}
 function test($data){
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
    <link rel="stylesheet" href="css/custom.css">
    <title>Get a house at Home View Apartment</title>
    <style>
        #image:hover{
            display: none;
        }
    </style>
</head>
<body>
 <div class="container-fluid fixed-top py-2" style="background-color:#3b5765;color:#efefef;">
<a href="customer_dashboard.php" class="nav-link text-light"><i class="fa fa-arrow-left fa-lg"> go back</i></a>
</div>
    <div class="col-md-7 mx-auto">
        <div class="card mt-5 mb-5">
            <div class="card-header">
                <h2 style="color: salmon">Quick Contact</h2>
                <p class="lead">
                Contact us today, and get reply within 24 hours.
                </p>
            </div>
            <div class="card-body">
                <?php 
                if (isset($_SESSION['msg'])) {
                echo "<div class='alert alert-success'>";
                echo $_SESSION['msg'];
                echo "</div>";
                unset($_SESSION['msg']);
                }
                ?>

                <form action="" method="POST">
                    <div class="form-group">
                        <textarea class="form-control" name="message" placeholder="Type your message Here..." style="min-height: 180px; max-height: 200px;"></textarea>
                        <p class="text-danger"><?php echo $messageErr; ?></p>
                    </div>
                    <div>
                        <button class="btn btn-outline-secondary" type="submit" name="send" style="float:right">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </body>
    </html>