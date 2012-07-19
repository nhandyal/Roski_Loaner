<?php
	session_start();
	require_once('../includes/db.php');
    
    $userid = addslashes(trim($_POST['username']));
    $password = sha1(addslashes(trim($_POST['password'])));
   
	
		//status = 1 if the account is ACTIVE
    $query = "select * from users where userid='$userid' AND password = '$password' AND status = '1' LIMIT 1";
		$result = mysql_query($query) or die (mysql_error());
    
    /*$dept = mysql_fetch_($result);
		$deptid = $dept['deptID'];*/
	
    /*echo $query; 
    echo $deptQ; 
		*/
	
	
    if(mysql_num_rows($result) == 1){
        //user successfully authenticated
        
        //save required data in Session.
        $_SESSION['user'] = $userid;
		//$_SESSION['dept'] = $deptid;
        
        $res = mysql_fetch_assoc($result);
        $_SESSION['role'] = $res['role'];
		$_SESSION['dept'] = $res['deptID'];
		$queryDept = "select * from dept WHERE deptID=".$res['deptID'];
		$resultDept = mysql_query($queryDept) or die (mysql_error());
		$resDept = mysql_fetch_assoc($resultDept);
		$_SESSION['deptN'] = $resDept['deptName'];
		
        //redirect to home page
        header('location: home.php');
    }
    else{
        //authentication failed, redirect back to login page.
        $_SESSION['error'] = "Authentication failed. Please try again.";
        header('location: index.php?err=1');
    }

	
?>
