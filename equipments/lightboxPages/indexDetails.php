<?php
		//check if Kit's ID was send or not.
		if(!isset($_GET['id'])){
				header("location: index.php");
		}
		else{
				$eid = $_GET['id'];
		}

		require_once("../../includes/session.php");
		require_once("../../includes/db.php");
		require_once("../../includes/global.php");
?>

	<div id="content" style="width:100% !important">
		<div>
			<?php
				$query = "SELECT a.*, b.condName, c.equipCatName, d.equipSubName, e.locationName FROM equipments a LEFT JOIN conditions b ON a.condID=b.condID LEFT JOIN equipCategory c ON a.equipCatID=c.equipCatID LEFT JOIN equipSubCategory d ON a.equipSubCatID=d.equipSubCatID LEFT JOIN locations e ON a.locationID=e.locationID WHERE a.equipmentid='".$eid."'";
				$result = mysql_query($query);
				$res = mysql_fetch_array($result);		
			?>
			</div>
			<form class="createForm">
				<fieldset>
						<legend>
								<div>
										Equipment Information
										<div class="cf-links" >
												<ul>
														<li><a href="edit.php?id=<?php echo $res['equipmentid']; ?>" id="edit" title="Edit"><img src='../etc/edit.png' /></a></li>
														<li><img src='../etc/cross.png' onclick="deactivate(<?php echo "'".$res['equipmentid']."'"; ?>)" title="Deactivate" style="cursor:pointer"/></li>
												</ul>
										</div>
										<div class="clear"><!-- --></div>
								</div>	
						</legend>
						<div class="pf-element">
								<div class='pf-description-float'>
											 Equipment ID:
								</div>
								<div class='pf-content-float'>
										<?php echo $eid; ?>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-sidebox">Status: 
								<?php 
										switch ($res['status']) {
												case 1:
														echo '<strong><font color="green">Available</font></strong>';
														break;
												case 2:
														echo '<strong><font color="red">On loan </font></strong><br />';
														break;
										}
								?>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Kit ID:
								</div>
								<div class='pf-content-float'>
										<?php echo $res['kitid']; ?>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Loan Length:
										<div class='pf-note'>Default length in days.</div>
								</div>
								<div class='pf-content-float'>
										<?php echo $res['loan_lengthEQ']; ?>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Manufacturer:
								</div>
								<div class='pf-content-float'>
										<?php echo $res['manufacturer']; ?>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Manufacturer Serial #:
								</div>
								<div class='pf-content-float'>
										<?php echo $res['manufSerialNum']; ?>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Manufacture Date:
								</div>
								<div class='pf-content-float'>
										<?php echo friendlyDateNoTime($res['manufactureDate']); ?>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Expected Lifetime:
								</div>
								<div class='pf-content-float'>
										<?php echo $res['expectedLifetime']; ?> years
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Expiration Date
								</div>
								<div class='pf-content-float'>
										<?php echo friendlyDateNoTime($res['expirationDate']); ?>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
											 Model:
								</div>
								<div class='pf-content-float'>
										<?php echo $res['model']; ?>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Category:
								</div>
								<div class='pf-content-float'>
										<?php echo $res['equipCatName']; ?>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Sub-Category:
								</div>
								<div class='pf-content-float'>
										<?php echo $res['equipSubName']; ?>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Condition:
								</div>
								<div class='pf-content-float'>
										<?php echo $res['condName']; ?>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Location:
								</div>
								<div class='pf-content-float'>
										<?php echo $res['locationName']; ?>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Access Areas:
								</div>
								<div class="pf-content-float">
										<?php
												$accessQuery = "SELECT a.accessarea FROM accessareas a INNER JOIN equipments_accessareas b ON a.id = b.accessid WHERE b.equipmentid=".$eid;
												$accessResult = mysql_query($accessQuery);
												echo "<ul>";
												while ($aRes = mysql_fetch_assoc($accessResult)){
														echo "<li>". $aRes['accessarea'] ."</li>";
												}
												echo "</ul>";
										?>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Equipment Added:
								</div>
								<div class='pf-content-float'>
										<?php echo friendlyDate($res['created_on']); ?>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Equipment Modified:
								</div>
										<div class='pf-content-float'>
										<?php echo friendlyDate($res['updated_on']); ?>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Description:
								</div>
								<div class='pf-content-float'>
										<?php echo $res['equipment_desc']; ?>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Notes:
								</div>
								<div class='pf-content-float'>
										<?php echo $res['notes']; ?>
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
                    <?php echo friendlyDateNoTime($res['purchaseDate']); ?>
                </div>
                <div class='clear'></div>
            </div>
            <div class="pf-element">
                <div class='pf-description-float'>
                    Purchase Price:
                </div>
                <div class='pf-content-float'>
                    <?php echo $res['purchasePrice']; ?>
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
								</div>
								<div class='pf-content-float'>
										<?php echo $res['ipAddress']; ?>
                </div>
								<div class='clear'></div>
            </div>
            <div class="pf-element">
                <div class='pf-description-float'>
                    MAC Address:
								</div>
                <div class='pf-content-float'>
                    <?php echo $res['macAddress']; ?>
                </div>
								<div class='clear'></div>
            </div>
            <div class="pf-element">
                <div class='pf-description-float'>
                    Host Name:
								</div>
                <div class='pf-content-float'>
										<?php echo $res['hostName']; ?>
                </div>
								<div class='clear'></div>
            </div>
            <div class="pf-element">
                <div class='pf-description-float'>
                    Connect Type:
								</div>
                <div class='pf-content-float'>
                    <?php echo $res['connectType']; ?>
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
                    <?php echo $res['warrantyInfo']; ?>
                </div>
								<div class='clear'></div>
            </div>
        </fieldset>
		</form>
</div>
<div class='clear'></div>