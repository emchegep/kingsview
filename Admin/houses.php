<?php
  //start a session when the page is loaded
  session_start(); 
  $username = $_SESSION['name'];
  //establish connection to the database
  include_once('../config/connection.php');
 //check if the login session is set before loading the page content
  if (isset($username)) {
    //retrieve all records from houses table  
   $query = "SELECT *FROM `houses`";
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
  <title>Houses | Info</title>
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
    tbody{
      max-height: 65vh;
      overflow-y: scroll;
      display: block;
      background-color: #e4eaec;
    }
    tr{
      display: block;
    }
    td,th{
      width: 75vw;
      text-align: center;
    }
  </style>
</head>
<body>
<div class="container-fluid fixed-top py-2" style="background-color:#3b5765;color:#efefef;">
  <a href="index.php" class="nav-link text-light"><i class="fa fa-arrow-left fa-lg"> go back</i></a>
</div>
<div class="container">
  <div class="col-sm-10 mx-auto my-4">
    <h3 class="text-info text-center">View | Delete House Info</h3>
  </div>
    <div class="col-8-md">
      <table class="table table-hover table-sm">
        <thead>
          <tr style="background-color:#ffe1c4; color: #757575">
          <th>#</th>
          <th>Type</th>
          <th>Room</th>
          <th>Floor</th>
          <th>Features</th>
          <th>Delete</th>
          </tr>
        </thead>
        <tbody style="background-color:#f3f7f9; color: #757575">
          <?php
          $x = 0;
           while ($rows = mysqli_fetch_array($result))
            { 
              echo "<tr>";
              echo "<td>".$x+=1;"</td>";
              echo "<td>".$rows['type']."</td>";
              echo "<td>".$rows['room_no']."</td>";
              echo "<td>".$rows['floor_number']."</td>";
               echo "<td>".$rows['internal_features']."</td>";
              echo "<td class='text-center'>";
              echo "<a  href='delete_house.php?id=". $rows['room_no'] ."' title='Delete Record' data-toggle='tooltip' ><i class='fa fa-trash text-danger'></i></a>";
              echo "</td>";
               echo "<tr>";
              }
            ?>

        </tbody>
      </table>
    </div>
    <?php 
      if (isset($_SESSION['houseDeleted'])) {
        echo "<div class='col-sm-6 mx-auto alert alert-success' >";
        echo '<i class="fa fa fa-check text-success" style="font-size:18px"></i> '.$_SESSION['houseDeleted'];
        echo "</div";
        unset($_SESSION['houseDeleted']);
      }
    ?>

  </div>
</body>
</html>