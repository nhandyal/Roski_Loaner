<?php
		require_once("../includes/session.php");
		require_once("../includes/db.php");
		require_once("../includes/global.php");
		
		$userid = $_GET['userid'];
		$userid = urldecode($userid);
		$paymentAmount = $_GET['paymentAmount'];
		$accepted_by = $_SESSION['user'];
		$updateQuery = "";
		
		if($paymentAmount == 0){exit(0);}
		
		$insertQuery = "INSERT INTO fines (userid, amount, accepted_by, timestamp, deptID) VALUES ('".$userid."',".$paymentAmount.",'".$accepted_by."',".time().",'".$_SESSION['dept']."')";
		mysql_query($insertQuery);
		if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
		$query = "SELECT fine FROM users WHERE userid='".$userid."'";
		$result = mysql_query($query);
		if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
		$r = mysql_fetch_assoc($result);
		$fine = $r['fine'];
		$fine = $fine - $paymentAmount;
		
		if($fine == 0)
				$updateQuery = "UPDATE users SET fine=$fine, suspended=0 WHERE userid='$userid'";
		else
				$updateQuery = "UPDATE users SET fine=$fine WHERE userid='$userid'";
				
		mysql_query($updateQuery);
		if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
		
		
		$response['status'] = 0;
		echo json_encode($response);
		exit(0);
		
?>

