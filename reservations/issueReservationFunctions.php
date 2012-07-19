<?php
	require_once("../includes/session.php");
	require_once("../includes/db.php");
	require_once("../includes/global.php");
	
	$current_time = time();
	$response;
		
		// Validate reservation checkout
		// Check if current time is between the bounds of the reservation
		if(isset($_GET['validateCheckoutTime'])){
				$lid = $_GET['validateCheckoutTime'];
				$iskit = true;
				
				$query = "SELECT issue_date, due_date FROM loans WHERE lid=".$lid;
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				$r = mysql_fetch_assoc($result);
				
				//This data is populated into the response to be used if status = 0.
				$response['due_date'] = $r['due_date'];
				
				if($current_time < $r['issue_date']){
						$response['status'] = 1;
						$response['message'] = "This reservation cannot be checked out until ".date("m-d-y -- h:i A" ,$r['issue_date']).".";
						echo json_encode($response);
						exit(0);
				}
				else if($current_time > $r['due_date']){
						$response['status'] = 1;
						$response['message'] = "This reservation has expired.";
						echo json_encode($response);
						exit(0);
				}
				else{
						$query = "SELECT * FROM loans WHERE lid=".$lid;
						$result = mysql_query($query);
						if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
						
						//Ensure all component equipment are available at this moment
						$r = mysql_fetch_assoc($result);
						if($r['equipmentid'] != ""){
								$iskit = false;
								$query = "SELECT equipmentid FROM equipments WHERE (status=2 OR status=7) AND equipmentid='".$r['equipmentid']."'";
						}
						else if($r['kitid'] != ""){
								$query = "SELECT equipmentid FROM equipments WHERE (status=2 OR status=7) and kitid='".$r['kitid']."'";
						}
						
						$result = mysql_query($query);
						if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
						
						
						if(mysql_num_rows($result) != 0){
								$response['status'] = 1;
								$response['message'] = "The following items are unavailable to checkout at this time. Equipment ID: ";
								while($r = mysql_fetch_assoc($result)){
										$response['message'] = $response['message'].$r['equipmentid']." ";
								}
								echo json_encode($response);
								exit(0);
						}
				}
				
				
				$response['status'] = 0;
				echo json_encode($response);
				exit(0);
				
		}
		
		// Checkout loan reservation
		if(isset($_GET['checkoutReservation'])){
				$lid = $_GET['checkoutReservation'];
				$notes = $_GET['notes'];
				$itemID = $_GET['itemID'];
				$type = $_GET['type'];
				$due_date = $_GET['due_date'];
				$componentUpdateQuery = "";
				$kitUpdateQuery = "";
				
				switch($type){
						case "Kit":
								$componentUpdateQuery = "UPDATE equipments SET status = 2 WHERE kitid='".$itemID."' AND status<>-1";
								$kitUpdateQuery = "UPDATE kits SET status=2 WHERE kitid='".$itemID."'";
								break;
						case "Equipment":
								$componentUpdateQuery = "UPDATE equipments SET status = 2 WHERE equipmentid='".$itemID."'";
								break;
				}
				
				// Update loans table with new loan info
				$updateQuery = "UPDATE loans SET
						issuedBy = '".$_SESSION['user']."',
						status = 2,
						notes = '".$notes."'
						WHERE lid=".$lid;
				mysql_query($updateQuery);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				// Update component equipment with status code 2
				mysql_query($componentUpdateQuery);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				// If kit, update kits table
				if($type == "Kit"){
						mysql_query($kitUpdateQuery);
						if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				}
				
				$response['status'] = 0;
				$response['message'] = "Reservation checked out. Due back on ".date("m-d-y -- h:i A" ,$due_date);
				echo json_encode($response);
				exit(0);
		}
		
		// Cancel Reservation
		if(isset($_GET['cancel'])){
				$id = $_GET['cancel'];
		
				$query = "UPDATE loans SET status = 6 WHERE lid=".$id;	// Cancel the reservation
				mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
			
				$response['status'] = 0;
				$response['message'] = "Reservation cancelled";
				echo json_encode($response);
				exit(0);
		}
	
	
		// Validate User Id
		if(isset($_GET['validateUID'])){
				$userid = urldecode($_GET['validateUID']);
				
				$query = "SELECT status, suspended FROM users WHERE userid='".$userid."' AND deptID=".$_SESSION['dept'];
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				if(mysql_num_rows($result) != 1){
						$response['status'] = 1;
						$response['message'] = "Invalid User ID";
						echo json_encode($response);
						exit(0);
				}
				else{
						$r = mysql_fetch_assoc($result);
						if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
						if($r['status'] == 2){
								$response['status'] = 1;
								$response['message'] = "User account is locked";
								echo json_encode($response);
								exit(0);
						}
						else if($r['suspended'] == 1){
								$response['status'] = 1;
								$response['message'] = "User account is suspended";
								echo json_encode($response);
								exit(0);
						}
						else{
								$response['status'] = 0;
								echo json_encode($response);
								exit(0);
						}
				}
		}
		
		
		// Validate Item ID -- This does not check for conflicting loans and reservations
		if(isset($_GET['validateItemID'])){
				$itemID = $_GET['validateItemID'];
				$userid = $_GET['userid'];
				$type = $_GET['type'];
				
				
				// Check if the item exists
				$query = "";
				switch($type){
						case "Kit":
								$query = "SELECT kitid FROM kits WHERE kitid='".$itemID."' AND status > 0 AND deptID=".$_SESSION['dept'];
								break;
						case "Equipment":
								$query = "SELECT equipmentid FROM equipments WHERE equipmentid='".$itemID."' AND status > 0 AND deptID=".$_SESSION['dept'];
								break;
				}
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				if(mysql_num_rows($result) != 1){
						$response['status'] = 1;
						$response['message'] = "Invalid ".$type." ID";
						echo json_encode($response);
						exit(0);
				}
				
				
				// Check if the user has acces to the item
				$query = "SELECT accessid FROM users_accessareas WHERE userid ='".$userid."'";
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				
				switch($type){
						case "Kit":
								$query = "SELECT kitid FROM kits_accessareas WHERE ( accessid = 0";
								break;
						case "Equipment":
								$query = "SELECT equipmentid FROM equipments_accessareas WHERE ( accessid = 0";
								break;
				}
				
				while($r = mysql_fetch_assoc($result)){
						$query = $query." OR accessid=".$r['accessid'];
				}
				
				switch($type){
						case "Kit":
								$query = $query." ) AND kitid =".$itemID;
								break;
						case "Equipment":
								$query = $query." ) AND equipmentid =".$itemID;
								break;
				}
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				if(mysql_num_rows($result) == 0){
						$response['status'] = 1;
						$response['message'] = "User does not have access for this ".strtolower($type);
						echo json_encode($response);
						exit(0);
				}
				
				$response = getEquipment($itemID,$type);
				$response['status'] = 0;
				echo json_encode($response);
				exit(0);
		}
		
		function getEquipment($itemID,$type){
				$query = "";
				
				switch($type){
						case "Kit":
								$query = $query."SELECT * FROM equipments WHERE kitid = '".$itemID."' AND status<>-1";
								break;
						case "Equipment":
								$query = $query."SELECT * FROM equipments WHERE equipmentid = '".$itemID."' AND status<>-1";
								break;
				}
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				$html = "";
				
				while($res = mysql_fetch_assoc($result)){                       
						$html = $html. "<ul>";
						$html = $html. "<li><label>Equipment ID:</label>".$res['equipmentid']."</li>";
						$html = $html. "<li><label>Description:</label>".$res['equipment_desc']."</li>";
						$html = $html. "<li><label>Model:</label>".$res['model']."</li>";
						$html = $html. "<li><label>Notes:</label>".$res['notes']."</li>";
						$html = $html. "</ul><br/>";
				}
				
				switch($type){
						case "Kit":
								$query = "SELECT loan_length FROM kits WHERE kitid = '".$itemID."'";
								break;
						case "Equipment":
								$query = "SELECT loan_lengthEQ FROM equipments WHERE equipmentid = '".$itemID."'";
								break;
				}
				
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				$res = mysql_fetch_assoc($result);
				
				$loan_length;
				switch($type){
						case "Kit":
								$loan_length = $res['loan_length'];
								break;
						case "Equipment":
								$loan_length = $res['loan_lengthEQ'];
								break;
				}
				
				$response['loan_length'] = $loan_length;
				$response['equipmentHTML'] = $html;
				return $response;
		}
		
		
		// Submit Item Reservation
		if(isset($_GET['issue'])){
				$itemID = $_POST['itemid'];
				$userid = urldecode($_POST['userid']);
				$issue_date = mktime(13,00,00, substr($_POST['issue_date'], 0, 2), substr($_POST['issue_date'], 3, 2), substr($_POST['issue_date'], 6, 4));
				$loan_length = $_POST['loan_length'];
				$due_date = $issue_date+(($loan_length)*24*60*60)-60;
				$notes = urldecode($_POST['notes']);
				$type = $_POST['type'];
				
				
				// This builds the WHERE clause to check if all components of the kit / equipment are available duing the reservation period
				$equipmentQuery = "";
				switch($type){
						case "Kit": // Checks if kit is available and if all component equipment are available for the requested dates
								$equipmentQuery = "( kitid='".$itemID."'";
								$query = "SELECT equipmentid FROM equipments WHERE kitid='".$itemID."' AND status<>-1";
								$result = mysql_query($query);
								if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
								if(mysql_num_rows($result) != 0){
										while($r = mysql_fetch_assoc($result)){
												$equipmentQuery = $equipmentQuery." OR equipmentid='".$r['equipmentid']."'";
										}
								}
								break;
						case "Equipment": // Checks if the equipment is available and if the equipment is part of a kit the kit is not reserved/checkedout during the requested dates
								$equipmentQuery = "( equipmentid='".$itemID."'";
								$query = "SELECT kitid FROM equipments WHERE equipmentid='".$itemID."' AND status<>-1";
								$result = mysql_query($query);
								if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
								$r = mysql_fetch_assoc($result);
								if($r['kitid'] != "") // Make sure there equipment is part of a kit otherwise you will get unexpected sql results
										$equipmentQuery = $equipmentQuery." OR kitid='".$r['kitid']."'";
								break;
				}
				$equipmentQuery = $equipmentQuery." )";
				
				// Check if there is already a reservation within the period selected by the user and if all parts of the item are available
				$query = "SELECT * FROM loans WHERE ".$equipmentQuery." AND due_date >= ".$issue_date." AND issue_date <= ".$due_date." AND (status <> 4 AND status <> 6)";
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				if(mysql_num_rows($result) != 0){
						$response['status'] = 1;
						$response['message'] = "There is an existing reservation or loan for the dates selected";
						echo json_encode($response);
						exit(0);	
				}
				
				
				$insertQuery = "";
				switch($type){
						case "Kit":
								$insertQuery = "INSERT INTO loans (kitid,userid,deptID,issuedBy,issue_date,loan_length,due_date,status,notes) VALUES ('".$itemID."','".$userid."',".$_SESSION['dept'].",'".$_SESSION['user']."',".$issue_date.",".$loan_length.",".$due_date.",5,'".$notes."')";
								break;
						case "Equipment":
								$insertQuery = "INSERT INTO loans (equipmentid,userid,deptID,issuedBy,issue_date,loan_length,due_date,status,notes) VALUES ('".$itemID."','".$userid."',".$_SESSION['dept'].",'".$_SESSION['user']."',".$issue_date.",".$loan_length.",".$due_date.",5,'".$notes."')";
								break;
				}
				mysql_query($insertQuery);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				$response['status'] = 0;
				$response['message'] = "Reservation made.";
				echo json_encode($response);
				exit(0);
		}
		
?>