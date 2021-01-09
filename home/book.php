<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once('../Admin/dist.php'); ?>

    <!-- custom css -->
    <link rel="stylesheet" href="css/custom.css">
    <title>Get a house at KingsView Apartment</title>
    <style>
        #image:hover{
            display: none;
        }
        #specs li{
            border: 1px solid lightgrey;
            padding: 2px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <!-- Navigation bar -->
  <nav class="navbar navbar-expand-md fixed-top" style="background-color: teal">
    <a class="navbar-brand font-weight-bold text-light" href="../index.php">Kings<i class="font-italic">View</i> <b class="text-lowercase text-warning"> Apartments</b></a>
        <div class="container-fluid header">
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarcollapse" ><i class="fa fa-bars"></i></button>
            <div class="collapse navbar-collapse" id="navbarcollapse">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="/kingsview">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="book.php" style="color:#004">Book</a></li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fas fa-sign-in-alt nav-icon"></i> Login</a>
                        <div class="dropdown-menu m-auto p-2" style="background-color: #0ca0a0">
                            <a class="dropdown-item" href="/kingsview/login/register.php"><i class="fas fa-sign-in-alt nav-icon"></i> Register</a>
                            <a class="dropdown-item" href="/kingsview/login/login.php"><i class="fas fa-user nav-icon"></i> Customer login</a>
                            <a class="dropdown-item" href="/kingsview/Admin"><i class="fas fa-user nav-icon"></i> Admin Login</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<div class="container pt-4" style="margin-bottom: 150px;">
    <div class="row">
    <?php
    include_once('../config/connection.php');
    //joins house table with upload table
   $sql = "
        SELECT
        houses.room_no,
        houses.type,
        houses.floor_number,
        houses.price,
        houses.general_features,
        houses.internal_features,
        uploads.upload_id,
        uploads.description,
        uploads.status,
        uploads.path
        FROM
        uploads
        INNER JOIN houses ON uploads.room_no = houses.room_no 
    ";
    $result = mysqli_query($conn,$sql);
