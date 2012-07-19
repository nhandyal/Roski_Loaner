<?php
	require_once("../includes/session.php");
	require_once("../includes/db.php");
	require_once("../includes/global.php");
	
	$_SESSION['role'] = $_SESSION['role'];
	$user = $_SESSION['user'];
	$response;
	
		// Lock User -- Edited
		if(isset($_GET['lock'])){
				$lock = $_GET['lock'];
				$id = $_GET['id'];
				$role = $_GET['role'];
				$query = "SELECT role FROM users WHERE userid='".$id."'";
				$r = mysql_fetch_array(mysql_query($query));
				if($id == $user){ //user can not delete his own account
						$response["status"] = 1;
						$response["message"] = "You cannot lock your own account.";
						echo json_encode($response);
						exit(0);
				}
				else if($r['role'] == 5 && $_SESSION['role'] == 3){ //admin cannot lock a sys admin account, only sys admin can lock sys admin
						$response["status"] = 1;
						$response["message"] = "You cannot lock or un-lock a system admin account.";
						echo json_encode($response);
						exit(0);
				}
				else{
						if($lock == 'true')
								$updateQuery = "UPDATE users SET status = 2 WHERE userid='".$id."'";
						else
								$updateQuery = "UPDATE users SET status = 1 WHERE userid='".$id."'";
								
						mysql_query($updateQuery);
						if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
						
						$response["status"] = 0;
						if($lock == 'true')
								$response["message"] = "Account ".$id." locked.";
						else
								$response["message"] = "Account ".$id." un-locked.";
								
						echo json_encode($response);
						exit(0);
						
				}
		}
	
		//Delete User -- Edited
		if(isset($_GET['delete'])){
				$id = addslashes(trim($_GET['delete']));
				$numRows =  mysql_num_rows(mysql_query("SELECT userid FROM loans WHERE userid='".$id."' AND (status<>4 OR status<>6)"));
				$query = "SELECT role FROM users WHERE userid='".$id."'";
				$r = mysql_fetch_array(mysql_query($query));
				if($id == $user){ //user can not delete his own account
						$response["status"] = 1;
						$response["message"] = "You cannot delete your own account.";
						echo json_encode($response);
						exit(0);
				}
				else if($numRows != 0){ //check if account has outstanding loans
						$response["status"] = 1;
						$response["message"] = "This account has outstanding loans or reservations. It cannot be deleted at this time.";
						echo json_encode($response);
						exit(0);
				}
				else if($r['role'] == 5 && $_SESSION['role'] == 3){ //admin cannot delete a sys admin account, only sys admin can delete sys admin
						$response["status"] = 1;
						$response["message"] = "You cannot delete a system admin account.";
						echo json_encode($response);
						exit(0);
				}
				else{
						$query = "DELETE FROM users WHERE userid='".$id."'";
				
						mysql_query($query);
						if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
						
						$response["status"] = 0;
						$response["message"] = "Account ".$id." deleted.";
						echo json_encode($response);
						exit(0);
						
				}
		}
	
		// Suspend or Enable User -- Edited
		if(isset($_GET['suspend'])){
				$id = $_GET['id'];
				$query = "SELECT role FROM users WHERE userid='".$id."'";
				$r = mysql_fetch_array(mysql_query($query));
				if($id == $user){ //user can not delete his own account
						$response["status"] = 1;
						$response["message"] = "You cannot suspend your own account";
						echo json_encode($response);
						exit(0);
				}
				else if($r['role'] == 5 && $_SESSION['role'] == 3){ //admin cannot suspend a sys admin account, only sys admin can delete sys admin
						$response["status"] = 1;
						$response["message"] = "You cannot suspend a system admin account.";
						echo json_encode($response);
						exit(0);
				}
				else{
						if($_GET['suspend']=='true')
								$query = "UPDATE users SET suspended = 1 where userid = '$id'";
						else if($_GET['suspend']=='false')
								$query = "UPDATE users SET suspended = 0 where userid = '$id'";
								
						mysql_query($query);
						if(mysql_errno() != 0){
								$response["status"] = 1;
								$response["message"] = mysql_errno().": ".mysql_error();
								echo json_encode($response);
								exit(0);
						}
						else{
								$response["status"] = 0;
								if($_GET['suspend']=='true')
										$response["message"] = "Account suspended.";
								else if($_GET['suspend'] == 'false')
										$response["message"] = "Account un-suspended.";
								echo json_encode($response);
								exit(0);
						}
				}
		}
	
	
	
	
		//Suspend or Unsuspend ALL Students -- Edited
		if(isset($_GET['suspend_all'])){
				if($_GET['suspend_all'] == "true")
						$query = "UPDATE users SET suspended = 1 WHERE role = '1' AND deptID=".$_SESSION['dept'];	// suspend all student accounts
				else if($_GET['suspend_all'] == "false")
						$query = "UPDATE users SET suspended = 0 WHERE role = '1' AND deptID=".$_SESSION['dept'];	// un-suspend all student accounts
				
				mysql_query($query);
				if(mysql_errno() != 0){
						$response["status"] = 1;
						$response["message"] = mysql_errno().": ".mysql_error();
						echo json_encode($response);
						exit(0);
				}
				else{
						$response["status"] = 0;
						if($_GET['suspend_all']=='true')
								$response["message"] = "All student accounts suspended";
						else if($_GET['suspend_all'] == 'false')
								$response["message"] = "All student accounts un-suspended";
						echo json_encode($response);
						exit(0);	
				}
		}
		
		// Create or Edit User -- Edited
    if(isset($_GET['create']) || isset($_GET['edit'])){	
				//get all values
				$userid 			= urldecode($_POST['userid']);
        $fname 				= urldecode($_POST['fname']);
        $lname 				= urldecode($_POST['lname']);
        $email 				= urldecode($_POST['email']);
				$phone 				= urldecode($_POST['phone']);
        $address 			= urldecode($_POST['add']);
        $city 				= urldecode($_POST['city']);
        $state 				= urldecode($_POST['state']);
        $zip 					= urldecode($_POST['zip']);
        $deptID 			= urldecode($_POST['deptID']);
				$class 				= urldecode($_POST['class']);
        $userrole 		= urldecode($_POST['role']);
        $notes 				= urldecode($_POST['notes']);
        $status 			= urldecode($_POST['status']);
				$access_areas	= urldecode($_POST['access_areas']);
				$aa 					= explode(",",$access_areas);

				
				if($fname == ""){
						$response["status"] = 1;
						$response["message"] = "First name cannot be blank";
						echo json_encode($response);
						exit(0);
				}
				else if($lname == ""){
						$response["status"] = 1;
						$response["message"] = "Last name cannot be blank";
						echo json_encode($response);
						exit(0);
				}	
				else if($email == ""){
						$response["status"] = 1;
						$response["message"] = "Email cannot be blank";
						echo json_encode($response);
						exit(0);
				}
				else if($phone == ""){
						$response["status"] = 1;
						$response["message"] = "Phone number cannot be blank";
						echo json_encode($response);
						exit(0);
				}
				
				
				if(isset($_GET['create'])){
						$password 	= urldecode($_POST['password']);
						$cpassword 	= urldecode($_POST['cpassword']);
					
						if($userid == ""){
								$response["status"] = 1;
								$response["message"] = "User ID cannot be blank";
								echo json_encode($response);
								exit(0);
						}
						elseif($password == "" || strlen($password) < 6 ){
								$response["status"] = 1;
								$response["message"] = "Invalid Password";
								echo json_encode($response);
								exit(0);
						}
						elseif($password != $cpassword ){
								$response["status"] = 1;
								$response["message"] = "Passwords do not match";
								echo json_encode($response);
								exit(0);
						}
						else{
								//check if userid already exists
								$query = "select * from users WHERE userid = '$userid' LIMIT 1";
								$result = mysql_query($query);
	
								if(mysql_num_rows($result) == 1){
										$response["status"] = 1;
										$response["message"] = "This username is already in use";
										echo json_encode($response);
										exit(0);
								}	
								else{
										$password = sha1($password);
										$query = "insert into users (userid, password, fname, lname, email, phone, address, city, state, zip, deptID, class, role, status, notes, created_on) values ('$userid', '$password', '$fname', '$lname', '$email', '$phone', '$address', '$city', '$state', '$zip', '$deptID', '$class', '$userrole', '$status', '$notes', $current_time)";
										mysql_query($query);
										
										if(mysql_errno() != 0){
												$response["status"] = 1;
												$response["message"] = mysql_errno().": ".mysql_error();
												echo json_encode($response);
												exit(0);
										}
								}
						}
				}	// End CREATE
				else if(isset($_GET['edit'])){	
						$query ="UPDATE users SET
								fname 				= '$fname',
								lname					= '$lname',
								email					= '$email',
								phone					= '$phone',
								address				= '$address',
								city					= '$city',
								state					= '$state',
								zip 					= '$zip',
								class 				= '$class',
								role 					= '$userrole',
								status 				= '$status',
								notes 				= '$notes',
								updated_on  	= $current_time
								WHERE userid	= '$userid'
						";
						
						mysql_query($query);
						if(mysql_errno() != 0){
								$response["status"] = 1;
								$response["message"] = mysql_errno().": ".mysql_error();
								echo json_encode($response);
								exit(0);
						}
						
						//remove all roles and add the current ones again
						$query = "DELETE FROM users_accessareas WHERE userid = '$userid'";
						mysql_query($query);
						if(mysql_errno() != 0){
								$response["status"] = 1;
								$response["message"] = mysql_errno().": ".mysql_error();
								echo json_encode($response);
								exit(0);
						}
						
				} // End EDIT
		
				//add access areas to the account.
				$aa_query = array();
			
				for($i=0; $i < count($aa); $i++){
						array_push($aa_query, "('".$userid."','".$aa[$i]."',".$current_time.")");
				}
		
				$access_areas_insert_query = implode(",", $aa_query);
		
				$query = "insert into users_accessareas (userid, accessid, added_on) values $access_areas_insert_query";
				mysql_query($query);
				if(mysql_errno() != 0){
						$response["status"] = 1;
						$response["message"] = mysql_errno().": ".mysql_error();
						echo json_encode($response);
						exit(0);
				}
				else{
						if(isset($_GET['create'])){
								$response["status"] = 0;
								$response["message"] = "Success, user created";
								echo json_encode($response);
								exit(0);
						}
						else if(isset($_GET['edit'])){
								$response["status"] = 0;
								$response["message"] = "Success, user updated";
								echo json_encode($response);
								exit(0);
						}
				}
    } //END OF CREATE /EDIT USER
?>