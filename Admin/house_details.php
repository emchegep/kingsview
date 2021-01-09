<?php 
include_once('../config/connection.php');
$price = $roomNo = $facilitiesErr = $priceErr = $roomNoErr = "";
if (isset($_POST['send']) ) {
  $type =$_POST['house_type'];
  $floor =$_POST['floor_number'];
  if (empty($_POST['roomNo'])) {
  $roomNoErr = "*Room number is required"; 
  }
  else{
    $roomNo =$_POST['roomNo'];
    if (strlen($roomNo)<3) {
      $roomNoErr = "*Room number should contain at least three characters"; 
    }
    elseif ($exists = verify($conn,$roomNo)) {
    $roomNoErr = "*Sorry, Room No. ".$roomNo." is already associated to anothe house";
    }
    elseif (preg_match("/[^0-9]/",$roomNo)) {
      $roomNoErr = "*Invalid Room no. Only number allowed. e.g. 001";
    }
  }
  if (empty($_POST['facilities'])) {
  $facilitiesErr = "*Please select at lease one general facility"; 
  }
  else{
  $facilities =$_POST['facilities'];
  }
  if (empty($_POST['facilities'])) {
  $facilitiesErr = "*Please select at lease one general facility"; 
  }
  else{
  $general_facilities = $_POST['facilities'];
  }
  if (empty($_POST['internals'])) {
  $facilitiesErr = "*Select at least one internal facility";
  }
  else{
  $internal_facilities = $_POST['internals'];
  }
  if(empty($_POST['price'])) {
    $priceErr = "*Price cannot be blank"; 
  }
  else{
    $price = $_POST['price'];
    if (preg_match("/[^0-9,]/",$price)) {
    $priceErr = "*Currency cannot contain letters, whitespaces or any special characters. Only numbers allowed"; 
    }
  } 
  if(empty($priceErr) && empty($roomNoErr) && empty($facilitiesErr)){
    $general_facilities = implode(',', $general_facilities);
    $internal_facilities = implode(',', $internal_facilities);
    $price = str_replace(",", "",$price);
    $newprice = number_format($price);
    $roomNo = "H".$roomNo;
    $sql = "INSERT INTO `houses`(`room_no`, `type`, `floor_number`, `price`, `general_features`, `internal_features`) 
    VALUES ('$roomNo','$type','$floor','$newprice','$general_facilities','$internal_facilities')";
    $execute = mysqli_query($conn,$sql);
    if ($execute) {
       session_start();
      $_SESSION['success'] = "Your house details has been added Successfully";
    }
    else{
      $_SESSION['fail'] = "Oops! an error occurred while adding the house details. Please try again.";
    }
  } 
}
function verify($conn,$data){
  $room_to_verify = $data;
  $query = " SELECT `room_no` FROM `houses`;";
  $result = mysqli_query($conn,$query);
  while ($row = mysqli_fetch_assoc($result)) {
    $db_roomNo = substr($row['room_no'], 1);
    if ($db_roomNo == $room_to_verify) {
      $exists = true;
      return $exists;
    }
  }
}
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <?php include_once('dist.php'); ?>

      <title>House Info</title>
      <style>
        body{
        background-color: #0003;
        }
        label{
        color:#002;
        }
      </style>
  </head>
