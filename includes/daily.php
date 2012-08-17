<?php

	require_once("../includes/db.php");
	require_once("../includes/global.php");
	echo "CRON Process Start<br>";

    	echo "Deleting expired Reservations<br>";
	$current_time = time();

	$suspend_query = "select distinct userid from loans where status = 1 AND $current_time >= due_date" ;

	echo "Delete Reservations before ". friendlyDate($current_time)."<br>";
	$query = "update loans set status = 4 where status = 2 AND $current_time - (issue_date + (60*60*24*3)) >= 0"; //canceling reservation on 3rd day  
	$result = mysql_query($query) or die (mysql_error());
	echo mysql_affected_rows()." reservations canceled<br>";
	
	echo "Suspending the accounts who's loan have passed the due date: ". friendlyDate($current_time) ."<br/>";

	$query = "update users set suspended = 1 where userid IN ($suspend_query)";
	echo $query."<br>";
	mysql_query($query) or die (mysql_error());

	//email all users abt their account suspension
	$query = "select fname, lname, userid, email from users where userid IN ($suspend_query)";
	$result = mysql_query($query) or die (mysql_error());

	while($r = mysql_fetch_assoc($result)){
		$subj = "Loaner Account Suspension";

		$body  = "Dear ".$r['fname']." ".$r['lname'].",<br>";
		$body .= "Your Loaner account has been suspended since your loan has passed the due date.<br/>Thanks,<br/>Admin - Loaner";

		$headers = "From: ".$admin_email;
		$headers .= "Reply-To: ".$admin_email."\r\n";
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		mail($r['email'], $subj, $body, $headers);
	}
	
	echo mysql_affected_rows()." accounts suspended<br>";
	echo "CRON Process End<br>";
