<?php
include_once('../config/connection.php');
session_start();
$name =$phone =$email = $customer_id ="";
if (isset($_SESSION['customer_id'])){
$customer_id =  $_SESSION['customer_id'];

$sql = "SELECT first_name,  second_name, phone, email FROM `users` WHERE `customer_id`= '$customer_id';";
$result = mysqli_query($conn,$sql);
$data = mysqli_fetch_assoc($result);
$name = $data['first_name']." ".$data['second_name'];
$phone = $data['phone'];
$email = $data['email'];
$sql = "
SELECT
    users.first_name,
    users.second_name,
    users.email,
    customer_order.order_id,
    houses.type,
    houses.room_no,
    houses.price,
    customer_order.time
    FROM
    users
    INNER JOIN customer_order ON customer_order.customer_id = users.customer_id
    INNER JOIN uploads ON customer_order.upload_id = uploads.upload_id
    INNER JOIN houses ON uploads.room_no = houses.room_no 
    WHERE users.customer_id = $customer_id
";
$result = mysqli_query($conn,$sql);
if (mysqli_num_rows($result) > 0) {
    function exportExcel($result) {
        $timestamp = time();
        global $name;
        global $customer_id;

        $filename = 'Booking Receipt for ' .$name .'-'.$customer_id.'.xls';
        
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        
        $isPrintHeader = false;
        foreach ($result as $row) {
            if (! $isPrintHeader) {
                echo implode("\t", array_keys($row)) . "\n";
                $isPrintHeader = true;
            }
            echo implode("\t", array_values($row)) . "\n";
        }
        exit();
    }
}
else{
    exit();
    header('location: ../home/customer_dashboard.php');
}
exportExcel($result);
}
?>