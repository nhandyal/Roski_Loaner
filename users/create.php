<?php
		require_once("../includes/session.php");
    $page_title = "Users : Create";
    require_once("../includes/headerOpen.php"); //opens the head tag		
?>

<script type="text/javascript" src="../js/users/create.js"></script>

<?php
	require_once("../includes/headerClose.php"); //close the head tag and add all universal header elements
?>

<!--------------------------------------------------------------------------------------------------------------------->
<!------------------------------------------------- BEGIN MAIN PAGE CONTENT ------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->

<div id="sidebar">
		<?php require_once("sidebar.php"); ?>
</div>


    
<div id="content">
		<div id="create_user">
        <form class="createForm" name="theForm" method="post" onsubmit="return Validate(this)">
            <fieldset>
                <legend>Create New User</legend>
                <div class="pf-note"><span class="required">*</span> Required fields<br /><br /></div>
                <div class="pf-element">
                    <div class='pf-description-float'>
                        <span class="required">*</span>User Name:
                    </div>
                    <div class='pf-content-float'>
                        <input type="text" class="pf-text-input" name="userid" id="userid" />
                    </div>
                    <div class='clear'></div>
                </div>
                <div class="pf-element">
                    <div class='pf-description-float'>
                        <span class="required">*</span>Password:
                        <p class="pf-note">6-12 characters alphanumeric</p>
										</div>
										<div class='pf-content-float'>
                        <input type="password" class="pf-text-input" name="password" id="password" />
                    </div>
                    <div class='clear'></div>
                </div>
                <div class="pf-element">
                    <div class='pf-description-float'>
                        <span class="required">*</span>Confirm Password:
                    </div>
                    <div class='pf-content-float'>
                        <input type="password" class="pf-text-input" name="cpassword" id="cpassword" />
                    </div>
                    <div class='clear'></div>
                </div>
                <div class="pf-element">
										<div class='pf-description-float'>
                        <span class="required">*</span>First Name:
										</div>
                    <div class='pf-content-float'>
                        <input type="text" class="pf-text-input" name="fname" id="fname" />
                    </div>
                    <div class='clear'></div>
								</div>
                <div class="pf-element">
                    <div class='pf-description-float'>
                        <span class="required">*</span>Last Name:
                    </div>
                    <div class='pf-content-float'>
                        <input type="text" class="pf-text-input" name="lname" id="lname" />
                    </div>
                    <div class='clear'></div>
                </div>
								<div class="pf-element">
                    <div class='pf-description-float'>
                        <span class="required">*</span>Email:
                    </div>
                    <div class='pf-content-float'>
                        <input type="text" class="pf-text-input" name="email" id="email" />
                    </div>
                    <div class='clear'></div>
								</div>
								<div class="pf-element">
										<div class='pf-description-float'>
												<span class="required">*</span>Phone:
                    </div>
                    <div class='pf-content-float'>
												<input type="text" class="pf-text-input" name="phone" id="phone" />
                    </div>
                    <div class='clear'></div>
                </div>
                <div class="pf-element">
                    <div class='pf-description-float'>
                        Address:
                    </div>
                    <div class='pf-content-float'>
                        <input type="text" class="pf-text-input" name="add" id="add" />
                    </div>
                    <div class='clear'></div>
                </div>
                <div class="pf-element">
                    <div class='pf-description-float'>
												City:
                    </div>
                    <div class='pf-content-float'>
                        <input type="text" class="pf-text-input" name="city" id="city" />
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
																		if($abb == "CA"){
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
                        <input type="text" class="pf-text-input" name="zip" id="zip" />
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
																$query = "select classname from classes WHERE deptID = '$_SESSION[dept]'";
																$result = mysql_query($query) or die (mysql_error());
																while($class = mysql_fetch_assoc($result)){
																		echo "<option value='".$class['classname']."'";
																		echo ">".$class['classname']."</option>";
																}
														?>	
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
														$query = "select * from accessareas WHERE deptID = '$_SESSION[dept]'";
														$r = mysql_query($query) or die (mysql_error());
														while($area = mysql_fetch_assoc($r)){
												?>	
																<input class="access_area" type="checkbox" name="access_area" id="access_Area" value='<?php echo $area['id']; ?>'/><?php echo $area['accessarea']; ?><br/>
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
																								if($_SESSION['dept'] == $dept['deptID'])
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
																		echo "<option value='". $i ."'>". $user_role[$i] ."</option>";
																}
														?>
                        </select>
                    </div>
                    <div class='clear'></div>
                </div>
                <div class="pf-element">
                    <div class='pf-description-float'>
                        Notes:
                    </div>
                    <div class='pf-content-float'>
                        <textarea class="pf-text-input" name="notes" id="notes" cols="35" rows="5" ></textarea>
                    </div>
                    <div class='clear'></div>
                </div>
                <br/>
                <div class="pf-element" style="width:170px; margin:auto; float:none !important; padding-top:10px">
										<input type="hidden" id="status" name="status" value="1" />
										<input type="button" name="submit" id="submit" value="Submit" onclick="submitForm()"/>&nbsp;&nbsp;<input type="button" value="Clear Form" onclick="javascript:(window.location.reload())" />
										&nbsp&nbsp<img id="submitWaiting" class="waiting" src="../etc/loading.gif" width="15" height="15" style="display: none"/>
                </div>
            </fieldset>
				</form>
		</div>
</div>
<div class="clear"></div>

<?php
		require_once("../includes/footerNew.php");
?>