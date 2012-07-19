<?php
		require_once("../includes/session.php");
		require_once("../includes/db.php");
		require_once("../includes/global.php");
	
	
		/*
		$queryString = "SELECT * FROM equipments_accessareas a";
		echo $queryString;
		echo "<br/>";
		$result = mysql_query($queryString);
		echo mysql_num_rows($result);
		echo "<br/>";
		while($r = mysql_fetch_assoc($result)){
				echo $r['equipmentid']." ";
				$queryString = "SELECT a.equipmentid FROM equipments a WHERE equipmentid='".$r['equipmentid']."'";
				echo $queryString."<br/>";
				if (mysql_num_rows(mysql_query($queryString)) == 0){
						$query = "DELETE FROM equipments_accessareas WHERE equipmentid='".$r['equipmentid']."' AND accessid=".$r['accessid']." AND added_on=".$r['added_on'];
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
		$queryString = "SELECT equipmentid FROM equipments";
		echo $queryString;
		echo "<br/>";
		$result = mysql_query($queryString);
		echo mysql_num_rows($result);
		echo "<br/>";
		echo "<br/>";
		while($r = mysql_fetch_assoc($result)){
				echo $r['equipmentid']." ";
				$queryString = "SELECT a.equipmentid FROM equipments_accessareas a WHERE equipmentid='".$r['equipmentid']."'";
				//echo $queryString."<br/>";
				if (mysql_num_rows(mysql_query($queryString)) == 0){
						echo "DNE ";
						$queryString = "INSERT INTO equipments_accessareas (equipmentid, accessid, added_on) VALUES ('".$r['equipmentid']."', 0, ".$ctime.")";
						echo $queryString."<br/>";
						mysql_query($queryString) or die(mysql_error());
						echo " UPDATED<br/>";
				}
				else{
						echo "EXISTS <br/>";
				}
				//$deleteQuery = "DELETE FROM equipments_accessareas WHERE equipmentid='".$r['equipmentid']."' AND accessid = 0 AND added_on=".$r['added_on'];
				//mysql_query($deleteQuery) or die(mysql_error());
				//echo $deleteQuery." DELETED <br/>";
		}
?>
		