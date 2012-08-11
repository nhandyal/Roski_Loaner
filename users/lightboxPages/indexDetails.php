<?php
		//check if User ID was send or not.
		if(!isset($_GET['id'])){
				header("location: index.php");
		}
		else{
				$id = $_GET['id'];
		}

		require_once("../../includes/session.php");
		require_once("../../includes/db.php");
		require_once("../../includes/global.php");
?>

<div id="content" style="width:100%">
		<?php
				$query = "SELECT users.*, dept.deptName FROM users, dept WHERE users.deptID = dept.deptID AND userid='$id' LIMIT 1";
				$result = mysql_query($query);		
				
				if(mysql_num_rows($result) != 1){
						header("location: index.php?err=201");
				}
				else{
						$res = mysql_fetch_assoc($result);
						$admin = ($_SESSION['role'] == 3 || $_SESSION['role'] == 5);
						$notes = $res['notes'];
				}
		?>
		<form class="createForm">
			<fieldset>
					<legend>
							<div>
									<div style="float: left;">Account Information : <?php echo $res['userid']; ?></div>
										<div class="cf-links">
												<ul>
														<li>
																<?php
																		if($res['suspended'] == "1"){
																				if($admin){
																?>
																						<a href="javascript:suspend(false,'<?php echo $res['userid'];?>')" class="suspended" title="Un-Suspend"><img src='../etc/suspended_user.png' /></a>
																<?php
																				}
																				else
																						echo "<img src='../etc/suspended_user.png' title='suspended' />";
																		}
																		else{
																				if($admin){
																?>
																						<a href="javascript:suspend(true,'<?php echo $res['userid'];?>')" class="not_suspended" title="Suspend"><img src='../etc/not_suspended_user.png' /></a>
																<?php
																				}
																				else
																						echo "<img src='../etc/not_suspended_user.png' title='not-suspended' />";
																		}
																?>
														</li>
														<li>
																<a href="edit.php?id=<?php echo $res['userid']; ?>" class="edit" title="Edit"><img src='../etc/edit.png' /></a>
														</li>
														<li>
																<?php
																if($res['status'] == "1" & $admin){
														?>
																		<a href="javascript:lockUser(true,'<?php echo $res['userid']; ?>',<?php echo $_SESSION['role']; ?>)" class="enable" title="Lock"><img src='../etc/unlock.png' /></a>
														<?php
																}
																else if($admin){
														?>
																		<a href="javascript:lockUser(false,'<?php echo $res['userid']; ?>',<?php echo $_SESSION['role']; ?>)" class="disable" title="Un-Lock"><img src='../etc/lock.png' /></a>
														<?php
																}
														?>
														</li>
														<li>
																<?php
																		if($admin) {
																?>
																				<a href="javascript:deleteAccount('<?php echo $res['userid']; ?>')" class="delete" title="Delete"><img src='../etc/delete.png' /></a>
																<?php
																		}
																?>
														</li>
												</ul>
										</div>
										<div class='clear'></div>
								</div>
						</legend>
					<div class="pf-sidebox">Fine $: 
								<?php 
										switch ($res['fine']) {
												case 0:
														echo '<strong><font color="green">0</font></strong>';
														break;
												default:
														echo '<strong><font color="red">'.$res['fine'].'</font></strong><br />';
														break;
										}
								?>
						</div>
						<div class="pf-element">
										<div class='pf-description-float'>
												First Name:
										</div>
										<div class='pf-content-float'>
												<?php echo $res['fname']; ?>
										</div>
										<div class='clear'></div>
						</div>        
						<div class="pf-element">
										<div class='pf-description-float'>
												Last Name:
										</div>
										<div class='pf-content-float'>
												<?php echo $res['lname']; ?>
										</div>
										<div class='clear'></div>
						</div>        
						<div class="pf-element">
										<div class='pf-description-float'>
												Email:
										</div>
										<div class='pf-content-float'>
												<?php echo $res['email']; ?>
										</div>
										<div class='clear'></div>
						</div>        
						<div class="pf-element">
										<div class='pf-description-float'>
												Phone:
										</div>
										<div class='pf-content-float'>
												<?php echo $res['phone']; ?>
										</div>
										<div class='clear'></div>
						</div>        
						<div class="pf-element">
										<div class='pf-description-float'>
												Address:
										</div>
										<div class='pf-content-float'>
												<?php echo $res['address']; ?>
										</div>
										<div class='clear'></div>
						</div>        
						<div class="pf-element">
								<div class='pf-description-float'>
												City:
										</div>
										<div class='pf-content-float'>
												<?php echo $res['city']; ?>
										</div>
										<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
												State:
										</div>
										<div class='pf-content-float'>
												<?php echo $res['state']; ?>
										</div>
										<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
												Zip:
										</div>
										<div class='pf-content-float'>
												<?php echo $res['zip']; ?>
										</div>
										<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
												Department:
										</div>
										<div class='pf-content-float'>
												<?php echo $res['deptName']; ?>
										</div>
										<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
												Class:
										</div>
										<div class='pf-content-float'>
												<?php echo $res['class']; ?>
										</div>
										<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
												Status:
										</div>
										<div class='pf-content-float'>
												<?php echo $user_status[$res['status']]; ?>
										</div>
										<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
												Suspended:
										</div>
										<div class='pf-content-float'>
												<?php echo $user_suspended[$res['suspended']]; ?>
										</div>
										<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
												Role:
										</div>
										<div class='pf-content-float'>
												<?php echo $user_role[$res['role']]; ?>
										</div>
										<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
												Account Created:
										</div>
										<div class='pf-content-float'>
												<?php echo friendlyDate($res['created_on']); ?>
										</div>
										<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Access Areas:
										</div>
										<div class='pf-content-float'>
												<?php
														$query = "select a.accessarea as accessarea from users_accessareas u JOIN accessareas a ON (u.accessid = a.id) where u.userid = '$id'";
														$result = mysql_query($query) or die (mysql_error());
				
														echo "<ul>";
														while ($res = mysql_fetch_assoc($result)){
																echo "<li>". $res['accessarea'] ."</li>";
														}
														echo "</ul>";
												?>
										</div>
										<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
												Notes
										</div>
										<div class='pf-content-float'>
												<?php echo str_replace("\n","<br/>",$notes); ?>
										</div>
										<div class='clear'></div>
						</div>
				 </fieldset>
		 </form>
</div> <!-- End of Contents -->
<div class="clear"><!-- --></div>
