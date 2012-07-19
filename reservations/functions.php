<?php
	require_once("../includes/session.php");
	require_once("../includes/db.php");
	require_once("../includes/global.php");
	
	$current_time = time();
	$response;
		
		// Validate reservation checkout
		// Check if current time is between the bounds of the reservation
		if(isset($_GET['validateCheckoutTime'])){
				$id = $_GET['validateCheckoutTime'];
				
				$query = "SELECT issue_date, due_date FROM loans WHERE lid=".$id;
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				$r = mysql_fetch_assoc($result);
				
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
						$response['status'] = 0;
						echo json_encode($response);
						exit(0);
				}
		}
		
		// Checkout loan reservation
		if(isset($_GET['checkoutLoan'])){
				$lid = $_GET['checkoutLoan'];
				$notes = $_GET['notes'];
				$iskit = true;
				$due_date;
				
				$query = "SELECT * FROM loans WHERE lid=".$lid;
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				$r = mysql_fetch_assoc($result);
				if($r['equipmentid'] != ""){
						$iskit = false;
						$componentUpdateQuery = "UPDATE equipments SET status = 2 WHERE equipmentid='".$r['equipmentid']."'";
				}
				else if($r['kitid'] != ""){
						$componentUpdateQuery = "UPDATE equipments SET status = 2 WHERE kitid='".$r['kitid']."' AND status<>-1";
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
				
				$response['status'] = 0;
				$response['message'] = "Reservation checked out. Due back on ".date("m-d-y -- h:i A" ,$r['due_date']);
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
				$userid = addslashes(htmlentities(trim($_GET['validateUID'])));
				
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
		
		
		// Validate Kit ID
		if(isset($_GET['validateKID'])){
				$kitid = $_GET['validateKID'];
				$userid = $_GET['userid'];
				
				//Check if the kit exists
				$query = "SELECT kitid FROM kits WHERE kitid='".$kitid."' AND deptID=".$_SESSION['dept'];
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				if(mysql_num_rows($result) != 1){
						$response['status'] = 1;
						$response['message'] = "Invalid Kit ID";
						echo json_encode($response);
						exit(0);
				}
				
				
				// Check if the user has acces to the kit
				$query = "SELECT accessid FROM users_accessareas WHERE userid ='".$userid."'";
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				$query = "SELECT kitid FROM kits_accessareas WHERE ( accessid = 0";
				while($r = mysql_fetch_assoc($result)){
						$query = $query." OR accessid=".$r['accessid'];
				}
				$query = $query." ) AND kitid =".$kitid;
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				if(mysql_num_rows($result) == 0){
						$response['status'] = 1;
						$response['message'] = "User does not have access to this kit";
						echo json_encode($response);
						exit(0);
				}
				else{
						$response['status'] = 0;
						echo json_encode($response);
						exit(0);
				}
		}
		
		// Validate EQ ID
		if(isset($_GET['validateEID'])){
				$eid = $_GET['validateEID'];
				$userid = $_GET['userid'];
				
				//Check if the equipment exists
				$query = "SELECT equipmentid FROM equipments WHERE equipmentid='".$eid."' AND status<>-1 AND deptID=".$_SESSION['dept'];
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				if(mysql_num_rows($result) != 1){
						$response['status'] = 1;
						$response['message'] = "Invalid Equipment ID";
						echo json_encode($response);
						exit(0);
				}
				

				// Check if the user has access to the equipment
				$query = "SELECT accessid FROM users_accessareas WHERE userid ='".$userid."'";
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				$query = "SELECT equipmentid FROM equipments_accessareas WHERE ( accessid = 0";
				while($r = mysql_fetch_assoc($result)){
						$query = $query." OR accessid=".$r['accessid'];
				}
				$query = $query." ) AND equipmentid =".$eid;
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				if(mysql_num_rows($result) == 0){
						$response['status'] = 1;
						$response['message'] = "User does not have access to this equipment";
						echo json_encode($response);
						exit(0);
				}
				else{
						$response['status'] = 0;
						echo json_encode($response);
						exit(0);
				}
		}
		
		// Get Kit EQ
		if(isset($_GET['getKEQ'])){
				$kitid = $_GET['getKEQ'];
				$query = "SELECT * FROM equipments WHERE kitid = '".$kitid."' AND status<>-1";
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
				
				$query = "SELECT loan_length FROM kits WHERE kitid = '".$kitid."'";
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				$res = mysql_fetch_assoc($result);
				$loan_length = $res['loan_length'];
				
				$response['status'] = 0;
				$response['loan_length'] = $loan_length;
				$response['message'] = $html;
				echo json_encode($response);
				exit(0);
		}
	
		// Get Equipment Equipment
		if(isset($_GET['getEEQ'])){
				$eid = $_GET['getEEQ'];
				$query = "SELECT * FROM equipments WHERE equipmentid = '".$eid."'";
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
				
				$query = "SELECT loan_lengthEQ FROM equipments WHERE equipmentid = '".$eid."'";
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				$res = mysql_fetch_assoc($result);
				$loan_length = $res['loan_lengthEQ'];
				
				$response['status'] = 0;
				$response['loan_length'] = $loan_length;
				$response['message'] = $html;
				echo json_encode($response);
				exit(0);
		}
		
		// Submit Kit Reservation
		if(isset($_GET['kr'])){
				$kitid = $_POST['kitid'];
				$userid = addslashes(htmlentities(trim($_POST['userid'])));
				$issue_date = mktime(13,00,00, substr($_POST['issue_date'], 0, 2), substr($_POST['issue_date'], 3, 2), substr($_POST['issue_date'], 6, 4));
				$loan_length = ($_POST['loan_length']);
				$due_date = $issue_date+(($loan_length)*24*60*60)-60;
				$notes = $_POST['notes'];
				
				$kitEquipmentQuery = "( kitid='".$kitid."'";
				$query = "SELECT equipmentid FROM equipments WHERE kitid='".$kitid."' AND status<>-1";
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				if(mysql_num_rows($result) != 0){
						while($r = mysql_fetch_assoc($result)){
								$kitEquipmentQuery = $kitEquipmentQuery." OR equipmentid='".$r['equipmentid']."'";
						}
				}
				
				$kitEquipmentQuery = $kitEquipmentQuery." )";
				
				// Check if there is already a reservation within the period selected by the user and if all parts of the kit are available
				$query = "SELECT * FROM loans WHERE ".$kitEquipmentQuery." AND due_date >= ".$issue_date." AND issue_date <= ".$due_date." AND (status <> 4 OR status <> 6)";
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				if(mysql_num_rows($result) != 0){
						$response['status'] = 1;
						$response['message'] = "There is an existing reservation for the dates selected";
						echo json_encode($response);
						exit(0);	
				}
				
				
				$insertQuery = "INSERT INTO loans (kitid,userid,deptID,issuedBy,issue_date,loan_length,due_date,status,notes) VALUES ('".$kitid."','".$userid."',".$_SESSION['dept'].",'".$_SESSION['user']."',".$issue_date.",".$loan_length.",".$due_date.",5,'".$notes."')";
				mysql_query($insertQuery);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				$response['status'] = 0;
				$response['message'] = "Reservation made.";
				echo json_encode($response);
				exit(0);
		}
		
		// Submit EQ Reservation
		if(isset($_GET['er'])){
				$eid = $_POST['eid'];
				$userid = $_POST['userid'];
				$issue_date = mktime(13,00,00, substr($_POST['issue_date'], 0, 2), substr($_POST['issue_date'], 3, 2), substr($_POST['issue_date'], 6, 4));
				$loan_length = ($_POST['loan_length']);
				$due_date = $issue_date+(($loan_length)*24*60*60)-60;
				$notes = $_POST['notes'];
				
				
				$equipmentsKitQuery = "( equipmentid='".$eid."'";
				$query = "SELECT kitid FROM equipments WHERE equipmentid='".$eid."' AND status<>-1";
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				if(mysql_num_rows($result) != 0){
						$r = mysql_fetch_assoc($result);
						$equipmentsKitQuery = $equipmentsKitQuery." OR kitid='".$r['kitid']."'";
				}
				
				$equipmentsKitQuery = $equipmentsKitQuery." )";
				
				
				// Check if there is already a reservation within the period selected by the user
				$query = "SELECT * FROM loans WHERE ".$equipmentsKitQuery." AND due_date >= ".$issue_date." AND issue_date <= ".$due_date." AND (status <> 4 OR status <> 6)";
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				if(mysql_num_rows($result) != 0){
						$response['status'] = 1;
						$response['message'] = "There is an existing reservation for the dates selected";
						echo json_encode($response);
						exit(0);	
				}
				
				$insertQuery = "INSERT INTO loans (equipmentid,userid,deptID,issuedBy,issue_date,loan_length,due_date,status,notes) VALUES ('".$eid."','".$userid."',".$_SESSION['dept'].",'".$_SESSION['user']."',".$issue_date.",".$loan_length.",".$due_date.",5,'".$notes."')";
				mysql_query($insertQuery);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				$response['status'] = 0;
				$response['message'] = "Reservation made.";
				echo json_encode($response);
				exit(0);	
		}
?>

