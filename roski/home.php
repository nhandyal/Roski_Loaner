<?php
		$page_title = "Home";	
		require_once("../includes/session.php");
		require_once("../includes/headerOpen.php"); //opens the head tag
?>

		<link rel="stylesheet" type="text/css" href="../css/roski/home.css">
				
<?php
		require_once("../includes/headerClose.php"); //closes the head tag and adds all universal header elements
?>	
		<div id="content" style="width:100% !important">
				<div id="home-content-holder">
						<div class="home-section-holder">
								<div class="home-section-header">Expired</div>
								<div class="home-notification-holder">Kits</div>
								<div class="home-notification-holder">Equipment</div>
						</div>
						<div class="home-section-holder">
								<div class="home-section-header">Issued</div>
								<div class="home-notification-holder">Kits</div>
								<div class="home-notification-holder">Equipment</div>
						</div>
						<div class="home-section-holder">
								<div class="home-section-header">Reservations</div>
								<div class="home-notification-holder">Kits</div>
								<div class="home-notification-holder">Equipment</div>
						</div>
				</div>
		</div>
		<div class="clear"></div>
<?php
	require_once("../includes/footerNew.php");
?>