<?php
  //start a session when the page is loaded
  session_start(); 
  $username = $_SESSION['name'];
  //establish connection to the database
  include_once('../config/connection.php');
 //check if the login session is set before loading the page content
  if (isset($username)) {
    //retrieve the total number of messages sent
    $result = mysqli_query($conn,'SELECT *FROM `feedback` WHERE `status`="unread"');
    if ($result) {
     $total_messages = mysqli_num_rows($result);
    }
    else{
     $total_messages = 0; 
    }   
    $query = " SELECT * FROM `users`; ";
    $result = mysqli_query($conn,$query);
    $total = mysqli_num_rows($result);
  }
  else{
    //if session is not set redirect the user to the index page to login
    header('location:admin_login.php');
    exit();
  }
?>
<!doctype html>
<html lang="en">
<head>
<?php include_once('dist.php'); ?>
  <title>Admin Pannel</title>
  <script type="text/javascript">
    //Invoke Jquery
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
    });
  </script>
  <style>
    body{
      font-family: sans-serif;
      padding-top: 50px;
      background-color:#fafafa ;
    }
    .sidebar-fixed li{
      padding: 10px;
      border-bottom: 1px solid lightgray;
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
    .fa-bars{
      cursor: pointer;
      margin: 0;
    }
    .fa-bars:hover{
      color: #004;
    }
    .fa-times:hover{
      color: #004;
    }
    table{
      max-height: 65vh;
      overflow-y: scroll;
      display: block;
    }
    td,th{
      width: 70vw;
    }
   @media (min-width: 568px) {
        .navbar-toggler {
          display: none;
        }
         .sidebar-fixed{
        overflow-y: hidden !important;
      }
      }
      @media (max-width: 568px) {
      p{
        font-size: 16px !important;
      }
      .fixed-top i{
        font-size: 14px !important;
        display: inline !important;
      }
    }
     @media (max-width: 568px) {
      .sidebar-fixed{
        width: 95%;
      }
      .sidebar-fixed li{
      padding: 50px auto;
      width: 80%;
      border-bottom: none;
     }
      .sidebar-fixed li a{
      font-size: 18px;
    }
    }
  </style>
</head>
<body>
<div class="container-fluid d-flex justify-content-md-between fixed-top" style="background-color:#3b5765;color:#efefef">
  <p class="pt-3">
    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidebar" ><i class="fa fa-bars text-light"></i></button>
   <a href="/homeview" title="back to homepage" class="text-light " data-toggle='tooltip'><i class="fa fa-home fa-lg pr-3"></i></a>
    <a href="feedback.php?ref=unread" title="notifications" class="text-light pr-3" data-toggle='tooltip'><i class="fa fa-bell" >
    <?php if ($total_messages != 0) { ?>
      <sub class="badge badge-pill badge-danger m-0"><?php echo $total_messages; ?></sub>
    <?php } ?>
    </i></a>
  </p>
  <p class="pt-3">
    <?php echo "Welcome, ".$username;?>
  </p>
</div>
<div class="container-fluid">
  <div class="row">
    <nav class="col-md-3 d-md-block collapse navbar-collapse" id="sidebar">
      <div class="sidebar-fixed" >
        <ul class="nav flex-column">
          <li class="text-info py-3" style=" border-bottom: 3px solid lightgrey; font-size:22px;">
            <i class="fa fa-tachometer-alt fa-lg"></i> Dashboard
          </li>
          <li class="nav-item">
            <a class="nav-link " href="requests.php"><i class="fa fa-shopping-cart fa-lg"></i> Customer Requests</a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="messaging.php"><i class="far fa-comments fa-lg fa-lg"></i> Messaging</a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="house_details.php"><i class="fa fa-plus fa-lg"></i> Add a house</a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="houses.php"><i class="fa fa-edit fa-lg"></i> View and Edit House Info</a>
          </li>
          <li class="nav-item">
            <a href="upload_house.php" class="nav-link"><i class="fa fa-upload fa-lg"></i> Post a Vacant</a>
          </li>
          <li class="nav-item">
            <a href="manage_account.php" class="nav-link"><i class="fa fa-cog fa-lg"></i> Manage Account</a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="logout.php"><i class="fa fa-sign-out-alt fa-lg"></i> Logout</a>
          </li>
        </ul>
    </div>
  </nav>
  <div class="col-md-9 mr-auto">
    <div class="col-sm-12 mx-auto mt-2">
    <?php 
    if (isset($_SESSION['password_update'])) {
    echo "<div class=' alert alert-success' >";
    echo '<i class="fa fa fa-check text-success" style="font-size:18px"></i> '.$_SESSION['password_update'];
    echo "</div";
    unset($_SESSION['password_update']);
    }
    ?>

  </div>
    <h2 class="text-center pt-2 text-info" >Customers</h2>
    <p class="lead"> <?php echo date('jS M Y'); ?></p>
    <div class="d-flex justify-content-between">
     <p class="lead text-success" >Registered customers: <?php echo '<span class="badge badge-pill badge-success">'.$total;"</span>" ?> </p>
     <form action="cutomers_excel_report.php" method="POST">
       <button class="btn btn-outline-secondary" type="submit" name="export"><i class="fa fa-file-excel fa-lg text-success"></i> Export to Excel</button>
     </form> 
    </div>
      <table class="table table-hover table-bordered table-sm table-responsive">
        <thead>
          <tr style="background-color:#ffe1c4; color: #757575">
          <th>#</th>
          <th>Name</th>
          <th>Phone</th>
          <th>Email</th>
          <th>More</th>
          <th>Delete</th>
          </tr>
        </thead>
        <tbody style="background-color:#f3f7f9; color: #757575">
          <?php
          $x = 0;
           while ($rows = mysqli_fetch_array($result))
            { 
              echo "<tr>";
               echo "<td>".$x +=1;"</td>";
              echo "<td>".$rows['first_name']." ".$rows['second_name']."</td>";
              echo "<td>".$rows['phone']."</td>";
              echo "<td>".$rows['email']."</td>";
              echo "<td>";
              echo " <a href='read.php?id=".$rows['customer_id']."' title='View info' data-toggle='tooltip'><i class='fa fa-eye text-info'></i>";
              echo "</td>";
              echo "<td>";
              echo "<a  href='delete_customer.php?id=". $rows['customer_id'] ."' title='Delete Record' data-toggle='tooltip' ><i class='fa fa-trash text-danger'></i></a>";
              echo "</td>";
               echo "<tr>";
              }
            ?>

        </tbody>
      </table>
      <?php 
      if (isset($_SESSION['deleted'])) {
        echo "<div class='col-sm-6 mx-auto alert alert-success' >";
        echo '<i class="fa fa fa-check text-success" style="font-size:18px"></i> '.$_SESSION['deleted'];
        echo "</div";
        unset($_SESSION['deleted']);
      }
       ?>
  </div>
  </div>
  </div>
</body>
</html>