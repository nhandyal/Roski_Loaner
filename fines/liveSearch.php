<?php
		require_once("../includes/session.php");
		require_once("../includes/db.php");
		require_once("../includes/global.php");
		
		$search = "";
		
		if(isset($_GET['search']))
				$search = $_GET['search'];
		
		$query = "SELECT userNum, userid FROM users WHERE ";
		
		if($search != "")
				$query = $query."userid LIKE '%".$search."%' AND ";
		
		$query = $query." deptID=".$_SESSION['dept']." AND fine <> 0";
		$result = mysql_query($query);
		
		echo "<table id='user-selection-table'>";
				while($r = mysql_fetch_assoc($result)){
						echo "<tr><td id='user-".$r['userNum']."' onclick='loadUserFines(".$r['userNum'].",this)'>".$r['userid']."</td></tr>";
				}
		echo "</table>";
		
?>

