	<?php
    
    require_once('../includes/db.php');
    
    $userid = "admin";

   
	
	//status = 1 if the account is ACTIVE
    $query = "select * from users where userid = '$userid'";
    $result = mysql_query($query);
    /*$deptQ = deptID;*/
	
	
	
    echo $result; 
    /*echo "Dept ID:" .$deptQ;*/ 
	
	while($row = mysql_fetch_array($result))
  {
  echo $row['userid'] . " " . $row['deptID'];
  echo "<br />";
  }


	

?>
