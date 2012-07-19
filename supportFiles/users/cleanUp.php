<?php
		require_once("../includes/session.php");
		require_once("../includes/db.php");
		require_once("../includes/global.php");
	
	
		
		echo "Users Clean UP <br/>";
		/*
		$queryString = "SELECT * FROM users_accessareas a";
		echo $queryString;
		echo "<br/>";
		$result = mysql_query($queryString);
		echo mysql_num_rows($result);
		echo "<br/>";
		while($r = mysql_fetch_assoc($result)){
				echo $r['userid']." ";
				$queryString = "SELECT a.userid FROM users a WHERE userid='".$r['userid']."'";
				if (mysql_num_rows(mysql_query($queryString)) == 0){
						echo "NULL ";
						$query = "DELETE FROM users_accessareas WHERE userid='".$r['userid']."' AND accessid=".$r['accessid']." AND added_on=".$r['added_on'];
						echo $query;
						mysql_query($query);
						echo " Deleted<br/>";
				}
				else{
						echo " Match<br/>";
				}	
		}
		*/
		
		$ctime = time();
		echo $ctime."<br/>";
		$queryString = "SELECT * FROM users_accessareas WHERE accessid > 19";
		echo $queryString;
		echo "<br/>";
		$result = mysql_query($queryString);
		echo mysql_num_rows($result);
		echo "<br/>";
		echo "<br/>";
		while($r = mysql_fetch_assoc($result)){
				echo $r['userid']." ";
				//$queryString = "SELECT userid FROM users_accessareas WHERE userid='".$r['userid']."'";
				//if (mysql_num_rows(mysql_query($queryString)) == 0){
				//		echo " DNE ";
				//		$insertQuery = "INSERT INTO users_accessareas (userid, accessid, added_on) VALUES ('".$r['userid']."', 0,".$ctime.")";
				//		echo $insertQuery."<br/>";
				//}
				//else{
				//		echo " EXISTS<br/>";
				//}
				$updateQuery = "UPDATE users_accessareas SET
						accessid = 0
						WHERE
						userid='".$r['userid']."' AND added_on=".$r['added_on']." AND accessid=".$r['accessid'];
				mysql_query($updateQuery);
				echo $updateQuery." UPDATED <br/>";
		}
		
?>
		