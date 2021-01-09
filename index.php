<?php 
session_start();
// php code to process the feedback form
include_once('config/connection.php');
$nameErr = $phoneErr = $emailErr = $messageErr = "";
$name = $phone = $email = $message = "";
if (isset($_POST['send'])) {
    $code = "254";
    if (empty($_POST['name'])) {
        $nameErr = "*name is required";
    }
    else{
       $name = test($_POST['name']);
       if(!preg_match("/^[a-zA-Z ]*$/",$name)) {
          $nameErr = "*Invalid name. Only letters and white space allowed.";
        }   
    }
     if (empty($_POST['phone'])) {
        $phoneErr = "*phone is required";
    }
    else{
       $phone = test($_POST['phone']);
       if(preg_match("/[^0-9]/",$phone)) {
        $phoneErr = "*Invalid phone number. Only numbers allowed.";  
            }
        elseif(strlen($phone)< 10) {
        $phoneErr = "*Sorry, the phone number should contain 10 characters.";  
        }  
    }
     if (empty($_POST['email'])) {
        $emailErr = "*email is required";
    }
    else{
       $email= test($_POST['email']); 
       if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "*Invalid Email";
        }  
    }
     if (empty($_POST['message'])) {
        $messageErr = "*message cannot be blank";
    }
    else{
       $message = test($_POST['message']);   
    }
    if (empty($nameErr) && empty($phoneErr) && empty($emailErr) && empty($messageErr) ) {
        date_default_timezone_set("Africa/Nairobi");
        $time = date('Y-m-d H:i:s');
        $status = "unread";
        $newphone = $code.substr($phone, 1);
        $sql = "INSERT INTO `feedback`(`sender_name`, `phone`, `email`, `message`, `time`,`status`)
        VALUES ('$name','$newphone','$email','$message','$time','$status')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
           $_SESSION['msg'] = "Message sent successfully.";
        }
    }
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
<?php include_once('Admin/dist.php'); ?>

    <!-- custom css -->
    <link rel="stylesheet" href="css/main.css">
    <title>Get a house at homeview apartment</title> 
    <style>
        .carousel-item img{
            height: 300px;
        }
        .carousel-caption{
            text-shadow: 2px 2px #004 !important;
        }
        .carousel-indicators{
            background-color: #0009;
            opacity: .85;
        }
    </style>
    <script>
        $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <!-- Navigation bar -->
  <nav class="navbar navbar-expand-md fixed-top" style="background-color: teal">
     <a class="navbar-brand font-weight-bold text-light" href="index.php">Kings<i class="font-italic">View</i> <b class="text-lowercase text-warning"> Apartments</b></a>
        <div class="container-fluid header">
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarcollapse" ><i class="fa fa-bars"></i></button>
            <div class="collapse navbar-collapse" id="navbarcollapse">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="index.php" style="color:#004">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="home/book.php">Book</a></li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fas fa-sign-in-alt nav-icon"></i> Login</a>
                        <div class="dropdown-menu m-auto p-2" style="background-color: #0ca0a0">
                            <a class="dropdown-item" href="login/register.php"><i class="fas fa-sign-in-alt nav-icon"></i> Register</a>
                            <a class="dropdown-item" href="login/login.php"><i class="fas fa-user nav-icon"></i> Customer login</a>
                            <a class="dropdown-item" href="Admin"><i class="fas fa-user nav-icon"></i> Admin Login</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>   
    <div class="container-fluid content-container my-5">
        <div class="col-sm-10 py-md-4 mx-auto text-dark" style="opacity: .85;background-color: #fefefe">
            <h3 class="pt-4">
                KingsView Apartments Provides you with the best houses which have :
            </h3> 
            <ul class="px-4 py-3 text-dark font-weight-bold" style="font-size: 18px; list-style: none;">
                <li ><i class="fa fa-check-circle"></i> A city view</li>
                <li><i class="fa fa-check-circle"></i> 3 bedroom mainsonettes with en-suite DSQ</li>
                <li><i class="fa fa-check-circle"></i> Automatic Backup Generators</li>
                <li><i class="fa fa-check-circle"></i> Free WiFi</li>
                <li><i class="fa fa-check-circle"></i> Ample Parking</li>
                <li><i class="fa fa-check-circle"></i> Borehore and a common garden</li>
                <li><i class="fa fa-check-circle"></i> children's play area</li>
                <li><i class="fa fa-check-circle"></i> Solar Panels</li>       
            </ul>
        </div>  
    </div>
    <div class="container-fluid">
        <h1 class="text-muted text-center">How is KingsView Apartment?</h1>
    </div>
    <div class="container-fluid py-3" style="background-color: #e4eaec;">
        <div class="row">
            <div class="col-md-6 box">
                <h3  class="pt-3 pl-3">It's Self Contained Apartment</h3>
                  <img class="pl-3" src="/kingsview/images/self-contained.png" alt="">
                <p class="pl-2">
                 KingsView Apartment is a self-contained flat which has its own bathroom and kitchen, so that you do not need to share any rooms with people who live in other flats
                </p>
            </div>
            <div class="col-md-6 box">
                <h3 class="pt-3 pl-3">What about Security?</h3>
                <img class="pl-3" src="/kingsview/images/security.png" alt="">
                <p class="pl-2">
                 KingsView Apartment is situated in a secure area guarded so that only specific people can enter or leave it. Cctvs  are also installed all round the apartment.
                </p class="pl-3">
            </div> 
            <div class="col-md-6 box">
                <h3  class="pt-3 pl-3">KingsView  is a Low-rise Apartment</h3>
                  <img class="pl-3" src="/kingsview/images/height.png" alt="">
                <p class="pl-2">
                 KingsView Apartment is a low-rise building with only 8 floors and also equiped with lifts. It's strategically located within Ruiru town providing a beatiful scenic view of the city.
                </p>
            </div>
            <div class="col-md-6">
                <div class="col-sm-8">
                    <img src="/kingsview/images/apartment-logo.svg" alt="">
                </div>
                    <div id="preview" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#preview" data-slide-to="0" class="active"></li>
                            <li data-target="#preview" data-slide-to="1"></li>
                            <li data-target="#preview" data-slide-to="2"></li>
                            <li data-target="#preview" data-slide-to="3"></li>
                            <li data-target="#preview" data-slide-to="4"></li>
                            <li data-target="#preview" data-slide-to="5"></li>
                            <li data-target="#preview" data-slide-to="6"></li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="/homeview/images/homeview.jpg" class="d-block w-100" alt="">
                                <div class="carousel-caption">
                                    <h2>KingsView Apartment</h2>
                                    <p class="lead">
                                        A Great choice of modern houses in Ruiru.
                                    </p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="/homeview/images/bg_image3.png" class="d-block w-100" alt="">
                                <div class="carousel-caption text-light">
                                    <h2>City view.</h2>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="/homeview/images/aerial_view.jpg" class="d-block w-100" alt="">
                                 <div class="carousel-caption text-light">
                                    <h2>Bed-Sitters</h2>
                                    <p class="lead">
                                        Carefully designed each with a Balcony which provides
                                        a scenic view of the city.
                                    </p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="/homeview/images/image2.jpg" class="d-block w-100" alt="">
                            </div>
                            <div class="carousel-item">
                                <img src="/homeview/images/image3.jpg" class="d-block w-100" alt="">
                                 <div class="carousel-caption text-light">
                                    <h2>3 bedroom </h2>
                                    <p class="lead">
                                        mainsonette with en-suite DSQ.
                                    </p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="/homeview/images/image5.jpg" class="d-block w-100" alt="">
                            </div>
                            <div class="carousel-item">
                                <img src="/homeview/images/house13.jpg" class="d-block w-100" alt="">
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#preview" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </a>
                        <a class="carousel-control-next" href="#preview" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </a>
                </div>  
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-md-center mt-4">
            <div class="p-2">
            <a class="navbar-brand font-weight-bold text-info" href="index.php"> <i class="fa fa-chevron-left fa-lg text-muted"></i> Kings<i class="font-italic">View</i> <b class="text-lowercase text-warning"> <br>Apartments</b> <i class="fa fa-building fa-lg text-muted"></i></a>
        </div>
        <div class="col-md-3">
                <div class="block-heading-two text-muted pt-3">
                  <h3><span>Our Compitence</span></h3>
                </div>                
                <h6 class="text-muted pt-2">Routine house Maintenance</h6>
                <div class="progress pb-sm">
                  <div class="progress-bar bg-success" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                  </div>
                </div>
                <h6 class="text-muted pt-2">Customer Satisfaction</h6>
                <div class="progress pb-sm">
                  <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                  </div>
                </div>
                <h6 class="text-muted pt-2">Bill Payments</h6>
                <div class="progress pb-sm">
                  <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
                  </div>
                </div>
                <h6 class="text-muted pt-2">Network Services</h6>
                <div class="progress pb-sm">
                  <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                  </div>
                </div>
                <div class="text-muted my-2">
                    <h4>Best Services</h4>
                    <ul class="list-unstyled list-inline">
                        <li class="list-inline-item"><i class="fa fa-wifi"></i> Free WiFi</li>
                        <li class="list-inline-item"><i class="fa fa-fire-extinguisher"></i> Satefy</li>
                        <li class="list-inline-item"><i class="fa fa-users"></i> Community</li>
                    </ul>
                </div>
          </div>
          <div class="col-md-5">
            <div class="block-heading-two text-muted pt-3">
                  <h3><span>Don't be left out, lets Build KingsView Community together
                  </span></h3>
                </div>
            <div class="card" style="background-color: #e4eaec;">
                <div class="col-10 mx-auto card-img pt-2">
                   <img src="images/gif/giphy.gif"class="img-fluid">
                </div>
                <div class="card-body">
                   <p class="lead text-info">...Lets Go!</a>
                   <span class="spinner-grow" role="status"></span>  
                    <a id="bk" class="nav-link" href="/homeview/home/book.php"><button class="btn btm-lg btn-warning text-muted font-weight-bold" type="button">Click here to Book
                     <i class="fa fa-angle-right"></i></button></a> 
               </p>
                </div> 
            </div>
         </div> 
        </div>
        </div>
         <hr class="my-5">
        <div class="row">
            <div class="col-md-6 mt-5 pl-5 text-secondary contact">
                <h3>Call us on:</h3>
                    <p class="lead"> <i class="fas fa-phone-alt"></i> +254-700-000-000  or +254-700-111-111</p>
                     <h3 > Email:</h3> 
                     <p class="lead"> <i class="fas fa-envelope">
                        </i>   <a href="mailto:test@gmail.com">info@kingsviewapartment.co.ke</a>
                     </p>
                     <h3 > Location:</h3> 
                     <p class="lead"> <i class="fa fa-map-marker-alt "></i> We are located in Ruiru town along Kiambu road 400 meter away from Kihunguro Shopping center.
                     <h3 > Postal Address:</h3> 
                    <i class="fas fa-home fa-lg"></i>  057 Kihunguro, Ruiru P.O.Box 6423 – 00232 
                    </p>
            </div>
            <div class="col-md-6">
                <div class="card mt-5 mb-5">
                    <div class="card-header">
                        <h2 style="color: salmon">Quick Contact</h2>
                        <p class="lead">
                            Contact us today, and get reply within 24 hours.
                        </p>
                    </div>
                    <div class="card-body">
                    <?php if (isset($_SESSION['msg'])){
                        $res = $_SESSION['msg'];?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <p><?php echo $res; ?></p>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                        </div>    
                    <?php } unset($_SESSION['msg']) ?>
                        <form action="" method="POST">
                            <div class="form-row">
                                <div class="col-sm-6">
                                   <div class="form-group">
                                       <input class="form-control" type="text" name="name" placeholder="Your name" value="<?php echo $name; ?>">
                                       <p class="text-danger"><?php echo $nameErr; ?></p>
                                   </div> 
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="tel" name="phone" placeholder="Phone" value="<?php echo $phone; ?>" maxlength="10">
                                        <p class="text-danger"><?php echo $phoneErr; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="email" name="email" placeholder="Email Address" value="<?php echo $email; ?>">
                                <p class="text-danger"><?php echo $emailErr; ?></p>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="message" placeholder="Type your message Here..." style="min-height: 120px; max-height: 120px;"></textarea>
                                <p class="text-danger"><?php echo $messageErr; ?></p>
                            </div>
                            <div>
                                <button class="btn btn-outline-secondary" type="submit" name="send" style="float:right">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="container">
        <div class="row">
            <div class="col-md-4 ml-auto">
                <form action="#" id="newsletter-form" class="bg-secondary">
                <fieldset>
                  <div class="form-group">
                    <h4 class="text-light mr-sm-2">
                        <i class="fa fa-check-circle"></i> Newsletter</h4>
                    <input type="email" name="email" placeholder ="Enter Your Email Here" class="form-control" required>
                  </div>
                  <div class="form-group">
                    <button type="submit" name="newsletter" class="btn btn-danger btn-lg">Subscribe Now <i class="fa fa-bell"></i></button>
                  </div>
                </fieldset>
            </form>
            </div> 
            <div class="col-md-7 ml-auto">
            <div class='px-2'>
            <p class="text-center">
            <i class='fa fa-star'></i> 
            <i class='fa fa-star'></i> 
            <i class='fa fa-star'></i> 
            <i class='fa fa-star'></i> 
            </p>
            <p class='lead text-info'>Hi Dear customer, we appreciate your interest in KingsView Apartments a great choice of apartments in Kenya.</p>
            <h4 class='font-italic text-center text-muted'>
            <i class='fa fa-thumbs-up text-info'></i> Thank you...<i class='fa fa-smile'></i>
            </h4>
            </div>
            <div class="col-lg-5 mx-auto"> 
            <a class="nav-link" href="/homeview/home/book.php"><button class="btn btn-warning btn-block text-muted font-weight-bold" type="button">Book Now</button></a>    
            </div>
            </div>   
        </div>
    </div>

