<?php
		require_once("session.php");
		require_once("db.php");
	
		if(isset($_POST['deptID'])){
				$newDept = $_POST['deptID'];
				$uid = $_SESSION['user'];
				
				$updateQuery = "UPDATE users SET deptID=".$newDept." WHERE userid='".$uid."'";
				$result = mysql_query($updateQuery);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				$_SESSION['dept'] = $newDept;
				$response["status"] = 0;
				echo json_encode($response);
				exit(0);
		}
	
		function sqlError($errorNo, $errorMessage){
				$response["status"] = 1;
				$response["message"] = $errorNo.": ".$errorMessage;
				echo json_encode($response);
				exit(0);
		}
?>