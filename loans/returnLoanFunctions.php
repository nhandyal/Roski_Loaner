<?php
		require_once("../includes/session.php");
		require_once("../includes/db.php");
		require_once("../includes/global.php");
		
		
		if(isset($_GET['validateUID'])){
				$userid = urldecode($_GET['validateUID']);
				
				
				$query = "SELECT lid FROM loans WHERE userid='".$userid."' AND ( status=2 OR status=3 OR status=7 )";
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				if(mysql_num_rows($result) == 0){
						$response['status'] = 1;
						$response['message'] = "User does not have an outstanding loan";
						echo json_encode($response);
						exit(0);
				}
				
				$response['status'] = 0;
				echo json_encode($response);
				exit(0);
		}
		
		
		// Validate Item ID And Return Appropriate Data
		if(isset($_GET['validateItemID'])){
				$response;
				$result;
				$loan_type;
				$type;
				$itemID = $_GET['validateItemID'];
				$userid = $_GET['userid'];
				
				// Figure out if item id entered is a kit or equipment
				$query = "SELECT status, loan_length FROM loans WHERE kitid='$itemID' AND userid='$userid' AND deptID=".$_SESSION['dept']." AND ( status=2 OR status=3 OR status=7 )";
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				if(mysql_num_rows($result) == 0){
						// itemID is not a kit. Check to see if it is an equipment
						$query = "SELECT status, loan_length FROM loans WHERE equipmentid='$itemID' AND userid='$userid' AND deptID=".$_SESSION['dept']." AND ( status=2 OR status=3 OR status=7 )";
						$result = mysql_query($query);
						if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
						if(mysql_num_rows($result) == 0){
								// itemID is not an equipment ---> invalid itemID
								$response['status'] = 1;
								$response['message'] = "This user does not have any loans with this item id";
								echo json_encode($response);
								exit(0);
						}
						else
								$type = "Equipment";
				}
				else
						$type = "Kit";
						
				
				$response = getEquipment($userid,$itemID,$type);
				$r = mysql_fetch_assoc($result);
				$loan_length = $r['loan_length'];
				switch($r['status']){
						case 7:
								$loan_type = "Long Term";
								break;
						default:
								$loan_type = "Short Term";
								break;
				}
				
				// Build item information html
				$loanInformation = "<div class='pf-element'><div class='pf-description-float'>Item Type:</div><div class='pf-content-float'><input type='text' id='item-type' class='readonly' readonly='readonly' value='".$type."'/></div><div class='clear'></div></div>";
				$loanInformation = $loanInformation."<div class='pf-element'><div class='pf-description-float'>Loan Type:</div><div class='pf-content-float'><input type='text' id='item-type' class='readonly' readonly='readonly' value='".$loan_type."'/></div><div class='clear'></div></div>";
				$loanInformation = $loanInformation."<div class='pf-element'><div class='pf-description-float'>Loan Length:</div><div class='pf-content-float'><input type='text' id='item-type' class='readonly' readonly='readonly' value='".$loan_length."'/></div><div class='clear'></div></div>";
				$response['loanInformation'] = $loanInformation;
				
				$response['status'] = 0;
				echo json_encode($response);
				exit(0);
		}
		
		
		function getEquipment($userid, $itemID, $type){
				$response;
				$query = "";
				
				switch($type){
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
				
				$listedEquipment = "".$inputHTML."".$equipmentHTML;
				
				$response['listedEquipment'] = $listedEquipment;
				return $response;
		}
		
		
		// Return Loan
		if(isset($_GET['return'])){
				$itemID = $_POST['itemid'];
				$userid = $_POST['userid'];
				$notes = urldecode($_POST['notes']);
				$brokenEQ = urldecode($_POST['brokenEQ']);
				$missingEQ = urldecode($_POST['missingEQ']);
				$type = $_POST['type'];
				$return_date = time();
				
				
				// update loan status
				$updateQuery = "";
				switch($type){
						case "Kit":
								$updateQuery = 'UPDATE loans SET status=4, notes="'.$notes.'", return_date='.$return_date.' WHERE kitid="'.$itemID.'" AND userid="'.$userid.'" AND ( status=2 OR status =3 OR status=7 )';
								break;
						case "Equipment":
								$updateQuery = 'UPDATE loans SET status=4, notes="'.$notes.'", return_date='.$return_date.' WHERE equipmentid="'.$itemID.'" AND userid="'.$userid.'" AND ( status=2 OR status =3 OR status=7)';
								break;
				}
				mysql_query($updateQuery);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				
				// Update Equipment Status
				$updateQuery = "";
				switch($type){
						case "Kit":
								$updateQuery = "UPDATE equipments SET status = 1 WHERE kitid='".$itemID."' AND status<>-1";
								break;
						case "Equipment":
								$updateQuery = "UPDATE equipments SET status = 1 WHERE equipmentid='".$itemID."' AND status<>-1";
								break;
				}
				mysql_query($updateQuery);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				
				if($type == "Kit"){
						// If Kit, Update Kit Status
						$updateQuery = "UPDATE kits SET status = 1 WHERE kitid='".$itemID."' AND status <>-1";
						mysql_query($updateQuery);
						if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				}
				
				// append notes to equipment and users if there are broken or missing items
				if($brokenEQ != "N/A"){
						// add a broken by flag on the equipment
						$brokenEQArray = explode(",",$brokenEQ);
						
						$query = "SELECT equipmentid, notes FROM equipments WHERE";
						foreach($brokenEQArray as $key => $eqID){
								if($key == 0)
										$query .= " equipmentid='$eqID'";
								else
										$query .= " OR equipmentid='$eqID'";
						}
						$result = mysql_query($query);
						if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
						while($r = mysql_fetch_assoc($result)){
								$eqID = $r['equipmentid'];
								$notes = $r['notes']."\n\nDamaged by $userid on: ".friendlyDateNoTime($return_date);
								$updateQuery = "UPDATE equipments SET notes='$notes', condID=5 WHERE equipmentID='$eqID'";
								mysql_query($updateQuery);
								if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
						}
						
						// add the broken equipment to the users account
						$query = "SELECT notes FROM users WHERE userID='$userid' AND deptID=".$_SESSION['dept'];
						$result = mysql_query($query);
						if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
						
						$r = mysql_fetch_assoc($result);
						$notes = $r['notes']."\n\n User damaged the following equipment: $brokenEQ";
						$updateQuery = "UPDATE users SET notes='$notes' WHERE userID='$userid' AND deptID=".$_SESSION['dept'];
						mysql_query($updateQuery);
						if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				}
				
				if ($missingEQ != "N/A"){
						// add a broken by flag on the equipment
						$missingEQArray = explode(",",$missingEQ);
						$query = "SELECT equipmentid, notes FROM equipments WHERE";
						foreach($missingEQArray as $key => $eqID){
								if($key == 0)
										$query .= " equipmentid='$eqID'";
								else
										$query .= " OR equipmentid='$eqID'";
						}
						$result = mysql_query($query);
						if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
						while($r = mysql_fetch_assoc($result)){
								$eqID = $r['equipmentid'];
								$notes = $r['notes']."\n\n $userid lost this item on: ".friendlyDateNoTime($return_date);
								$updateQuery = "UPDATE equipments SET notes='$notes', condID=6 WHERE equipmentID='$eqID'";
								mysql_query($updateQuery);
								if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
						}
						
						// add the broken equipment to the users account
						$query = "SELECT notes FROM users WHERE userID='$userid' AND deptID=".$_SESSION['dept'];
						$result = mysql_query($query);
						if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
						
						$r = mysql_fetch_assoc($result);
						$notes = $r['notes']."\n\n User lost the following equipment: $missingEQ";
						$updateQuery = "UPDATE users SET notes='$notes' WHERE userID='$userid' AND deptID=".$_SESSION['dept'];
						mysql_query($updateQuery);
						if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				}
				

				$response['status'] = 0;
				$response['message'] = $type." returned.";
				echo json_encode($response);
				exit(0);	
		}
?>