if (mysqli_num_rows($result) > 0) {
    $folder = "/kingsview/Admin/";
    $x = 3.0;
    while ($row = mysqli_fetch_assoc($result)) {
        $upload_id = $row['upload_id'];
        $path = $folder.$row['path'];
        $price = $row['price'];
        $general_facilities = $row['general_features'];
        $internal_facilities = $row['internal_features'];
        $txt = $row['description'];
        $general_facility = explode(',', $general_facilities);
        $internal_facility = explode(',', $internal_facilities);
        ?>
        <div class="col-md-4 my-2">
        <?php  
        echo "<h3 class='text-secondary'><i class='fa fa-caret-left'></i> {$row['type']} <i class='fa fa-caret-right'></i></h3>";   
        echo "<img class='img-thumbnail house-img' src = '{$path}' width ='100%'/>"; 
        echo '<h4 class="text-danger pt-1">'.'KES '.$price.' per Month</h4>';
        echo " <a href='order.php?id=".$upload_id."'>
            <button class='btn btn-outline-warning text-muted  m-1'>
            <i class='fa fa-plus-circle text-success'></i> Reserve This House
            </button>
            </a>";
        ?>
        </div>
        <div class="col-md-8 ml-auto mb-auto" style='border-bottom: 3px solid lightgrey; border-top: 0.5px solid lightgrey'>
            <div class="row">
                <div class="container col-sm-7 p-2">
                <h4 class="text-info pt-1">Description</h4>
                <p class="text-info p-2" style="border: 1px solid lightgrey;border-radius:8px; background-color: #e4eaec;">
                    <?php echo $txt; ?> <br>
                <i class="fa fa-angle-double-right text-danger fa-lg"></i> 
                <span class="text-danger lead">
                    <?php echo $row["floor_number"]; ?></span>
                </p>
                <span class='text-primary mr-3'><span class="badge badge-primary"><?php echo $x+=0.1; ?></span> Superb</span>
                <span class="small">
                <i class='fa fa-star fa-sm text-warning'></i>  
                <i class='fa fa-star fa-sm text-warning'></i>
                <i class='fa fa-star fa-sm text-warning'></i>
                <i class='fa fa-star fa-sm text-success'></i>
                <i class='fa fa-star fa-sm text-success'></i>
                <span class='text-danger ml-3'>Quality rating</span>
                </span>
                <ul id="specs" class="list-unstyled list-inline small">
                    <span class="fa fa-thumbs-up text-warning fa-lg mr-3"></span>
                    <li class="list-inline-item my-1"><i class="fa fa-check small text-muted"></i> Balcony</li>
                    <li class="list-inline-item my-1"><i class="fa fa-check small text-muted"></i> View</li>
                    <li class="list-inline-item my-1"><i class="fa fa-check small text-muted"></i> <?php echo $internal_facility[1];?></li>
                    <li class="list-inline-item my-1"><i class="fa fa-check small text-muted"></i> <?php echo end($internal_facility); ?></li>
                </ul>
                </div>
                <div class="col-sm-5 p-2">
                <h4 class="text-secondary">Internal Features</h4>
                <?php
                for ($i=0; $i < count($internal_facility); $i++){
                echo '<i class="fa fa-angle-right text-danger"></i>'.' '.$internal_facility[$i];
                echo '<br>';}
                ?>
                <div class="text-muted my-2">
                    <span>Common Specs</span>
                 <i class='fa fa-wifi fa-sm'></i> 
                 <i class='fa fa-bath fa-sm'></i> 
                <i class='fa fa-water fa-sm'></i>  
                <i class='fa fa-hotel fa-sm'></i>
                <i class='fa fa-fire-extinguisher fa-sm text-danger'></i>  
                </div>
                <a href="#" class="btn m-1 btn-outline-dark dropdown-toggle border-0" data-toggle="dropdown">
                More 
                </a>
                <div class="dropdown-menu pl-3">
                <?php
                for ($i=0; $i < count($general_facility); $i++){ 
                echo '<i class="fa fa-angle-right text-danger"></i>'.' '.$general_facility[$i];
                echo '<br>';}               
                ?>
                </div>
                </div>
            </div>
        </div>
        <?php 
    } ?>
    </div>
</div>
<?php }
else{
echo "<div class='col-sm-8 py-5 mx-auto text-center'>";
echo "</p>
        <i class='fa fa-star'></i> 
        <i class='fa fa-star'></i> 
        <i class='fa fa-star'></i> 
        <i class='fa fa-star'></i> 
    </p>";
echo "<p class='lead text-info'>Hi Dear customer, we appreciate your interest in KingsView Apartment but its soo unfortunate we don't have any vacant at the moment. <br>
    Once we post a vacant it will appear here.</p>";
echo "<h4 class='font-italic text-center text-warning'>
 <i class='fa fa-thumbs-up text-info'></i> Thank you...<i class='fa fa-smile'></i>
 </h4>";
echo "</div>";
?>
    <div class="container">
        <div class="row">
            <div class="col-sm-6 my-sm-5">
                <h5 class="text-secondary">Follow us on social media</h5>
                <a href="#" ><i class = "fab fa-facebook-f social-icon fb"></i></a>
                <a href="#" ><i class = "fab fa-twitter social-icon tt"></i></a>
                <a href="#" ><i class = "fab fa-linkedin social-icon ln"></i></a>
                <a href="#" ><i class = "fab fa-youtube social-icon tb"></i></a>
                <h5 class="text-secondary">Contact us</h5>
                <i class="fa fa-home text-secondary"></i> 057 Kihunguro, Ruiru P.O.Box 6423 – 00232. <br>
                <i class="fa fa-phone text-secondary"></i> +254-700-111-111
            </div>

            <div class="col-sm-6 my-sm-5">
                <h5 class="text-secondary">About us</h5>
                <div class="text-secondary">
                KingsView apartment ltd is a company which offers middle class residential houses. Our main objective is to provide high quality, secure and affordable houses which will meet your deisire.
                </div> 
            </div>   
        </div>
        <div class="col-sm-8 mx-auto text-center">
            &copy; <?php echo date('Y'); ?> KingsView Apartment ltd. <br> All rights Reserved. Designed by Peter Chege <br>
            Privacy Policy - <a href="#">Terms & Conditions</a>
        </div>
    </div>
 <?php 
exit();
}
?>

<!--footer-->
<div class="container-fluid footer">
        <div class="row d-flex justify-content-sm-between">
              <div class="col-md-3 py-md-3">
            <h5 class="text-secondary">Follow us on social media</h5>
            <a href="#" ><i class = "fab fa-facebook-f social-icon fb"></i></a>
            <a href="#" ><i class = "fab fa-twitter social-icon tt"></i></a>
            <a href="#" ><i class = "fab fa-linkedin social-icon ln"></i></a>
            <a href="#" ><i class = "fab fa-youtube social-icon tb"></i></a>
        </div>
        <div class="col-md-5 py-md-3">
        <h5 class="text-secondary">About us</h5>
         <div class="text-secondary">
            KingsView apartment ltd is a company which offers middle class residential houses. Our main objective is to provide high quality, secure and affordable houses which will meet your deisire.
        </div> 
        </div>
        <div class="col-md-4 py-md-3 ">
            <div class="text-secondary"> 
                &copy; <?php echo date('Y'); ?> KingsView Apartment ltd. <br> All rights Reserved. Designed by Peter Chege <br>
                Privacy Policy - <a href="#">Terms & Conditions</a>
            </div>
        </div>
        </div>
    </div>
</body>
</html>