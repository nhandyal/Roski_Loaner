<?php
		require_once("../includes/session.php");
		require_once("../includes/db.php");
		require_once("../includes/global.php");
	
	
		/*
		echo "Kits Clean UP <br/>";
		$queryString = "SELECT * FROM kits_accessareas a";
		echo $queryString;
		echo "<br/>";
		$result = mysql_query($queryString);
		echo mysql_num_rows($result);
		echo "<br/>";
		while($r = mysql_fetch_assoc($result)){
				echo $r['kitid']." ";
				$queryString = "SELECT a.kitid FROM kits a WHERE kitid='".$r['kitid']."'";
				if (mysql_num_rows(mysql_query($queryString)) == 0){
						echo "NULL ";
						$query = "DELETE FROM kits_accessareas WHERE kitid='".$r['kitid']."' AND accessid=".$r['accessid']." AND added_on=".$r['added_on'];
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
		$queryString = "SELECT kitid, accessid FROM kits_accessareas";
		echo $queryString;
		echo "<br/>";
		$result = mysql_query($queryString);
		echo mysql_num_rows($result);
		echo "<br/>";
		echo "<br/>";
		while($r = mysql_fetch_assoc($result)){
				$queryString = "SELECT kitid FROM kits WHERE kitid='".$r['kitid']."'";
				if (mysql_num_rows(mysql_query($queryString)) == 0){
						echo $r['kitid']." with accessid ".$r['accessid'];
						echo " HAS NO RELATION TO kits.kitid ------- <br/>";
						//$updateQuery = "UPDATE equipments_accessareas SET accessid=0 WHERE equipmentid='".$r['equipmentid']."'";
						//mysql_query($updateQuery) or die(mysql_error());
						//echo "Fixed Relation<br/>";
				}
		}
		
?>
		