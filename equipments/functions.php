<?php
		require_once("../includes/session.php");
		require_once("../includes/db.php");
		require_once("../includes/global.php");
	
		$current_time = time();
		$response;
		
		// Deactivate Equipment
		if(isset($_GET['deactivate'])){
				$eid = $_GET['deactivate'];
				// Check if equipment is currently on loan or reserved
				$query = "SELECT status FROM loans WHERE equipmentid='".$eid."' AND status<>4 AND status<>6";
				if(mysql_num_rows(mysql_query($query)) != 0){
						$response["status"] = 1;
						$response["message"] = "Item cannot be deactivated because it is currently on loan or reserverd";
						echo json_encode($response);
						exit(0);
				}
				
				// Check if item is part of a kit that is currently on loan or reserved
				$query = "SELECT kitid FROM equipments WHERE equipmentid='$eid'";
				$r = mysql_fetch_assoc(mysql_query($query));
				if($r['kitid'] != ""){
						$query = "SELECT status FROM loans WHERE kitid='".$r['kitid']."' AND status<>4 AND status<>6";
						if(mysql_num_rows(mysql_query($query)) != 0){
								$response["status"] = 1;
								$response["message"] = "Item cannot be deactivated. Parent kit: ".$r['kitid']." is currently on loan or reserved";
								echo json_encode($response);
								exit(0);
						}
				}
				
				$insertQuery = "INSERT INTO inactiveEquipment SELECT equipments.* FROM equipments WHERE equipmentid='$eid'";
				$deleteQuery = "DELETE FROM equipments WHERE equipmentid='$eid'";
				mysql_query($insertQuery);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				mysql_query($deleteQuery);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}

				
				$response["status"] = 0;
				$response["message"] = "Item deactivated";
				echo json_encode($response);
				exit(0);
		}
		
		// Validate Kit ID
		if (isset($_GET['validate'])){
				$eid = addslashes(trim($_GET['validate']));
				// Check if kit exists in kits table
				$query = "SELECT kitid FROM kits WHERE kitid='".$eid."'";
				if(mysql_num_rows(mysql_query($query)) == 1)
						echo 0;
				else
						echo -1;
		}

		// Save the user if submit button clicked
		// Create a new equipment row
		if(isset($_GET['create']) || isset($_GET['edit'])){	
		
				// Get all values
				$equipmentID = urldecode($_POST['equipmentid']);
				$kitid 		= urldecode($_POST['kitid']);
				$loanlenEQ	= urldecode($_POST['loanlenEQ']);
				$createdBy = $_SESSION['user'];
				$manufacturer	= urldecode($_POST['manufacturer']);
				$manufSerialNum	= urldecode($_POST['manufSerial']);
				$manufactureDate = $_POST['manufactureDate'];
				$expectedLifetime = $_POST['expectedLifetime'];
				$expirationDate = $manufactureDate + ($expectedLifetime * 365 * 24 * 60 * 60);
				$model      = urldecode($_POST['model']);
				$equipCatID	= urldecode($_POST['equipCatID']);
				$equipSubCatID	= urldecode($_POST['equipSubCatID']);
				$condID		= urldecode($_POST['cond']);
				$locationID	= urldecode($_POST['location']);
				$owner = urldecode($_POST['owner']);
				$deptID		= $_SESSION['dept'];
				$desc 		= urldecode($_POST['desc']);
				$notes 		= urldecode($_POST['notes']);
				$purchaseDate  =  $_POST['purchaseDate'];
				$purchasePrice	= urldecode($_POST['purchasePrice']);
				$ipAddress	= urldecode($_POST['ipAddress']);
				$macAddress	= urldecode($_POST['macAddress']);
				$hostName	= urldecode($_POST['hostName']);
				$connectType	= urldecode($_POST['connectType']);
				$warrantyInfo	= urldecode($_POST['warrantyInfo']);
				$access_areas	=	urldecode($_POST['access_areas']);
				$aa = explode(",", $access_areas);
	
				
				
				if($equipmentID == ""){
						$response["status"] = 1;
						$response["message"] = "Equipment ID can not be blank"; //kit id is blank
						echo json_encode($response);
						exit(0);
				}
				else if($equipCatID == "-1") {
						$response["status"] = 1;
						$response["message"] = "Category is required";
						echo json_encode($response);
						exit(0);
				}
				else if($equipSubCatID == "-1"){
						$response["status"] = 1;
						$response["message"] = "Sub Category is required";
						echo json_encode($response);
						exit(0);
				}
				else if($condID== "-1") {
						$response["status"] = 1;
						$response["message"] = "Condition is required";
						echo json_encode($response);
						exit(0);
				}
				else if($locationID == "-1") {
						$response["status"] = 1;
						$response["message"] = "Location is required";
						echo json_encode($response);
						exit(0);
				}
				else if($manufactureDate == "0"){
						$response["status"] = 1;
						$response["message"] = "Manufacture date is required";
						echo json_encode($response);
						exit(0);
				}
				else{
						//create an equipment
						if(isset($_GET['create'])){
								$query = "INSERT INTO equipments (equipmentid, kitid, loan_lengthEQ, model, condID, deptID, status,  equipment_desc, notes, createdBy, created_on, equipCatID, equipSubCatID, manufacturer, manufSerialNum, manufactureDate, expectedLifetime, expirationDate, locationID, purchaseDate, purchasePrice, ipAddress, macAddress, hostName, connectType, warrantyInfo) VALUES ('".$equipmentID."', '".$kitid."', '$loanlenEQ', '".$model."', '$condID', '$deptID', 1, '".$desc."', '".$notes."', '".$createdBy."', '$current_time', '$equipCatID', '$equipSubCatID', '".$manufacturer."', '".$manufSerialNum."', '$manufactureDate', '$expectedLifetime', '$expirationDate', '$locationID', '$purchaseDate', '$purchasePrice', '".$ipAddress."', '".$macAddress."', '".$hostName."', '".$connectType."', '".$warrantyInfo."')";
								mysql_query($query);
								if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
						}
						//update an equipment
						else{
								$query = "UPDATE equipments SET
										kitid = '".$kitid."',
										loan_lengthEQ ='".$loanlenEQ."',
										manufacturer = '".$manufacturer."',
										manufactureDate = '$manufactureDate',
										expectedLifetime = '$expectedLifetime',
										expirationDate = '$expirationDate',
										model	=	'".$model."',
										manufSerialNum = '".$manufSerialNum."',
										equipCatID	=	'$equipCatID',
										equipSubCatID	=	'$equipSubCatID',
										locationID	=	'$locationID',
										condID	=	'$condID',
										equipment_desc	=	'".$desc."',
										notes	=	'".$notes."',
										purchaseDate	=	'$purchaseDate',
										purchasePrice	=	'$purchasePrice',
										ipAddress	=	'".$ipAddress."',
										macAddress	=	'".$macAddress."',
										hostName	=	'".$hostName."',
										connectType	=	'".$connectType."',
										warrantyInfo	=	'".$warrantyInfo."',
										updated_on = '$current_time'
										WHERE equipmentid = '$equipmentID'
								";
								
								
								mysql_query($query);
								if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
								
								//remove all access areas and add the current ones again
								$query = "delete from equipments_accessareas where equipmentid = '$equipmentID'";
								mysql_query($query);
								if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
						}
						
						//add access areas to the account.
						$aa_query = array();
			
						for($i=0; $i < count($aa); $i++){
								array_push($aa_query, "('".$equipmentID."','".$aa[$i]."')");
						}
		
						$access_areas_insert_query = implode(",", $aa_query);
		
						$query = "insert into equipments_accessareas (equipmentid, accessid) values $access_areas_insert_query";
						mysql_query($query);
		
						if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
						
						if(isset($_GET['create'])){
								$response["status"] = 0;
								$response["message"] = "Success, Equipment Created";
						}
						else if(isset($_GET['edit'])){
								$response["status"] = 0;
								$response["message"] = "Success, Equipment Updated";
						}
						echo json_encode($response);
						exit(0);
				}
		} //END OF CREATE / EDIT Equipment
?>



