<?php 
include_once('../config/connection.php');

// check wheter the export button was clickeds
if (isset($_POST['export'])) {
	$sql = "SELECT `customer_id`, `first_name`, `second_name`, `email`,`phone`, `postal_address` FROM `users`";
	$result = mysqli_query($conn,$sql);
	if (mysqli_num_rows($result) > 0) {
		//call a function to generate excel report...
		exportExcel($result);
	}else{
		header('location: index.php');
	}
}
function exportExcel($result) {
    $date = date('jS M Y');
    $filename = 'Registered Customers as at '.$date.'.xls';
    
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    
    $isPrintHeader = false;
    echo "HOMEVIEW APARTMENTS CUSTOMER REGISTRATION REPORT AS AT ".$date."\n"; 
    foreach ($result as $row) {
        if (! $isPrintHeader) {
            echo implode("\t", array_keys($row)) . "\n";
            $isPrintHeader = true;
        }
        echo implode("\t", array_values($row)) . "\n";
    }
    exit();
}
?>