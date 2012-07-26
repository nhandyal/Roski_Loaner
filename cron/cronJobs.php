<?php

		require_once("../includes/db.php");
		
		$current_time = time();
		
		$query = "SELECT lid, due_date FROM loans WHERE (status=2 OR status=3) AND due_date < ".$current_time;
		$result = mysql_query($query);
		
		// Update loans table with new fine amounts
		while($r = mysql_fetch_assoc($result)){
				$days_late = ceil(($current_time - $r['due_date'])/(24*60*60));
				$fine = $days_late*25;
				$updateQuery = "UPDATE loans SET fine=".$fine.", status=3 WHERE lid=".$r['lid'];
				mysql_query($updateQuery);
		}
		
		// Update users table with new fine amounts
		$query = "SELECT payments.userid, payments.deptID, payments.paymentTotal, fines.userid, fines.deptID, fines.fineTotal FROM (SELECT userid, deptID, sum(fine) AS fineTotal FROM loans GROUP BY userid, deptID) AS fines LEFT JOIN (SELECT userid, deptID, sum(amount) AS paymentTotal FROM finePayments GROUP BY userid, deptID) AS payments ON fines.userid=payments.userid AND fines.deptID=payments.deptID";
		$result = mysql_query($query);
		while($r = mysql_fetch_assoc($result)){
				$userFine = $r['fineTotal']-$r['paymentTotal'];
				if($userFine > 0){
						$updateQuery = "UPDATE users SET fine=".$userFine.", suspended=1 WHERE userid='".$r['userid']."' AND deptID=".$r['deptID'];
						mysql_query($updateQuery);
				}
				
		}
		
		
		// Cancel all expired reservations
		$query = "SELECT lid FROM loans WHERE due_date<=$current_time AND status=5";
		$result = mysql_query($query);
		while($r = mysql_fetch_assoc($result)){
				$updateQuery = "UPDATE loans SET status=6 WHERE lid=".$r['lid'];
				mysql_query($updateQuery);
		}
		
		
?>