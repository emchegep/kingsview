<?php 
  include_once('../config/connection.php');
  $price = $txt = $txtErr  = $roomErr =  $room = $imageErr = "";
  if (isset($_POST['delete_post'])) {
   $room = $_POST['select_post'];
   if(!empty($room )){
    $sql = "DELETE FROM `uploads` WHERE `room_no` = '$room'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
     $_SESSION['uploadSuccess'] = "House ".$room." has been deleted Successfully. Your customers won't see the post.";
     }
     else{
      echo mysqli_error($conn);
     }
   }
  }
  if (isset($_POST['upload']) ) {
    $target_name = "uploaded_images/";
    $target_name = $target_name.basename($_FILES['uploadFile']['name']);
    $fileName = $_FILES['uploadFile']['name'];
    $fileExt = explode('.', $fileName);
    $ActualExt = strtolower(end($fileExt));
    $extentions = array('jpg','jpeg','png');
    $status =$_POST['status'];   

    if(empty($_POST['txt'])) {
    $txtErr = "*Please briefly describe the house"; 
    }
    else{
     $txt = $_POST['txt'];
    }
    if (empty($fileName)) {
      $imageErr = "*No file selected. Please select the image file you want to upload.";
    }
    elseif(!in_array($ActualExt, $extentions)) {
    $imageErr = "*Sorry, files with .".$ActualExt." extention are not allowed. Please select a .jpg .jpeg or .png file."; 
    }
    if ($exists = verify($conn,$_POST['roomNo'])) {
     $roomErr = "*Sorry, house ".$_POST['roomNo']." has already been uploaded.";
    }
    else{
    $roomNo = $_POST['roomNo'];
    } 
    if ($exists = verify($conn,$target_name)) {
    $imageErr = "*Oops! it seem like this image file (".$fileName.") has already been uploaded in the system.";
    }
    else{
      move_uploaded_file($_FILES['uploadFile']['tmp_name'],$target_name);
    }
    if(empty($txtErr) && empty($imageErr) && empty($roomErr)){
      $query = "INSERT INTO `uploads`(`room_no`, `description`, `status`, `path`)
       VALUES ('$roomNo','$txt','$status','$target_name')";
      $execute = mysqli_query($conn,$query);
      if ($execute) {
        session_start();
        $_SESSION['uploadSuccess'] = "Your house has been uploaded Successfully";
      }
    }
  }
  function verify($conn,$data){
    $room_to_verify = $data;
    $path_to_verify = $data;
    $query = " SELECT `path`,`room_no` FROM `uploads`;";
    $result = mysqli_query($conn,$query);
    while ($row = mysqli_fetch_assoc($result)) {
      $db_roomNo = $row['room_no'];
      $db_path = $row['path'];
      if ($db_roomNo == $room_to_verify) {
        $exists = true;
        return $exists;
      }
      if ($db_path == $path_to_verify) {
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
    <title>upload your houses</title>
    <style>
      body{
      background-color: #0003;
      }
      label{
      padding-bottom: 5px;
      color:#002;
      }
    </style>
  </head>
<body>
  <div class="modal-dialog modal-dialog-scrollable modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">POST A VACANT HOUSE FOR YOUR CUSTOMERS TO FIND IT EASILY </h5>
        <a href="index.php" class="text-danger" style="font-size: 30px; text-decoration: none">
          <span >&times;</span>
        </a>
      </div>
      <div class="modal-body">
        <?php 
        if (isset($_SESSION['uploadSuccess'])) {
        echo '<div class="alert alert-success" role = "alert">';
        echo '<i class="fa fa fa-check text-success" style="font-size:18px"></i> '.$_SESSION['uploadSuccess'];
        echo '</div>';
        unset($_SESSION['uploadSuccess']);
        }
      ?>
      <form action="" method="POST" enctype="multipart/form-data">
      <div class="form-group" >
      <label for="txt1"> Description:</label>
      <textarea class="form-control" name="txt" id="txt1" placeholder="Brief describe the house..."><?php echo $txt; ?></textarea>
      <p class="text-danger"><?php echo $txtErr; ?></p>
      </div>
      <div class="form-group">
      <label class="label-control" for="roomNo">Room No.</label>
      <select id="roomNo" class="custom-select" name="roomNo" >
      <?php 
      $sql = "SELECT `room_no`,`type` FROM `houses`";
      $result = mysqli_query($conn,$sql);
      while ($rows = mysqli_fetch_assoc($result)) {
      ?>
      <option value="<?php echo $rows['room_no'] ?>"><?php echo $rows['room_no'] ?> - <?php echo $rows['type']; ?></option>
      <?php } ?>
      </select>
      <p class="text-danger"><?php echo $roomErr; ?></p>
      </div>
      <input type="text" name="status" value="Vaccant" hidden>
      <div class="form-group">
      <input class="form-control-file my-3" type="file" name="uploadFile">
      <p class="text-danger"><?php echo $imageErr; ?></p>
      </div>
      <div class="form-group" >
        <button type="submit" name="upload" class="btn btn-outline-success">
          <i class="fa fa-upload" style="font-size: 18px"></i> Upload the house
        </button> 
      </div>
      <div class="form-group form-row">
           <div class="form-group col-sm-7">
            <label for="lb1">Delete a Post</label>
            <select class="custom-select col-6 my-1" name="select_post" id="lb1">
              <?php  
              $sql = "SELECT room_no FROM uploads";
              $result = mysqli_query($conn, $sql);
              if (mysqli_num_rows($result) > 0) {
                 while ($rows = mysqli_fetch_assoc($result)) {
              ?>
             <option value="<?php echo $rows['room_no'] ?>">
              <?php echo $rows['room_no'] ?>
             </option>
              <?php } 
              }
              else{ ?>
              <option value="">No house</option>
              <?php } ?>
            </select>
          </div>
          <div class="col-sm-5">
            <button type="submit" name="delete_post" class="btn btn-outline-danger btn-sm">Delete Selected House</button>
          </div>
        </div>   
      </form> 
      </div>
    </div>
  </div></div>
<script src="/homeview/dist/js/jquery.js" ></script>
<script src="/homeview/dist/js/popper.min.js" ></script>
<script src="/homeview/dist/js/bootstrap.min.js" ></script>
</body>
</html>