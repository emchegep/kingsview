<?php
include_once('../config/connection.php');
include_once('../pdf/fpdf182/fpdf.php');
session_start();
$name =$phone =$email = $customer_id ="";
if (isset($_SESSION['customer_id'])){
$customer_id =  $_SESSION['customer_id'];

class PDF extends FPDF
{
// Page header
    function Header()
    {
        global $orderNo, $customer_id;
        // Logo
       // $this->Image('../images/logo.png',10,10,45);
        // Arial Bold itallic 24
        $this->SetFont('Arial','I',24);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->SetFillcolor(228, 234, 236);
        $this->Cell(80,12,'Customer Order',1,1,'C',true);
        // Line break
        $this->Ln();
        $this->Cell(0,0,'',1,1);
        $this->Ln(1);
        $this->Cell(0,0,'',1,1);
        $this->SetFont('Times','BI',10);
        $this->Cell(0,10,'Reciept no: '.date('y').$orderNo.$customer_id,0,1,'R');
        $this->SetFont('Times','BI',17);
        $this->Cell(0,10,'KingsView Apartments',0,1);
        $this->SetFont('Times','I',13);
        $this->Cell(0,8,'Phone: +254 700 111 111',0,1);
        $this->Cell(0,8,'Email: info@kingsviewapartment.co.ke',0,1);
        $this->Cell(0,8,'Postal Address: 057 Kihunguro, Ruiru P.O.Box 6423 00232',0,1);
         $this -> Cell(0,5,'Website: http://www.kingsview.co.ke/',0,1);
         $this->Ln();
    }// Page footer
    function Footer()
    {
        // Position at 4.5 cm from bottom
        $this->SetY(-45);
        date_default_timezone_set("Africa/Nairobi");
        $this->Cell(0,7,'Order Printed at '.date('H:i:s Y-m-d'),0,1,'C');
        $this->SetFont('Arial','I',12);
        $this -> Cell(0,8,utf8_decode('©')." ".date('Y').' KingsView Apartment. All Rights Reservered.',0,1,'C');
        $this->Cell(0,8,'Powered by KingsView Apartments',0,1,'C');
        $this->SetFont('Arial','I',10);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        
    }
}
$sql = "SELECT first_name,  second_name, phone, email FROM `users` WHERE `customer_id`= '$customer_id';";
$result = mysqli_query($conn,$sql);
$data = mysqli_fetch_assoc($result);
$name = $data['first_name']." ".$data['second_name'];
$phone = $data['phone'];
$email = $data['email'];
$sql = "
SELECT 
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
$data = mysqli_fetch_assoc($result);
$orderNo = $data['order_id'];
// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAuthor('Peter Chege');
$pdf->SetCreator('KingsView Apartment');
$pdf->SetFont('Times','B',12);
$pdf->Cell(0,7,'CustomerId #'.$customer_id,0,1,'R');
$pdf->Cell(0,8,$name,0,1,'R');
$pdf->Cell(0,8,$phone,0,1,'R');
$pdf->Cell(0,8,$email,0,1,'R');

$pdf->Ln();
$pdf->SetFont('Times','I',20);
$pdf->SetFillcolor(228, 234, 236);
$pdf->Cell(0,10,'Order Details',1,1,'C',true);
$pdf->SetFont('Times','B',14);
$pdf->Cell(38,10,'Order Id',1);
$pdf->Cell(38,10,'House Type',1);
$pdf->Cell(38,10,'Room No.',1);
$pdf->Cell(38,10,'Price',1);
$pdf->Cell(38,10,'Book Date/Time',1);

$pdf->SetFont('Times','I',12);
foreach ($result as $rows) {
    $pdf->Ln();
    foreach ($rows as $field)
    $pdf->Cell(38,7,$field,1); 
}
$pdf->Ln(15);
$pdf->Cell(0,5,'NOTE: This is a system generated reciept and therefore, the reciept ',0,1,'C');
$pdf->Cell(0,5,' is not valid without official stump/signature from KingsView Apartment.',0,1,'C');
$pdf->Output();
}
?>