<?php
		require_once("../includes/session.php");
		if($_SESSION['role'] != 3 && $_SESSION['role'] != 5 && $_SESSION['role'] != 6){
				header('Location: /loaner/roski/home.php');
		}
		$page_title = "Fines";
		require_once("../includes/headerOpen.php");
?>
		
		<link rel="stylesheet" type="text/css" href="../fancybox/jquery.fancybox-1.3.4.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="../css/fines/index.css" media="screen" />
		<script type="text/javascript" src="../fancybox/jquery.fancybox-1.3.4.pack.js"></script>
		<script type="text/javascript" src="../js/fines/index.js"></script>
		
<?php
		require_once("../includes/headerClose.php");
?>
<div id="content">
		<div id='user-selection-container'>
				<div id='user-selection-header'>Users</div>
				<div id='liveSearch-container'>
						<div id='liveSearch-icon'></div>
						<div id='liveSearch-search'>
								<input id='liveSearch' type="text" name='liveSearch' placeholder='Search By User ID'/>
								<input id='liveSearch-previous' type="hidden" value=''/>
						</div>
				</div>
				<div id='user-selection-table-container'></div>
		</div>
		<div id='user-fines-container'>
				<div id='user-fines-placeholder'>Select A User To View Details</div>		
		</div>
		<div class='clear'></div>
</div>
<div class='clear'></div>

<?php
		require_once("../includes/footerNew.php");
?>