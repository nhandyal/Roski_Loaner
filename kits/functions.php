<?php
	require_once("../includes/session.php");
	require_once("../includes/db.php");
	require_once("../includes/global.php");
	
	$response;
	$current_time = time();
	
		// Deactivate Kit
		if(isset($_GET['deactivate'])){
				$id = $_GET['deactivate'];
				// Check if kit is currently on loan
				$query = "SELECT status FROM loans WHERE kitid='".$id."' AND status<>4 and status<>6";
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				if(mysql_num_rows($result) != 0){
						$response["status"] = 1;
						$response["message"] = "Kit cannot be deactivated because it is currently on loan or reserverd";
						echo json_encode($response);
						exit(0);
				}
				else{
						//changes status to -1=inactive
						$query = "UPDATE kits SET
										status = -1
										WHERE kitid = '".$id."'";
		
						mysql_query($query);
					
						if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
						
						if(mysql_affected_rows()== 1){
								$response["status"] = 0;
								$response["message"] = "Kit deactivated";
								echo json_encode($response);
								exit(0);
						}
				}
		}
	
	
		//save the kit if submit button clicked
    if(isset($_GET['create']) || isset($_GET['edit'])){	
        //get all values            
        $kitid 				= $_POST['kitid'];
				$deptID 			= $_SESSION['dept'];
        $desc	 				= addslashes(htmlentities(trim($_POST['desc'])));
        $loan_length	= addslashes(htmlentities(trim($_POST['loan_length'])));
        $notes 				= addslashes(htmlentities(trim($_POST['notes'])));
				$status 			= 1;
        $access_areas	= addslashes(htmlentities(trim($_POST['access_areas'])));
				$aa = explode(",",$access_areas);
				

				if($kitid == ""){
						$response["status"] = 1;
						$response["message"] = "Kit ID cannot be blank";
						echo json_encode($response);
						exit(0);
				}
				
				if($loan_length == ""){
						$loan_length = 3;
				}
						
				if(isset($_GET['create'])){
						$query = "insert into kits (kitid, deptID, kit_desc, loan_length, notes, status, created_on) values ('$kitid', '$deptID', '$desc', '$loan_length', '$notes', '$status', $current_time)";
						mysql_query($query);

						if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				} 
				else{
						$query = "UPDATE kits SET
										kit_desc  = '$desc',
										loan_length = '$loan_length',
										updated_on = '$current_time',
										notes = '$notes'
										WHERE kitid = '".$kitid."'
										";
						mysql_query($query);
						
						if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}

						//remove all access_areas and add the current ones again
						$query = "DELETE FROM kits_accessareas WHERE kitid = '".$kitid."'";
						mysql_query($query);
						
						if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				}
				// Add access areas to the kit
				$aa_query = array();
				
				for($i=0; $i < count($aa); $i++){
						array_push($aa_query, "('".$kitid."','".$aa[$i]."')");
				}	
				$access_areas_insert_query = implode(",", $aa_query);
				
				$query = "insert into kits_accessareas (kitid, accessid) values $access_areas_insert_query";
				mysql_query($query);
				
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				if(isset($_GET['create'])){
						$response["status"] = 0;
						$response["message"] = "Success, kit created";
						echo json_encode($response);
						exit(0);
				}
				else{
						$response["status"] = 0;
						$response["message"] = "Success, kit updated";
						echo json_encode($response);
						exit(0);
				}
				
    } //END OF CREATE / EDIT Kit
?>