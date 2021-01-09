<?php
session_start();
include_once('../config/connection.php');
    $to = $fname = $msg = $Greeting =$result = $phoneError =  $textError = "";
    if (isset($_GET['contact']) && !empty($_GET['contact'])) {
        $contact = $_GET['contact'];
        $contact = explode("-", $contact);
        $phone = end($contact);
        $fname = strtok($contact[0], " ");
        $Greeting = "Hi ".$fname.", we have received you request...";
        $to = trim($phone);
    }
    
    if (isset($_POST["send"])) {
            $fname = $_POST["fname"];
        if(empty($_POST["toInput"])){
            $phoneError = " *Please Eneter the number of the receipient";
        }
        else {
            $to = $_POST["toInput"];
            if(preg_match("/[^0-9,]/",$to)) {
            $phoneError = "*Invalid phone number. Only numbers allowed, Whitespaces are also not allowed.\n Multiple phone numbers should be separated by comma.";  
            }
        }

        if(empty($_POST["textInput"])){
            $textError = " *Please write something...You can't send a blank message";
        }
        else{
            $msg = $_POST["textInput"];
        }
        if (empty($phoneError) && empty($textError)) {
                                                                                   
        $message =$msg;
        $sender_id = '254sms'; //Default senderId
        $phone = $to; //for multiple concatinate with comma(,)
        $apikey = 'MDU2ZWRjNDFiYTJlMDQxZmU4YmY4Nj'; // Check under HTTP API in 254sms.com
        $username= 'Dennis'; // Your 254sms.com Username
        $url = 'http://api.254sms.com/version1/sendsms';
        $data = array(
            'username' => $username,
            'api_key' => $apikey,
            'sender_id' => $sender_id,
            'phone' => $phone,
            'message' => $message  
        );
        $payload = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $result;
        $_SESSION['messagesuccess'] = $result;
        }
    }   
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once('dist.php'); ?>

    <title>Send message</title>
    <script type="text/javascript">
    //Invoke Jquery
      $(document).ready(function(){
          $('[data-toggle="tooltip"]').tooltip();   
      });
    </script>
    <style>
        body{
          font-family: sans-serif;
          padding: 0;
          margin: 0;
          background-color: #fafafa
        }
        .error{
            color:rgb(192, 31, 31);
        }
        .btn{
            border:none;
            outline: none;
            margin: 8px 0;
        }
        textarea{
            max-height: 30vh;
            min-height: 20vh;
        }
        .send-icon{
            font-size: 20px;
        }
        .form-group{
            padding-top: 10px;
        }
        .form-group input{
        margin: 0 8px 0 8px;
        font-size: 17px;
        }
        .form-group label{
            color: lightslategray;
            font-size: 18px;
        }
        .form-group textarea{
            font-size: 18px;
        }
        tbody{
            max-height: 80vh;
            overflow-y: scroll;
            display: block;
            background-color: #e4eaec;
            opacity: .85;
            text-align: justify;
        }
        tr{
            display: block;
        }
        td,th{
          width: 100%;
        }
        thead{
          background-color:#ffe1c4; 
          color: #757575;
        }
        td:hover{
            cursor: pointer;
            transition: .5s;
            padding-left: 30px;
        }
    </style>  
</head>
<body>
    <div class="container-fluid d-flex justify-content-md-between sticky-top" style="background-color:#3b5765;color:#efefef">
        <p class="pt-3">  
            <a href="index.php" title="back to Dashboard" data-toggle="tooltip" class="text-light pr-3"><i class="fa fa-arrow-left fa-lg"></i></a>
            <a href="#" class="dropdown-toggle text-light pr-3" data-toggle="dropdown" data-target="#Contacts"><i class="fa fa-users fa-lg"> Contacts</i></a>
        </p>
        <button type="button" class="btn btn-outline-light" onclick="sendBulk()"><i class="fa fa-rocket fa-lg text-muted"></i> Send Bulk Sms</button>
    </div>
  <div  id="Contacts">
    <div class="dropdown-menu ">
      <table id="tbl" class="table table-bordered table-hover table-sm">
        <thead>
          <tr>
            <th>Name</th>
            <th>Number</th>
          </tr>
        </thead>
        <tbody>
        <?php 
        //querying the database
        $query = " SELECT first_name, phone FROM `users`; ";
        $result = mysqli_query($conn,$query);
        while ($rows = mysqli_fetch_assoc($result)) { 
        ?> 
        <tr>
          <td><?php echo $rows['first_name']; ?></td>
          <td><?php echo $rows['phone']; ?></td>
        </tr>
        <?php } ?>
        </tbody>
      </table> 
    </div>
    </div> 
   <div class="col-sm-7 mx-auto">
    <form  class="form-horizontal" action="" method="POST">
        <h3 class="text-secondary pt-3" > <i class="fa fa-comments fa-lg"></i> Homeview Messaging</h3>
            <div class="form-group">
                <input class="form-control" type="text" id="fname" name="fname" placeholder="Reciepient's name" value="<?php echo $fname; ?>" readonly>
            </div>
            <div class="form-group">
                <input class="form-control" type="tel" id="phone" placeholder="Cleint's phone number" name="toInput" value="<?php echo $to; ?>" autocomplete="off">
                <div class = "error"> <?php echo $phoneError;?> </div>
            </div>
            <div class="form-group">
                <textarea class="form-control" type="text" placeholder="Say something to your Clients....." name="textInput" rows="5" cols="40" autofocus ><?php echo $Greeting; ?></textarea>
                <div class = "error"><?php echo $textError;?></div>
            </div>
            <div class="form-group justify-content-between">
                <button class="btn btn-success" type="submit" name="send"><i class="fas fa-paper-plane send-icon"> Send</i></button>
                <button class="btn btn-danger" type="reset"><i class="fas fa-trash send-icon"> Clear</i></button>
            </div>    
        </form> 
   </div>
   <div class="container-fluid"> 
        <?php if (isset($_SESSION['messagesuccess'])){
           $res = $_SESSION['messagesuccess'];?>
           <div class="alert alert-success alert-dismissible fade show" role="alert">
           <p><?php echo $res ?></p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>    
        <?php } unset($_SESSION['messagesuccess']) ?>
       </div>
<script>
    var table = document.getElementById('tbl');
    for(var i = 1; i< table.rows.length; i++)
    {
        table.rows[i].onclick = function(){
        document.getElementById("fname").value = this.cells[0].innerHTML;
        document.getElementById("phone").value = this.cells[1].innerHTML;
        };
    }
     function sendBulk(){
            var phoneArray = [];
            var phone;
            for(var i = 1; i < table.rows.length; i++)
            {
                phone = table.rows[i].cells[1].innerHTML;
                phoneArray.push(phone);
            }
          console.log( "Phone Array --> " + phoneArray);
        document.getElementById("phone").value = phoneArray.toString();
        document.getElementById("fname").value = "Bulk sms From Homeview";
        }
</script>
</body>
</html>
<html>