<?php
session_start();
 if(!isset($_SESSION['user'])){
 	//unset($_SESSION);
 	header('location: ../roski/logout.php?err=2');
 }
 $user = $_SESSION['user'];
 date_default_timezone_set('America/Los_Angeles');

?>
