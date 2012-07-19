<?php
		require_once("../includes/session.php");
		require_once("../includes/db.php");
		require_once("../includes/global.php");
		
		$response;
		$view = $_GET['view'];
		
		
		$iskit = true;
		$query = "SELECT * FROM loans WHERE lid=".$_GET['loanID'];
		$result = mysql_query($query);
		if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
		$r = mysql_fetch_assoc($result);
		$issue_date = time();
		$renew_count = $r['renew_count'];
		$due_date = mktime(13,0,0) + ($r['loan_length']*24*60*60) - 60;
		
		if($r['kitid'] == ""){
				$iskit = false;
				$id = $r['equipmentid'];
		}
		else{
				$id = $r['kitid'];
		}
		
		// Check if item has exceeded its maximum renew count
		// Only if short term loan
		if($view == "short"){
				if($renew_count == 3){
						$response['status'] = 1;
						$response['message'] = "This item has already been renewed a maximum of three times.";
						echo json_encode($response);
						exit(0);
				}
		}
		
		// Check if the item is past due
		if($r['due_date'] <= $issue_date){
				$response['status'] = 1;
				$response['message'] = "This item is past due and cannot be renewed.";
				echo json_encode($response);
				exit(0);
		}
		
		// Check if there is an upcoming reservation during the renew period
		if($iskit)
				$query = "SELECT * FROM loans WHERE kitid = '".$id."' AND issue_date <= ".$due_date." AND issue_date >= ".$issue_date." AND status = 5";
		else
				$query = "SELECT * FROM loans WHERE equipmentid = '".$id."' AND issue_date <= ".$due_date." AND issue_date >= ".$issue_date." AND status = 5";
				
		$result = mysql_query($query);
		if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
		
		if(mysql_num_rows($result) != 0){
				$response['status'] = 1;
				$response['message'] = "This item cannot be renewed because there is an upcoming reservation.";
				echo json_encode($response);
				exit(0);	
		}
		
		$renew_count ++;
		$updateQuery = "UPDATE loans SET
				due_date = ".$due_date.",
				renew_count = ".$renew_count."
				WHERE lid = ".$_GET['loanID'];
		mysql_query($updateQuery);
		
		$response['status'] = 0;
		$response['message'] = "Loan successfully renewed. Due back on ".date("m-d-y -- h:i A" ,$due_date);
		echo json_encode($response);
		exit(0);	
?>