<!--footer-->
    <div class="container-fluid mb-5 more">
         <hr class="my-5">
        <div class="row ">
            <div class="col-sm-4">
                <h4 class="pt-3">Partnership</h4>
               <ul>
                   <li><img class="p-1" src="/homeview/images/sms.png" alt="" height="28px" width="28px"> 254sms</li>
                   <li><img class="p-1" src="/homeview/images/cleaners.jpg" alt="" height="28px" width="28px"> Shafisha Cleaners</li>
                   <li><img class="p-1" src="/homeview/images/rujusco.png" alt="" height="28px" width="28px"> Rujusco Sewarage</li>
                   <li><img class="p-1"  src="/homeview/images/saif.png" alt="" height="28px" width="28px"> Saif Apartments</li>
                   <li><img class="p-1"  src="/homeview/images/kcb.png" alt="" height="28px" width="28px"> Kenya Commercial Bank</li>
               </ul>
            </div>
            <div class="col-sm-3">
                <h4 class="pt-3">Services</h4>
               <ul>
                   <li><i class="fa fa-check-circle"></i> House renting</li>
                   <li><i class="fa fa-check-circle"></i> Advertising</li>
                   <li><i class="fa fa-check-circle"></i> House sale</li>
               </ul>
            </div>
            <div class="col-sm-5">
                <h4 class="pt-3">Contact</h4>
                <p>
                    <i class="fa fa-home text-secondary"></i> 057 Kihunguro, Ruiru
                    <br>
                    <i class="fa fa-envelope text-secondary"></i> 
                    <a href="mailto:info@kingsview.co.ke"> info@kingsview.co.ke</a>
                    <br>
                    <i class="fa fa-phone-alt text-secondary"></i> (254) 700 111 111
                </p>
            </div> 
        </div>
    </div> 
