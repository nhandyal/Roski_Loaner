<?php
		require_once("../includes/session.php");
		require_once("../includes/db.php");
		require_once("../includes/global.php");
		
		$response;
		
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
		
		
		// Validate Item ID Based on Selector
		if(isset($_GET['validateItemID'])){
				$itemID = $_GET['validateItemID'];
				$userid = $_GET['userid'];
				
				// Check if the item exists
				$query = "";
				switch($_GET['type']){
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
						$response['message'] = "Invalid ".$_GET['type']." ID";
						echo json_encode($response);
						exit(0);
				}
				
				// Check if there is a current loan on the item
				// We will check if there is a conflict with a reservation when we submit the loan
				switch($_GET['type']){
						case "Kit":
								$query = "SELECT due_date FROM loans WHERE kitid='".$itemID."' AND (status = 2 OR status = 3 OR status = 7)";
								break;
						case "Equipment":
								$query = "SELECT due_date FROM loans WHERE equipmentid='".$itemID."' AND (status = 2 OR status = 3 OR status = 7)";
								break;
				}
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				if(mysql_num_rows($result) != 0){
						$r = mysql_fetch_assoc($result);
						$response['status'] = 1;
						$response['message'] = $_GET['type']." is currently checked out. Due back on: ".date("m-d-y -- h:i A" ,$r['due_date']);
						echo json_encode($response);
						exit(0);
				}
				
				// If item is a kit, check if all component equipment are available. If item is equipment, make sure equipment is available
				// This does not need to check if the items is deactivated because that is already taken care of earlier
				switch($_GET['type']){
						case "Kit":
								$query = "SELECT equipmentid FROM equipments WHERE (status=2 OR status=7) and kitid='".$itemID."'";
								break;
						case "Equipment":
								$query = "SELECT equipmentid FROM equipments WHERE (status=2 OR status=7) AND equipmentid='".$itemID."'";
								break;
				}
				
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				if(mysql_num_rows($result) != 0){
						$response['status'] = 1;
						$response['message'] = "All requested items are not available at this time to check out. Equipment id:";
						while ($r = mysql_fetch_assoc($result)){
								$response['message'] = $response['message']." ".$r['equipmentid'];
						}
						echo json_encode($response);
						exit(0);
				}
				
				// Check if the user has acces to the item
				$query = "SELECT accessid FROM users_accessareas WHERE userid ='".$userid."'";
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				
				switch($_GET['type']){
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
				
				switch($_GET['type']){
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
						$response['message'] = "User does not have access for this ".strtolower($_GET['type']);
						echo json_encode($response);
						exit(0);
				}
				else{
						$response['status'] = 0;
						echo json_encode($response);
						exit(0);
				}
		}
		
		// Get Item Equipment
		if(isset($_GET['getEQ'])){
				$itemID = $_GET['getEQ'];
				$type = $_GET['type'];
				$query = "";
				
				switch($_GET['type']){
						case "Kit":
								$query = $query."SELECT * FROM equipments WHERE kitid = '".$itemID."' AND status<>-1";
								break;
						case "Equipment":
								$query = $query."SELECT * FROM equipments WHERE equipmentid = '".$itemID."' AND status<>-1";
								break;
				}
				
				$r = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				$i = 0;
				$inputHTML = '<div id="input-wrapper">'; // open 1
				$equipmentHTML = '<div id="equipment-wrapper">'; // open 1
				while ($result = mysql_fetch_assoc($r)){
						$equipmentID = $result['equipmentid'];
						$model = $result['model'];
						$notes = $result['notes'];
						
						if($i%4 == 0){
								$inputHTML = $inputHTML."<div class='holder'>"; // open 2
								$equipmentHTML = $equipmentHTML."<div class='holder'>"; //open 2
						}
						
						$inputHTML = $inputHTML.'<input id="input_'.$equipmentID.'" class="equipment-input" type="text" onchange="validateEqID(this)"//>';
						$equipmentHTML = $equipmentHTML.'<div id="'.$equipmentID.'" class="equipment-wrapper not-scanned"><div class="equipment">';
						$equipmentHTML = $equipmentHTML.'Equipment ID: '.$equipmentID.'<br/>';
						$equipmentHTML = $equipmentHTML.'Model: '.$model.'<br/>';
						$equipmentHTML = $equipmentHTML.'Notes: '.$notes.'<br/>';
						$equipmentHTML = $equipmentHTML.'</div>';
						$equipmentHTML = $equipmentHTML.'<div class="equipment-functions">';
						$equipmentHTML = $equipmentHTML.'<img class="missing-item" src="../etc/grey-cross.png" width="9" height="9" title="Missing Item"/>';
						$equipmentHTML = $equipmentHTML.'<img class="broken-item" src="../etc/wrench_icon.png" width="12" height="12" title="Broken Item"/>';
						$equipmentHTML = $equipmentHTML.'</div></div>';
						
						$i++;
						
						if($i%4 == 0){
								$inputHTML = $inputHTML."<div class='clear'></div></div>";
								$equipmentHTML = $equipmentHTML."<div class='clear'></div></div>";
						}
						
				} // end while
				
				if($i%4 != 0){
						$inputHTML = $inputHTML."<div class='clear'></div></div>";
						$equipmentHTML = $equipmentHTML."<div class='clear'></div></div>";
				}
				
				$inputHTML = $inputHTML."<div class='clear'></div></div>";
				$equipmentHTML = $equipmentHTML."<div class='clear'></div></div>";
				
				$html = "".$inputHTML."".$equipmentHTML;
				
				
				switch($_GET['type']){
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
				switch($_GET['type']){
						case "Kit":
								$loan_length = $res['loan_length'];
								break;
						case "Equipment":
								$loan_length = $res['loan_lengthEQ'];
								break;
				}
				
				$response['status'] = 0;
				$response['loan_length'] = $loan_length;
				$response['message'] = $html;
				echo json_encode($response);
				exit(0);
		}
		
		// Submit Loan
		if(isset($_GET['issue'])){
				$itemID = $_POST['itemid'];
				$userid = $_POST['userid'];
				$notes = $_POST['notes'];
				$loan_length = intval($_POST['loan_length']);
				$type = $_POST['type'];
				$loan_type = $_POST['loan_type'];
				$issue_date = time();
				$due_date = mktime(13,0,0) + ($loan_length*24*60*60) - 60;
				$query = "";
				
				
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
						case "Equipment": // Checks if the equipment is available. If the equipment is part of a kit, make sure the kit is not reserved/checkedout during the requested dates
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
								$insertQuery = "INSERT INTO loans (kitid,userid,deptID,issuedBy,issue_date,loan_length,due_date,status,notes) VALUES ('".$itemID."','".$userid."',".$_SESSION['dept'].",'".$_SESSION['user']."',".$issue_date.",".$loan_length.",".$due_date.",".$loan_type.",'".$notes."')";
								break;
						case "Equipment":
								$insertQuery = "INSERT INTO loans (equipmentid,userid,deptID,issuedBy,issue_date,loan_length,due_date,status,notes) VALUES ('".$itemID."','".$userid."',".$_SESSION['dept'].",'".$_SESSION['user']."',".$issue_date.",".$loan_length.",".$due_date.",".$loan_type.",'".$notes."')";
								break;
				}
				mysql_query($insertQuery);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				
		
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
				mysql_query($updateQuery);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				
				if($type == "Kit"){
						// If Kit, Update Kit Status
						$updateQuery = "UPDATE kits SET status = 2 WHERE kitid='".$itemID."' AND status <>-1";
						mysql_query($updateQuery);
						if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				}
				
				
				$response['status'] = 0;
				$response['message'] = $type." issued. Due back on : ".date("m-d-y -- h:i A" ,$due_date);
				echo json_encode($response);
				exit(0);	
		}
?>