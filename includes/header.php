<?php
        require_once("db.php");
	    require_once("misc.php");
		require_once("global.php");
		date_default_timezone_set('America/Los_Angeles');
		$current_time = time();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title><?php echo $page_title; ?> | Loaner :: Roski School of Fine Arts</title>
		
		<!-- Import all StyleSheets -->
		<link rel="stylesheet" type="text/css" href="../css/reset.css"> 	<!-- Yahoo Reset -->
		<link rel="stylesheet" type="text/css" href="../css/jquery.css"> 	<!-- jQuery Theme -->
		<link rel="stylesheet" type="text/css" href="../css/default.css">
    
		-->
	</head>
	<body>
	<div id="page">
		<p class='error'> </p>
		<div id="header">
			<?php
				//show menu when user is logged in
				if(isset($_SESSION['user'])){
					require_once("menubar.php");
				}
				else{
			?>
				<a href='/loaner/'><h1>Loaner :: Roski School of Fine Arts</h1></a>
			<?php
				}
			?>
		</div>
		<div id="main">
