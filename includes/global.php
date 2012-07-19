<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

date_default_timezone_set('America/Los_Angeles');
$current_time = time(); //unix timestamp
$admin_email = "andrekel@usc.edu";

function clean_data($str){
	return addslashes(htmlentities(trim($str)));
}

function showError(){
	if(isset($_SESSION['error'])){
		echo $_SESSION['error'];
		unset($_SESSION['error']);
	}
}

function friendlyDate($date){
		if($date == 0){
				return "";
		}
		else{
				return date("D M d, Y - h:ia",$date);
		}
}

function friendlyDateNoTime($date){
		if($date == 0){
				return "";
		}
		else{
				return date("D M d, Y",$date);
		}
}

function friendlyTimeStamp($mysqlTimestamp){
		$mysqlTimestampString = strtotime($mysqlTimestamp);
		return date("l F d, Y - h:i.A",$mysqlTimestampString);
}

function dateadd($day,$toadd){ //input format: d/m/yyyy
	$tmp = explode('/',$day);
	$dadate = mktime(12,59,59,$tmp[0],$tmp[1]+($toadd),$tmp[2]); //due at 12:59 PM
	return $dadate;
}

function add_date($givendate, $days_to_add) {
      	$cd = strtotime($givendate);
      	$newdate = mktime(12, 59, 59, date('m'), date('d') + $days_to_add, date('Y') );
	
	return $newdate;
}

function sqlError($errorNo, $errorMessage){
		$response["status"] = 1;
		$response["message"] = $errorNo.": ".$errorMessage;
		echo json_encode($response);
		exit(0);
}

$cond_array = array('','New','Good','Moderate','Poor','Damaged');
$user_role = array('','Student', 'Work Study', 'Administrator', 'Faculty', 'System Admin', 'Cashier');
$user_status = array('','Unlocked','Locked');
$user_suspended = array('No','Yes'); 
$user_classes = array('209A', '209B', '210', '309', '310', '409', '410');
?>