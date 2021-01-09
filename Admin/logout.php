<?php
session_start(); 
unset($username);
session_destroy();
header('location: admin_login.php');
 ?>