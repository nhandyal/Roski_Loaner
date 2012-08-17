<?php
		require_once("../includes/session.php");
		require_once("../includes/db-link.php");
		
		// Submit Loan
		$userid = $_POST['userid'];
		$loanItems = $_POST['loanItems'];
		
		mysqli_query($link,"START TRANSACTION");
		
		foreach($loanItems as $loan){
				$itemID = $loan['itemid'];
				$notes = $loan['notes'];
				$type =$loan['type'];
				$loan_type = $loan['loanType'];
				$loan_length = intval($loan['loanLength']);
				$issue_date = time();
				$due_date = mktime(13,0,0) + ($loan_length*24*60*60) - 60;
				$query = "";
				
				$equipmentQuery = "";
				switch($type){
						case "Kit": // Checks if kit is available and if all component equipment are available for the requested dates
								$equipmentQuery = "( kitid='".$itemID."'";
								$query = "SELECT equipmentid FROM equipments WHERE kitid='".$itemID."' AND status<>-1";
								$result = mysqli_query($link,$query);
								if(mysqli_errno($link) != 0){sqlCommRollChanges(1, mysqli_errno($link), mysqli_error($link), $link);}
								if(mysqli_num_rows($result) != 0){
										while($r = mysqli_fetch_assoc($result)){
												$equipmentQuery = $equipmentQuery." OR equipmentid='".$r['equipmentid']."'";
										}
								}
								break;
						case "Equipment": // Checks if the equipment is available. If the equipment is part of a kit, make sure the kit is not reserved/checkedout during the requested dates
								$equipmentQuery = "( equipmentid='".$itemID."'";
								$query = "SELECT kitid FROM equipments WHERE equipmentid='".$itemID."' AND status<>-1";
								$result = mysqli_query($link,$query);
								if(mysqli_errno($link) != 0){sqlCommRollChanges(1, mysqli_errno($link), mysqli_error($link), $link);}
								$r = mysqli_fetch_assoc($result);
								if($r['kitid'] != "") // Make sure there equipment is part of a kit otherwise you will get unexpected sql results
										$equipmentQuery = $equipmentQuery." OR kitid='".$r['kitid']."'";
								break;
				}
				$equipmentQuery = $equipmentQuery." )";
				
				// Check if there is already a reservation within the period selected by the user and if all parts of the item are available
				$query = "SELECT * FROM loans WHERE ".$equipmentQuery." AND due_date >= ".$issue_date." AND issue_date <= ".$due_date." AND (status <> 4 AND status <> 6)";
				$result = mysqli_query($link,$query);
				if(mysqli_errno($link) != 0){sqlCommRollChanges(1, mysqli_errno($link), mysqli_error($link), $link);}
				
				if(mysqli_num_rows($result) != 0){ sqlCommRollChanges(1,0,"There is a conflicting reservation or loan for $type: $itemID",$link);}
				
				
				// Insert loan into loans table
				$insertQuery = "";
				switch($type){
						case "Kit":
								$insertQuery = "INSERT INTO loans (kitid,userid,deptID,issuedBy,issue_date,loan_length,due_date,status,notes) VALUES ('".$itemID."','".$userid."',".$_SESSION['dept'].",'".$_SESSION['user']."',".$issue_date.",".$loan_length.",".$due_date.",".$loan_type.",'".$notes."')";
								break;
						case "Equipment":
								$insertQuery = "INSERT INTO loans (equipmentid,userid,deptID,issuedBy,issue_date,loan_length,due_date,status,notes) VALUES ('".$itemID."','".$userid."',".$_SESSION['dept'].",'".$_SESSION['user']."',".$issue_date.",".$loan_length.",".$due_date.",".$loan_type.",'".$notes."')";
								break;
				}
				mysqli_query($link,$insertQuery);
				if(mysqli_errno($link) != 0){sqlCommRollChanges(1, mysqli_errno($link), mysqli_error($link), $link);}
				
				
		
				// Update Equipment Status
				$updateQuery = "";
				switch($type){
						case "Kit":
								$updateQuery = "UPDATE equipments SET status = 2 WHERE kitid='".$itemID."' AND status<>-1";
								break;
						case "Equipment":
								$updateQuery = "UPDATE equipments SET status = 2 WHERE equipmentid='".$itemID."' AND status<>-1";
								break;
				}
				mysqli_query($link,$updateQuery);
				if(mysqli_errno($link) != 0){sqlCommRollChanges(1, mysqli_errno($link), mysqli_error($link), $link);}
				
				
				if($type == "Kit"){
						// If Kit, Update Kit Status
						$updateQuery = "UPDATE kits SET status = 2 WHERE kitid='".$itemID."' AND status <>-1";
						mysqli_query($link,$updateQuery);
						if(mysqli_errno($link) != 0){sqlCommRollChanges(1, mysqli_errno($link), mysqli_error($link), $link);}
				}
		} // end forEach loop
		
		sqlCommRollChanges(0, 0, "", $link);
		
		function sqlCommRollChanges($status ,$sql_errno, $sql_error, $sqlLink){
				$response = "";
				if($status == 0){
						mysqli_commit($sqlLink);
						$response['status'] = 0;
						$response['message'] = "All loan items were successfully checked out.";
				}
				else{
						mysqli_rollback($sqlLink);
						$response['status'] = 1;
						$response['message'] = $sql_errno.": ".$sql_error." No loan items were checked out.";
				}
				
				echo json_encode($response);
				exit(0);
		}
?>