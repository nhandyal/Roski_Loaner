<?php
		require_once("../includes/session.php");
		require_once("../includes/db.php");
		require_once("../includes/global.php");
		
		$loanID = $_GET['loanID'];
		$newFine = $_GET['newFine'];
		
		
		$query = "SELECT notes, fine FROM loans WHERE lid=$loanID";
		$result = mysql_query($query);
		if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
		$r = mysql_fetch_assoc($result);
		$notes = $r['notes']."\n\n Fine was changed from $".$r['fine']." to $".$newFine." on ".friendlyDate(time())." by ".$_SESSION['user'];
		
		$updateQuery = "UPDATE loans SET fine=$newFine, notes='$notes' WHERE lid=$loanID";
		mysql_query($updateQuery);
		if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}

		$response['status'] = 0;
		echo json_encode($response);
		exit(0);
?>