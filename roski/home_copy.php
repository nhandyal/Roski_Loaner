<?php
		$page_title = "Home";	
		require_once("../includes/session.php");
		require_once("../includes/header.php"); //opens the head tag
?>	
		<div class="home_notifications" class="ui-widget">
			<h3 class="ui-widget-header">Expired</h3>
				<div class="ui-widget-inside">Kits</div>
                    <ol>
                        <?php
                            $query = "select kitid, due_date FROM loans WHERE userid = '$user' AND due_date < $current_time AND return_date = 0 AND status = 1  AND equipmentid = ''";
                            $result = mysql_query($query) or die (mysql_error());
                            while($res = mysql_fetch_assoc($result)){
                                echo "<li>".$res['kitid']." - Due Date: ".  friendlyDate($res['due_date']) ."</li>";
                            }
                        ?>
                        <br />
                    </ol>
				<div class="ui-widget-inside">Equipments</div>
                    <ol>
                        <?php
                            $query = "select equipmentid, due_date FROM loans WHERE userid = '$user' AND due_date < $current_time AND return_date = 0 AND status = 1 AND kitid = ''";
                            $result = mysql_query($query) or die (mysql_error());
                            while($res = mysql_fetch_assoc($result)){
                                echo "<li>".$res['equipmentid']." - Due Date: ".  friendlyDate($res['due_date']) ."</li>";
                            }
                        ?>
                    </ol>
		</div>
		<div class="home_notifications" class="ui-widget">
			<h3 class="ui-widget-header">Issued</h3>
				<div class="ui-widget-inside">Kits</div>
                
				<ol>
					<?php
						$query = "select DISTINCT kitid, due_date FROM loans WHERE userid = '$user' AND due_date > $current_time AND return_date = 0 AND status = 1 AND equipmentid = ''";
						$result = mysql_query($query) or die (mysql_error());
						while($res = mysql_fetch_assoc($result)){
							echo "<li>".$res['kitid']." - Due Date: ".  friendlyDate($res['due_date']) ."</li>";
						}
					?>
				</ol>
                <br />
				<div class="ui-widget-inside">Equipment</div>
                
				<ol>
					<?php
						$query = "select equipmentid, due_date FROM loans WHERE userid = '$user' AND due_date > $current_time AND return_date = 0 AND status = 1 AND kitid = ''";
						$result = mysql_query($query) or die (mysql_error());
						while($res = mysql_fetch_assoc($result)){
							echo "<li>".$res['equipmentid']." - Due Date: ".  friendlyDate($res['due_date']) ."</li>";
						}
					?>
				</ol>
		</div>
		<div class="home_notifications" class="ui-widget">
			<h3 class="ui-widget-header">Reservations</h3>
				<div class="ui-widget-inside">Kits</div>
				<ol>
					<?php
						$query = "select * FROM loans WHERE userid = '$user' AND issue_date > $current_time AND status = 2 AND equipmentid = ''";
						$result = mysql_query($query) or die (mysql_error());
						while($res = mysql_fetch_assoc($result)){
							echo "<li>".$res['kitid']." - Issue Date: ".  friendlyDate($res['issue_date']) ."</li>";
						}
					?>
				</ol>
                <br />
                <div class="ui-widget-inside">Equipments</div>
				<ol>
					<?php
						$query = "select * FROM loans WHERE userid = '$user' AND issue_date > $current_time AND status = 2 AND kitid = ''";
						$result = mysql_query($query) or die (mysql_error());
						while($res = mysql_fetch_assoc($result)){
							echo "<li>".$res['equipmentid']." - Issue Date: ".  friendlyDate($res['issue_date']) ."</li>";
						}
					?>
				</ol>
		
		</div>
		<div class="clear"><!-- --></div>
	
	
	<div class="clear"><!-- --></div>
<?php
	require_once("../includes/footerNew.php");
?>