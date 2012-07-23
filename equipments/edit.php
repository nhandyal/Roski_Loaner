<?php
		require_once("../includes/session.php");
		$eid = trim($_GET['id']);
		$page_title = "Equipments : Edit : " . $eid . " ";
    require_once("../includes/headerOpen.php"); //opens the head tag
	
		$query = "SELECT equipments.*, conditions.condName, locations.locationName, equipCategory.equipCatName, equipSubCategory.equipSubName FROM equipments, equipCategory, equipSubCategory, conditions, locations WHERE equipments.equipmentid = '$eid' AND equipments.equipCatID = equipCategory.equipCatID AND equipments.equipSubCatID = equipSubCategory.equipSubCatID AND equipments.condID = conditions.condID AND equipments.locationID = locations.locationID ";
		$result = mysql_query($query) or die (mysql_error());
		$res = mysql_fetch_assoc($result);
?>

<script type="text/javascript" src="../js/equipments/edit.js"></script>
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
		<form action="post" class="createForm">
				<fieldset>
						<legend>
								Edit Equipment: <?php echo $res['equipmentid']; ?>
								<div class="cf-links">
										<ul>
												<li><img src='../etc/cross.png' onclick="deactivate(<?php echo "'".$res['equipmentid']."'"; ?>)" title="Deactivate" style="cursor:pointer"/></li>
										</ul>
								</div>
						</legend>
						<div class="pf-element">
								<div class='pf-description-float'>
										Kit ID:
								</div>
								<div class='pf-content-float'>
										<input id="kitid" name="kitid" value="<?php echo $res['kitid']; ?>"/>
										<span class="pf-error">Invalid Kit ID</span>
								</div>
								<div class='clear'></div>                           
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Loan Length:
										<div class='pf-note'>Default length in days.</div>
								</div>
								<div class='pf-content-float'>
										<input name="loanlenEQ" type="text" id="loanlenEQ" value="<?php echo $res['loan_lengthEQ']; ?>"/>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Manufacturer:
								</div>
								<div class='pf-content-float'>
										<input type="text" name="manufacturer" id="manufacturer" value="<?php echo $res['manufacturer']; ?>" />
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Model:
								</div>
								<div class='pf-content-float'>
										<input type="text" name="model" id="model" value="<?php echo $res['model']; ?>" />
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Manufacturer Serial Number:
								</div>
								<div class='pf-content-float'>
										<input type="text" name="manuSerial" id="manufSerial" value="<?php echo $res['manufSerialNum']; ?>" />
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Manufacture Date:
								</div>
								<div class='pf-content-float'>
										<?php
											// create date drop down select
											$manuDate = getdate($res['manufactureDate']);
											
											
											// month
											echo "<select id='manuDate-month' class='date-element manu-changeDay'>";
											for ($i = 1; $i <= 12; $i++) {
												echo "<option value='$i'";
												if ($i == $manuDate['mon']) { echo " selected='selected'";}
												$month_text = date("F", mktime(0, 0, 0, $i+1, 0, 0, 0));
												echo ">$month_text</option>";
											}
											echo "</select>";
											
											// day
											echo "<select id='manuDate-day' class='date-element'>";
											for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN,$manuDate['mon'],$manuDate['year']); $i++){
												echo "<option value='$i'";
												if($i == $manuDate['mday']) { echo " selected='selected'";}
												echo ">$i</option>";
											}
											echo "</select>";
											
											// year
											echo "<select id='manuDate-year' class='date-element manu-changeDay'>";
											for ($i = $manuDate['year']+10; $i >= $manuDate['year']-10; $i--){
												echo "<option value='$i'";
												if($i == $manuDate['year']) { echo " selected='selected'";}
												echo ">$i</option>";
											}
											echo "</select>";
										?>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Expected Lifetime:
										<p class='pf-note'>In years from manufacture date.</p>
								</div>
								<div class='pf-content-float'>
										<input type="text" name="expectedLifetime" id="expectedLifetime" value="<?php echo $res['expectedLifetime']; ?>" />
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Category:
								</div>
								<div class='pf-content-float'>
										<select id="equipCatID" name="equipCatID">
												<option value="<?php echo $res['equipCatID']; ?>"><?php echo $res['equipCatName']; ?></option>
												<?php
														$query = "select * from equipCategory";
														$result = mysql_query($query) or die (mysql_error());
														while($equipCatID = mysql_fetch_assoc($result)){
																echo "<option value='".$equipCatID['equipCatID']."'";
																echo ">".$equipCatID['equipCatName']."</option>";
														}
												?>	
										</select> 
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element" id="equipSubCat">
								<div class='pf-description-float'>
										Sub Category:
								</div>
								<div class='pf-content-float'>
										<select name="equipSubCatID" id="equipSubCatID">
												<option value="<?php echo $res['equipSubCatID']; ?>"><?php echo $res['equipSubName']; ?></option>
												<?php
														$query = "select * from equipSubCategory WHERE equipCatID = '$_GET[equipCatID]'";
														$result = mysql_query($query) or die (mysql_error()); 
														while($row=mysql_fetch_array($result)) {
																echo "<option value='".$row['equipSubCatID']."'";
																echo ">".$row['equipSubName']."</option>";
														} 
												?>
										</select>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Condition:
								</div>
								<div class='pf-content-float'>
										<select id="cond" name="cond">
												<option value="<?php echo $res['condID']; ?>"><?php echo $res['condName']; ?></option>
												<?php
														$query = "select conditions.* from conditions";
														$result = mysql_query($query) or die (mysql_error());
														while($condID = mysql_fetch_assoc($result)){
																echo "<option value='".$condID['condID']."'";
																echo ">".$condID['condName']."</option>";
														}
												?>	
										</select> 
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Location:
								</div>
								<div class='pf-content-float'>
										<select id="location" name="location">
												<option value="<?php echo $res['locationID']; ?>"><?php echo $res['locationName']; ?></option>
												<?php
														$query = "select locations.* from locations";
														$result = mysql_query($query) or die (mysql_error());
														while($locationID = mysql_fetch_assoc($result)){
																echo "<option value='".$locationID['locationID']."'";
																echo ">".$locationID['locationName']."</option>";
														}
												?>	
										</select> 
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Area:
								</div>
								<div class='pf-content-float'>
										<?php
												$query = "select accessid from equipments_accessareas where equipmentid = '$eid'";
												$result = mysql_query($query);
												$aa = array();
												while($a = mysql_fetch_assoc($result)){
														array_push($aa, $a['accessid']);
												}
												
												$query = "select * from accessareas WHERE deptID = '$_SESSION[dept]'";
												$r = mysql_query($query);
												while($area = mysql_fetch_assoc($r)){
										?>	
												<input type="checkbox" name="access_area" class="access_area" <?php if(in_array($area['id'], $aa)){ echo "checked='checked'"; } ?> value='<?php echo $area['id']; ?>'/><?php echo $area['accessarea']; ?><br/>
										<?php
												}
										?>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Description:
								</div>
								<div class='pf-content-float'>
										<textarea name="desc" id="desc" cols="35" rows="5" ><?php echo $res['equipment_desc']; ?></textarea>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Notes:
								</div>
								<div class='pf-content-float'>
										<textarea name="notes" id="notes" cols="35" rows="5" ><?php echo $res['notes']; ?></textarea>
								</div>
								<div class='clear'></div>
						</div>
				</fieldset>
				<br/>
				<fieldset>
						<legend>Purchase Information</legend>
						<div class="pf-element">
								<div class='pf-description-float'>
										Purchase Date:
								</div>
								<div class='pf-content-float'>
										<?php
											// create date drop down select
											$purchDate = getdate($res['purchaseDate']);
											
											// month
											echo "<select id='manuDate-month' class='date-element manu-changeDay'>";
											for ($i = 1; $i <= 12; $i++) {
												echo "<option value='$i'";
												if ($i == $purchDate['mon']) { echo " selected='selected'";}
												$month_text = date("F", mktime(0, 0, 0, $i+1, 0, 0, 0));
												echo ">$month_text</option>";
											}
											echo "</select>";
											
											// day
											echo "<select id='manuDate-day' class='date-element'>";
											echo "Hi";
											for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN,$purchDate['mon'],$purchDate['year']); $i++){
												echo "<option value='$i'";
												if($i == $purchDate['mday']) { echo " selected='selected'";}
												echo ">$i</option>";
											}
											echo "</select>";
											
											// year
											echo "<select id='manuDate-year' class='date-element manu-changeDay'>";
											for ($i = $purchDate['year']+10; $i >= $purchDate['year']-10; $i--){
												echo "<option value='$i'";
												if($i == $purchDate['year']) { echo " selected='selected'";}
												echo ">$i</option>";
											}
											echo "</select>";
										?>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Purchase Price:
								</div>
								<div class='pf-content-float'>
										<input type="text" name="purchasePrice" id="purchasePrice" value="<?php echo $res['purchasePrice']; ?>" />
								</div>
								<div class='clear'></div>
						</div>
				</fieldset>
				<br/>
				<fieldset>
						<legend>Network Information</legend>
						<div class="pf-element">
								<div class='pf-description-float'>
										IP Address:
										<p class="pf-note">(123.456.789.012)</p>
								</div>
								<div class='pf-content-float'>
										<input type="text" name="ipAddress" id="ipAddress" value="<?php echo $res['ipAddress']; ?>" />
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										MAC Address:
								</div>
								<div class='pf-content-float'>
										<input type="text" name="macAddress" id="macAddress" value="<?php echo $res['macAddress']; ?>"  />
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Host Name:
										<p class="pf-note">(hostname.usc.edu)</p>
								</div>
								<div class='pf-content-float'>
										<input type="text" name="hostName" id="hostName" value="<?php echo $res['hostName']; ?>" />                                    
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Connect Type:
										<p class="pf-note">How does it connect to network?</p>
								</div>
								<div class='pf-content-float'>
										<select name="connectType" id="connectType">
												<option value="<?php echo $res['connectType']; ?>"><?php echo $res['connectType']; ?></option>
												<option value="both">Both</option>
												<option value="wired">Wired</option>
												<option value="wireless">Wireless</option>
										</select>
								</div>
								<div class='clear'></div>
						</div>
				</fieldset>
				<br/>
				<fieldset>
						<legend>Warranty Information</legend>
						<div class="pf-element">
								<div class='pf-description-float'>
										Warranty:
								</div>
								<div class='pf-content-float'>
										<textarea name="warrantyInfo" id="warrantyInfo" cols="50" rows="5" ><?php echo $res['warrantyInfo']; ?></textarea>
								</div>
								<div class='clear'></div>
						</div>
				</fieldset>
				<br/>
				<div class="pf-buttons" style="width:140px; margin:auto; float:none !important; padding-top:10px; border:none !important">
						<input type="hidden" id="purchaseDateUnix" value="<?php echo $res['purchaseDate']*1000; ?>"/>
						<input type="hidden" id="manufactureDateUnix" value="<?php echo $res['manufactureDate']*1000; ?>"/>
						<input type="hidden" id="equipmentid" value="<?php echo $eid; ?>"/>
						<input type="button" name="submit" id="submit" value="Submit" onclick="submitForm()"/>&nbsp&nbsp<img id="submitWaiting" class="waiting" src="../etc/loading.gif" width="15" height="15" style="display: none"/>
				</div>
		</form>
</div>
<div class="clear"></div>

<?php
		require_once("../includes/footerNew.php");
?>