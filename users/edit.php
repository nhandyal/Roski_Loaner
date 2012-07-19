<?php
		require_once("../includes/session.php");
		$id = trim($_GET['id']);
		$page_title = "Users : Edit : " . $id . " ";
    require_once("../includes/headerOpen.php"); //opens the head tag
	
		$query = "select * from users where userid = '$id'";
		$result = mysql_query($query) or die (mysql_error());
		$res = mysql_fetch_assoc($result);
		$admin = ($_SESSION['role'] == 3 || $_SESSION['role'] == 5);
?>

<script type="text/javascript" src="../js/users/edit.js"></script>

<?php
	require_once("../includes/headerClose.php"); //close the head tag and add all universal header elements
?>

<div id="sidebar">
		<?php require_once("sidebar.php"); ?>
</div>

<div id="content">
		<form class="createForm">
				<fieldset>
						<legend>Edit User : <?php echo $id; ?>
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
						</legend>
						<div class="pf-element">
								<div class='pf-description-float'>
										<span class="required">*</span>First Name:
								</div>
								<div class='pf-content-float'>
										<input class="pf-text-input" type="text" name="fname" id="fname" value="<?php echo $res['fname']; ?>"/>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										<span class="required">*</span>Last Name:
								</div>
								<div class='pf-content-float'>
										<input class="pf-text-input" type="text" name="lname" id="lname" value="<?php echo $res['lname']; ?>"/>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										<span class="required">*</span>Email:
								</div>
								<div class='pf-content-float'>
										<input class="pf-text-input" type="text" name="email" id="email" value="<?php echo $res['email']; ?>"/>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										<span class="required">*</span>Phone:
								</div>
								<div class='pf-content-float'>
										<input class="pf-text-input" type="text" name="phone" id="phone" value="<?php echo $res['phone']; ?>" />
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Address:
								</div>
								<div class='pf-content-float'>
										<input class="pf-text-input" type="text" name="add" id="add" value="<?php echo $res['address']; ?>"/>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										City:
								</div>
								<div class='pf-content-float'>
										<input class="pf-text-input" type="text" name="city" id="city" value="<?php echo $res['city']; ?>"/>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										State:
								</div>
								<div class='pf-content-float'>
										<select class="pf-text-input" name="state" id="state">
										<?php 
												foreach($us_states as $name => $abb){
														echo "<option value='". $abb ."'";
														if($abb == $res['state']){
																echo " selected ";
														}
														echo">". $name ."</option>";
												}
										?>
										</select>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Zip Code:
								</div>
								<div class='pf-content-float'>
										<input class="pf-text-input" type="text" name="zip" id="zip" value="<?php echo $res['zip']; ?>"/>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Class:
								</div>
								<div class='pf-content-float'>
										<select class="pf-text-input" id="class" name="class">
												<?php
														$query = "SELECT classname FROM classes WHERE deptID = '$_SESSION[dept]'";
														$result = mysql_query($query) or die (mysql_error());
														while($class = mysql_fetch_assoc($result)){
																echo "<option value='".$class['classname']."'";
																if($res['class'] == $class['classname']){
																		echo " selected ";
																}	
																echo ">".$class['classname']."</option>";
														}
												?>	
										</select>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Role:
								</div>
								<div class='pf-content-float'>
										<select class="pf-text-input" id="role" name="role">
												<?php
														for($i = 1; $i < count($user_role); $i++){
																if($_SESSION['role'] == 2 &&($i == 3 || $i==5 || $i==6)){
																		continue;
																}
																if($_SESSION['role'] == 3 && $i==5){
																		continue;
																}
																if($res['role'] == $i){
																		echo "<option value='". $i ."' selected>". $user_role[$i] ."</option>";
																}
																else{
																		echo "<option value='". $i ."'>". $user_role[$i] ."</option>";
																}
														}
												?>
										</select>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Status:
								</div>
								<div class='pf-content-float'>
										<select class="pf-text-input" id="status" name="status">
												<option value="1" <?php if($res['status'] == 1){echo "selected";}?>>Un-locked</option>
												<option value="2" <?php if($res['status'] == 2){echo "selected";}?>>Locked</option>
										</select>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Access Areas:
								</div>
								<div class='pf-content-float'>
										<?php
												$query = "select accessid from users_accessareas where userid = '$id'";
												$result = mysql_query($query) or die (mysql_error());
												$aa = array();

												while($a = mysql_fetch_assoc($result)){
														array_push($aa, $a['accessid']);
												}

												$query = "select * from accessareas WHERE deptID = '$_SESSION[dept]'";
												$r = mysql_query($query) or die (mysql_error());

												while($area = mysql_fetch_assoc($r)){
										?>
														<input type="checkbox" name="access_area" class="access_area" <?php if(in_array($area['id'], $aa)){ echo "checked='checked'"; } ?> value='<?php echo $area['id']; ?>'/><?php echo $area['accessarea']; ?><br/>
										<?php
												}
										?>
								</div>
								<div class='clear'></div>
						</div>
						<?php	
								if(($_SESSION['role'] == 5)) {
						?>
										<div class="pf-element">
												<div class='pf-description-float'>
														Department:
												</div>
												<div class='pf-content-float'>
														<select class="pf-text-input" id="deptID" name="deptID">
																<?php
																		$query = "SELECT * FROM dept";
																		$result = mysql_query($query) or die (mysql_error());
																		while($dept = mysql_fetch_assoc($result)){
																				if($dept['deptID'] != 0){
																						if($res['deptID'] == $dept['deptID'])
																								echo "<option value=" . $dept['deptID'] . " selected='selected'>";
																						else
																								echo "<option value=" . $dept['deptID'] . ">";
																						echo $dept['deptName'] . "</option>";
																				}
																		}
																?>	
														</select>
												</div>
												<div class='clear'></div>
										</div>
						<?php
								}
								else{
						?>
										<input type="hidden" id="deptID" value="<?php echo $_SESSION['dept']; ?>"/>
						<?php
								}
						?>
						<div class="pf-element">
								<div class='pf-description-float'>
										Notes:
								</div>
								<div class='pf-content-float'>
										<textarea class="pf-text-input" name="notes" id="notes" cols="35" rows="5" ><?php echo $res['notes']; ?></textarea>
								</div>
								<div class='clear'></div>
						</div>
						<br />
						<div class="pf-element" style="width:170px; margin:auto; float:none !important; padding-top:10px">
								<input type="hidden" name="userid" id="userid" value="<?php echo $res['userid']; ?>"/>
								<input type="button" name="submit" id="submit" value="Submit" onclick="submitForm()"/>&nbsp;&nbsp;<input type="button" value="Reset" onclick="javascript:(window.location.reload())" />
								&nbsp&nbsp<img id="submitWaiting" class="waiting" src="../etc/loading.gif" width="15" height="15" style="display: none"/>
						</div>
				</fieldset>
		</form>
</div>
<div class="clear"></div>

<?php
	require_once("../includes/footerNew.php");
?>