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
				
				
				// Check if the item is available to checkout
				$query = "";
				switch($_GET['type']){
						case "Kit":
								$query = "SELECT status FROM kits WHERE kitid='".$itemID."' AND deptID=".$_SESSION['dept'];
								break;
						case "Equipment":
								$query = "SELECT status, condID FROM equipments WHERE equipmentid='".$itemID."' AND deptID=".$_SESSION['dept'];
								break;
				}
				$r = mysql_fetch_assoc(mysql_query($query));
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				if($r['status'] == 1){
						// item is available, perform further tests to see if item can be checked out
						
						// If item is a kit, check if all component equipment are available
						// If item is an equipment, check to see that it's condition is not missing
						if($_GET['type'] == "Kit"){
								$query = "SELECT equipmentid FROM equipments WHERE (status=2 OR status=7) and kitid='".$itemID."'";
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
						}
						else if($r['condID'] == 6){
								// item is missing
								$response['status'] = 1;
								$response['message'] = "Do you know where this item is? We don't... so itd be great if you could let us know! (its missing)";
								echo json_encode($response);
								exit(0); 
						}
						
						// Check if the user has access to the item
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
								// valid item id, return component equipment
								getListedEquipment($itemID, $_GET['type']);
						}
				}
				else if($r['status'] == 2 || $r['status'] == 7){
						// Item is currently checked out, return due date.
						// One of the following conditions MUST be true
						
						// If item is a kit, return due date off loans table
						if($_GET['type'] == "Kit"){
								$query = "SELECT due_date FROM loans WHERE kitid='".$itemID."' AND (status = 2 OR status = 3 OR status = 7)";
								$result = mysql_query($query);
								if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
								
								$r = mysql_fetch_assoc($result);
								$response['status'] = 1;
								$response['message'] = "Kit is currently checked out. Due back on: ".date("m-d-y -- h:i A" ,$r['due_date']);
								echo json_encode($response);
								exit(0);
						}
						
						// At this point item is an equipment. There are two possibilities for equipment. Check for both
						// 1: equipment is on a direct loan
						// 2: equipment is part of a kit that is on loan
						
						// Check case 1
						$query = "SELECT due_date FROM loans WHERE equipmentid='$itemID' AND ( status = 2 OR status = 3 OR status = 7)";
						$$result = mysql_query($query);
						if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
						
						$r = mysql_fetch_assoc($result);
						$response['status'] = 1;
						$response['message'] = "Equipment is currently checked out. Due back on: ".date("m-d-y -- h:i A" ,$r['due_date']);
						echo json_encode($response);
						exit(0);
						
						// Check case 2
						$query = "SELECT kitid FROM equipments WHERE equipmentid='$itemID'";
						$r = mysql_fetch_assoc(mysql_query($query));
						if ($r['kitid'] != ""){
								$query = "SELECT due_date FROM loans WHERE kitid='".$r['kitid']."' AND (status = 2 OR status = 3 OR status = 7)";
								$result = mysql_query($query);
								
								if(mysql_num_rows($result) != 0){
										$r = mysql_fetch_assoc($result);
										$response['status'] = 1;
										$response['message'] = "Equipment is part of a kit that is currently checked out. Kit due back on: ".date("m-d-y -- h:i A" ,$r['due_date']);
										echo json_encode($response);
										exit(0);
								}
						}
				}
				else{
						// Invalid item id
						$response['status'] = 1;
						$response['message'] = "Invalid ".$_GET['type']." ID";
						echo json_encode($response);
						exit(0);
				}
				
				// Execution should never reach this point. If it has, there was an error processing the request somewhere and a bug should be reported
				$response['status'] = 1;
				$response['message'] = "There was an error processing your request. Please notify an administrator to have the issue resolved.";
				echo json_encode($response);
				exit(0);
		}
		
		// FUNCTIONS
		// Get Equipment Listed and display if they are in usable condition, damaged, or missing
		function getListedEquipment($itemID, $type){
				$allItemsOK = 1;
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
				$includedEquipment = "";
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
						
						$includedEquipment[$i] = $equipmentID;
						
						$inputHTML = $inputHTML.'<input id="input_'.$equipmentID.'" class="equipment-input" type="text" onchange="validateEqID(this)"//>';
						$equipmentHTML = $equipmentHTML.'<div id="'.$equipmentID.'" class="equipment-wrapper';
						if($result['condID'] == 5){
								//broken item
								$equipmentHTML .=' not-scanned broken-notify">';
						}
						else if($result['condID'] == 6){
								//missing item
								$equipmentHTML .=' missing-notify">';
						}
						else{
								//  valid item
								$equipmentHTML .=' not-scanned">';
						}
						$equipmentHTML .= '<div class="equipment" style="width:100%">';
						$equipmentHTML = $equipmentHTML.'<div><p class="details-title" style="width:40%">Equipment ID:</p><p class="details-content" style="width:60%">'.$equipmentID.'</p><div class="clear"></div></div>';
						$equipmentHTML = $equipmentHTML.'<div><p class="details-title">Model:</p><p class="details-content">'.$model.'</p><div class="clear"></div></div>';
						
						if($result['condID'] == 5){
								// item is damaged
								$equipmentHTML = $equipmentHTML.'<div class="status"><p class="details-title">Status:</p><p class="details-content" style="color:white">Damaged</p><div class="clear"></div></div>';
								$equipmentHTML = $equipmentHTML.'</div></div>';
						}
						else if($result['condID'] == 6){
								// item is missing
								$equipmentHTML = $equipmentHTML.'<div class="status"><p class="details-title">Status:</p><p class="details-content" style="color:white">Missing</p><div class="clear"></div></div>';
								$equipmentHTML = $equipmentHTML.'</div></div>';
						}
						else{
								// item is good
								$equipmentHTML = $equipmentHTML.'<div class="status"><p class="details-title">Status:</p><p class="details-content" style="color:white">Good</p><div class="clear"></div></div>';
								$equipmentHTML = $equipmentHTML.'</div></div>';
						}
						
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
				$response['all_items_ok'] = $allItemsOK;
				$response['htmlData'] = $html;
				$response['equipmentIDS'] = $includedEquipment;
				echo json_encode($response);
				exit(0);
		}
?>