<body>
<div class="container-fluid sticky-top py-2" style="background-color:#3b5765;color:#efefef;">
<a href="index.php" class="nav-link text-light"><i class="fa fa-arrow-left fa-lg"> go back</i></a>
</div>
<div class="modal-dialog modal-lg" style="margin-top: 70px">
<div class="modal-content">
  <div class="text-center text-secondary py-2">
    <h2><i class="fa fa-exclamation-circle"></i> Add House Info</h2>
  </div>
   <?php 
    if (isset($_SESSION['success'])) {
        echo "<div class='col-sm-10 mx-auto'>";
        echo "<div class='alert alert-success'>";
        echo $_SESSION['success'];
        echo "</div>";
        echo "</div>";
      }
    if (isset($_SESSION['fail'])) { 
        echo "<div class='col-sm-10 mx-auto'>"; 
        echo "<div class='alert alert-danger'>";
        echo $_SESSION['fail'];
        echo "</div>";
        echo "</div>";
        unset($_SESSION['fail']);
    }
  ?>

  <form class="col-md-10 mx-auto" action="" method="POST">
    <div class="form-row">
      <div class="col-md-6 pb-2">
        <label class="label-control" for="roomId">Type of house</label>
        <select id="roomId" class="custom-select" name="house_type" >
          <option value="Bed Sitter">Bed Sitter</option>
          <option value="Single Room">Single Room</option>
          <option value="1 bed Room">1 Bed Room</option>
          <option value="2 Bed Room">2 Bed Room</option>
          <option selected value="3 Bed Room">3 Bed Room</option>
          <option value="4 Bed Room">4 Bed Room</option>
        </select>
      </div>
      <div class="col-md-6 pb-2">
        <label class="label-control" for="floorId">Floor number</label>
        <select  id="floorId"class="custom-select" name="floor_number" >
          <option value="Ground floor">Ground floor</option>
          <option selected value="1st floor">1st floor</option>
          <option value="2rd floor">2nd floor</option>
          <option value="3rd floor">3rd floor</option>
          <option value="4th floor">4th floor</option>
          <option value="5th floor">5th floor</option>
          <option value="6th floor">6th floor</option>
          <option value="7th floor">7th floor</option>
          <option value="8th floor">8th floor</option>
        </select>
      </div>
    </div>
    <div class="form-row">
    <div class="col-md-6">
      <div class="form-group">
      <label class="label-control" for="roomNo"> Room No.</label>
      <input id="price" class="form-control" type="text" name="roomNo" maxlength="3" placeholder="e.g. 001" value="<?php echo $roomNo; ?>">
      <p class="text-danger">
        <?php echo $roomNoErr; ?>
          
        </p>
      </div>
    </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="label-control" for="price">Price</label>
            <input id="price" class="form-control" type="text" name="price" placeholder="Enter price in KES." value="<?php echo $price; ?>">
            <p class="text-danger">
              <?php echo $priceErr; ?>
                
              </p>
          </div>
        </div>
      </div>
      <div class="form-row">
      <div class="form-group col-md-6">
        <h4 class="text-secondary" >General Features</h4>
        <div class="form-check">
            <input id="chkbx1" class="form-check-input" type="checkbox" value="Wi-Fi" name = "facilities[]"  checked>
            <label class="form-check-label" for="chkbx1">Wi-Fi</label>
        </div>
        <div class="form-check">
            <input id="chkbx2" class="form-check-input" type="checkbox" value="City View" name = "facilities[]">
            <label class="form-check-label" for="chkbx2">City View</label>
        </div>
        <div class="form-check">
            <input id="chkbx3" class="form-check-input" type="checkbox" value="Balcony" name = "facilities[]">
            <label class="form-check-label" for="chkbx3">Balcony</label>
        </div>
        <div class="form-check">
            <input id="chkbx4" class="form-check-input" type="checkbox" value="CCTV" name = "facilities[]"checked>
            <label class="form-check-label" for="chkbx4">CCTV</label>
        </div>
        <div class="form-check">
            <input id="chkbx5" class="form-check-input" type="checkbox" value="Ample Parking" name = "facilities[]" >
            <label class="form-check-label" for="chkbx5">Parking inclusive</label>
        </div>
        <div class="form-check">
            <input id="chkbx6" class="form-check-input" type="checkbox" value="Backup Generator " name = "facilities[]" checked>
            <label class="form-check-label" for="chkbx6">Backup Generator</label>
          </div>
          <div class="form-check">
            <input id="chkbx7" class="form-check-input" type="checkbox" value="Common Garden " name = "facilities[]">
            <label class="form-check-label" for="chkbx7">Common Garden</label>
          </div>
          <div class="form-check">
            <input id="chkbx8" class="form-check-input" type="checkbox" value="Lift Elevator " name = "facilities[]">
            <label class="form-check-label" for="chkbx8">Lift Elevator</label>
          </div>
           <div class="form-check">
            <input id="chkbx9" class="form-check-input" type="checkbox" value="Water Included" name = "facilities[]" checked>
            <label class="form-check-label" for="chkbx9">Water Included</label>
          </div>
           <div class="form-check">
            <input id="chkbx10" class="form-check-input" type="checkbox" value="Electricity Included" name = "facilities[]" checked>
            <label class="form-check-label" for="chkbx10">Electricity Included</label>
          </div>
        </div>
        <div class="form-group col-md-6" >
          <h4 class="text-secondary" >Internal Features</h4>
          <div class="form-check">
            <input id="chkbx11" class="form-check-input" type="checkbox" value="Ensuite" name = "internals[]">
            <label class="form-check-label" for="chkbx11">Ensuite</label>
          </div>
          <div class="form-check">
            <input id="chkbx12" class="form-check-input" type="checkbox" value="Fitted Kitchen" name = "internals[]">
            <label class="form-check-label" for="chkbx12">Fitted Kitchen</label>
          </div>
          <div class="form-check">
            <input id="chkbx13" class="form-check-input" type="checkbox" value="Alarm" name = "internals[]" checked>
            <label class="form-check-label" for="chkbx13">Alarm</label>
          </div>
          <div class="form-check">
            <input id="chkbx14" class="form-check-input" type="checkbox" value="Wall Cupboards" name = "internals[]" checked>
            <label class="form-check-label" for="chkbx14">Wall Cupboards</label>
          </div>
           <div class="form-check">
            <input id="chkbx15" class="form-check-input" type="checkbox" value="Spa Bath" name = "internals[]" checked>
            <label class="form-check-label" for="chkbx15">Spa Bath</label>
          </div>
          <div class="form-check">
            <input id="chkbx16" class="form-check-input" type="checkbox" value="Gym" name = "internals[]">
            <label class="form-check-label" for="chkbx16">Gym</label>
          </div>
        </div>
      </div>
      <p class="text-danger">
        <?php echo $facilitiesErr; ?>
        
      </p> 
    <div class="form-group" >
      <button type="submit" name="send" class="btn btn-outline-success">Submit </button>
    </div> 
    </form>
</div>
</div>
<script src="/homeview/dist/js/jquery.js" ></script>
<script src="/homeview/dist/js/popper.min.js" ></script>
<script src="/homeview/dist/js/bootstrap.min.js" ></script>
</body>
</html>