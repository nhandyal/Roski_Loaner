<?php
    require_once("db.php");
	  require_once("misc.php");
		require_once("global.php");
		date_default_timezone_set('America/Los_Angeles');
		$current_time = time();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title><?php echo $page_title; ?> | Loaner :: Roski School of Fine Arts</title>
		
		<!-- Import All Universal StyleSheets -->
		<link rel="stylesheet" type="text/css" href="../css/global/reset.css"> 															<!-- Yahoo Reset -->
		<link rel="stylesheet" type="text/css" href="../css/global/jquery.css"> 														<!-- jQuery Theme -->
		<link rel="stylesheet" type="text/css" href="../css/global/default.css">													<!-- Universal site elemetns -->
		
		<!-- Import Global Javascripts -->
		<script language="JavaScript" type="text/javascript" src="../js/global/jquery.js"></script> 								<!-- jQuery Framework -->
		<script language="JavaScript" type="text/javascript" src="../js/global/jquery_ui.js"></script> 							<!-- jQuery UI Framework -->
		<script language="JavaScript" type="text/javascript" src="../js/global/jquery.scrollTo-min.js"></script> 		<!-- jQuery scrollTo plugin -->
		<script language="JavaScript" type="text/javascript" src="../js/global/jQuery.tableSort-min.js"></script> 	<!-- jQuery tableSorter plugin -->
		<script language="JavaScript" type="text/javascript" src="../js/global/default.js"></script> 								<!-- Default Javascript -->
		