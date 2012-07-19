<?php
    session_start();
    unset($_SESSION);
    session_destroy();
    $page_title = "Logout";

		require_once("../includes/headerOpen.php"); //opens the head tag
?>

				
<?php
		require_once("../includes/headerClose.php"); //closes the head tag and adds all universal header elements
?>	
    
		<div style='width:100%'><h2 style='width:500px; margin:auto; line-height:50px'>Logout successful. Click <a href="index.php" style="font-size:20px">here</a> to login</h2>

<?php
    require_once("../includes/footerNew.php");
?>
