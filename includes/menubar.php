<div id="department-header">
		<?php
				if($_SESSION['role'] == 5){
						$query = "SELECT * FROM dept";
						$result = mysql_query($query);
						$current_visible = "";
						$select_options = "<div id='select-options'>";
						while($r = mysql_fetch_assoc($result)){
								if($r['deptID'] != 0){
										if($r['deptID'] == $_SESSION['dept'])
												$current_visible = "<div id='current-visible'>".$r['deptName']." Dept</div>";
										else
												$select_options = $select_options."<div class='options' onclick='sysadmToggleDept(".$r['deptID'].")'>".$r['deptName']."</div>";
								}
						}
						
						$select_options = $select_options."</div>";
						echo $current_visible;
						echo $select_options;
				}
				else{
						$query = "SELECT * FROM dept WHERE deptID=".$_SESSION['dept'];
						$result = mysql_query($query);
						$r = mysql_fetch_assoc($result);
						echo "<div id='current-visible'>".$r['deptName']." Dept</div>";
				}
		?>
</div>
<div id="menubar">
		<ul>
				<li><a href="../roski/home.php">Home</a>|</li>
						<?php
								if($_SESSION['role'] == 2 || $_SESSION['role'] == 3 || $_SESSION['role'] == 5){
						?>
										<li><a href="../users/index.php">Users</a>|</li>
										<li><a href="../kits/index.php">Kits</a>|</li>
										<li><a href="../equipments/index.php">Equipment</a>|</li>
										<li><a href="../loans/index.php">Loans</a>|</li>
										<li><a href="../reservations/index.php">Reservations</a>|</li>
										<li><a href="../manage/index.php">System Options</a>|</li>
						<?php
								}
								if($_SESSION['role'] == 3 || $_SESSION['role'] == 5){
						?>
										<li><a href="../reports/index.php">Reports</a>|</li>
						<?php
								}
								if($_SESSION['role'] == 3 || $_SESSION['role'] == 5 || $_SESSION['role'] == 6){
						?>
										<li><a href="../fines/index.php">Fines</a>|</li>
						<?php
								}
						?>
		</ul>
</div><!-- end menubar -->
<div id="logout">
		<strong><?php echo $user ?></strong> | 
		<a href="../roski/change_password.php">Change Password</a> |
		<a href="../roski/logout.php">Logout</a>
</div><!--  end logout -->
<div class="clear"></div>