<!--footer-->
<footer>
<div class="container-fluid">
        <div class="row">
            <div class="col-sm-5 text-info">
                <h5 class="widgetheading">Our Mission</h5>
                <div>
                    <p class="small">Our main objective is to provide high quality, secure and affordable houses which will meet your deisire.</p>
                </div>
                
            </div>
            <div class="col-sm-2 text-info">
                <h5 class="widgetheading">Quick links</h5>
                <ul class="list-unstyled">
                    <li><a class="waves-effect waves-dark" href="#">Terms & Conditions</a></li>
                    <li><a class="waves-effect waves-dark" href="#">Privacy Policy</a></li>
                    <li><a class="" href="#">Contact us</a></li>
                </ul>
            </div>
            <div class="col-sm-5 text-info">
                <h5 class="widgetheading">About us</h5>
                <p class="small">
                KingsView apartment ltd is a company which offers middle class residential houses. Our main objective is to provide high quality, secure and affordable houses which will meet your deisire.
                </p>
            </div>
        </div>
    </div>
    <div id="sub-footer">
        <div class="container py-2">
            <div class="row">
                <div class="col-lg-6">
                    <div class="copyright">
                        <p>
                            <span>&copy; <?php echo date('Y') ?> KingsView Apartments ltd. All rights reserved.
                            <br>Proudly Designed by Peter Chege<a href="#"> </a>
                        </p>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <ul class="social-network">
                        <li><a class="text-light" data-placement="top" title="facebook" data-toggle = "tooltip" href="#"><i class="fab fa-facebook"></i></a></li>
                    <li><a class="text-light" data-placement="top" title="twitter" data-toggle = "tooltip" href="#"><i class="fab fa-twitter fa-sm"></i></a></li>
                    <li><a class="text-light" data-placement="top" title="LinkedIn" data-toggle = "tooltip" href="#"><i class="fab fa-linkedin"></i></a></li>
                    <li><a class="text-light" data-placement="top" title="youtube" data-toggle = "tooltip" href="#"><i class="fab fa-youtube"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <a href="#" class="scrollup waves-effect waves-dark"><i class="fa fa-angle-up active"></i></a>
</footer>
</body>
</html>