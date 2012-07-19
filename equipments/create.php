<?php
		require_once("../includes/session.php");
    $page_title = "Equipment : Create";
    require_once("../includes/headerOpen.php"); //opens the head tag		
?>

<script type="text/javascript" src="../js/equipments/create.js"></script>

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
						<legend>Create New Equipment</legend>
						<div class="pf-note"><span class="required">*Required fields<br/><br/></div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Equipment ID<span class="required">*</span>:
										<p class="pf-note">(Barcode) NUMERIC only.</p>
								</div>
								<div class='pf-content-float'>
										<input type="text" name="equipmentid" id="equipmentid" >
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Kit ID:  
										<p class="pf-note">(Leve blank if it will not belong to a kit)</p>
								</div>
								<div class='pf-content-float'>
										<input type="text" name="kitid" id="kitid"/>
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
										<input name="loanlenEQ" type="text" id="loanlenEQ" value="3"/>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Manufacturer:
								</div>
								<div class='pf-content-float'>
										<input type="text" name="manufacturer" id="manufacturer" />
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Manufacturer Serial #:  
										<p class="pf-note">(Found on product)</p>
								</div>
								<div class='pf-content-float'>
										<input type="text" name="manufSerial" id="manufSerial"/>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Manufacture Date:<span class="required">*</span>:
								</div>
								<div class='pf-content-float'>
										<input type="text" name="manufactureDate" id="manufactureDate" />
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Expected Lifetime:
										<p class='pf-note'>In years from manufacture date.</p>
								</div>
								<div class='pf-content-float'>
										<input type="text" name="expectedLifetime" id="expectedLifetime" value='4' />
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Model:
								</div>
								<div class='pf-content-float'>
										<input type="text" name="model" id="model"/>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Category<span class="required">*</span>:
								</div>
								<div class='pf-content-float'>
										<select name="equipCatID" id="equipCatID" onChange="getSubCat('findSubCat.php?equipCatID='+this.value)">
												<option value="-1">---Select Category---</option>
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
						<div class="pf-element" id="equipSubCat"></div> 	
						<div class="pf-element">
								<div class='pf-description-float'>
										Condition<span class="required">*</span>:
								</div>
								<div class='pf-content-float'>
										<select id="cond" name="cond">
												<option value="-1">---Select---</option>
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
										Location<span class="required">*</span>:  
										<p class="pf-note">Where is the product located</p>
								</div>
								<div class='pf-content-float'>
										<select id="location" name="location">
												<option value="-1">---Select---</option>
												<?php
														$query = "select * from locations WHERE deptID = '$_SESSION[dept]' ";
														$result = mysql_query($query) or die (mysql_error());
														while($locationsid = mysql_fetch_assoc($result)){
																echo "<option value='".$locationsid['locationID']."'";
																echo ">".$locationsid['locationName']."</option>";
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
						<div class="pf-element">
								<div class='pf-description-float'>
										Description:
								</div>
								<div class='pf-content-float'>
										<textarea  name="desc" id="desc" rows="5" cols="50"></textarea>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Notes:
								</div>
								<div class='pf-content-float'>
										<textarea name="notes" id="notes" cols="50" rows="5" ></textarea>
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
										<input type="text" name="purchaseDate" id="purchaseDate" />
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Purchase Price:
								</div>
								<div class='pf-content-float'>
										<input type="text" name="purchasePrice" id="purchasePrice" />
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
										<input name="ipAddress" type="text" id="ipAddress" value="000.000.000.000" />
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										MAC Address:  
										<p class="pf-note">(00:00:00:00:00:00)</p>
								</div>
								<div class='pf-content-float'>
										<input name="macAddress" type="text" id="macAddress" value="00:00:00:00:00:00" />
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Host Name:  
										<p class="pf-note">(hostname.usc.edu)</p>
								</div>
								<div class='pf-content-float'>
										<input name="hostName" type="text" id="hostName" value="hostname.usc.edu" />
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
												<option value="none">None</option>
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
										<textarea name="warrantyInfo" id="warrantyInfo" cols="50" rows="5" ></textarea>
								</div>
								<div class='clear'></div>
						</div>
				</fieldset>
				<div class="pf-element" style="width:200px; margin:auto; float:none !important; padding-top:10px">
						<input type="button" name="submit" id="submit" value="Submit" onclick="submitForm()"/>&nbsp;&nbsp;<input type="reset" value="Clear Form" />&nbsp&nbsp<img id="submitWaiting" class="waiting" src="../etc/loading.gif" width="15" height="15" style="display: none"/>
						<input type='hidden' id='manufactureDateUnix' value='0'/>
						<input type='hidden' id='purchaseDateUnix' value='0'/>
				</div>
		</form>
</div>
<div class="clear"></div>

<?php
		require_once("../includes/footerNew.php");
?>
