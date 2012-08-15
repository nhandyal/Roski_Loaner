<?php
		require_once("../../includes/session.php");
		require_once("../../includes/db.php");
		require_once("../../includes/global.php");
		
		$lid = $_GET['lid'];
		$queryString = "SELECT equipmentid, kitid, userid, notes FROM loans WHERE lid=".$lid;
		$r = mysql_fetch_assoc(mysql_query($queryString));
		$userid = $r['userid'];
		$notes = $r['notes'];
		$id;
		$iskit;
		$type;
				
		if($r['equipmentid'] != ""){
				$id = $r['equipmentid'];
				$iskit = FALSE;
				$type = "Equipment";
				$queryString = "SELECT * FROM equipments WHERE equipmentid='".$id."'";
		}
		else if($r['kitid'] != ""){
				$id = $r['kitid'];
				$iskit = TRUE;
				$type = "Kit";
				$queryString = "SELECT * From equipments WHERE kitid='".$id."' AND status<>-1";
		}
		
?>

<form class="createForm">
		<fieldset>
				<legend>Return Loan : <?php echo $lid; ?>
						<div class="cf-links">
								<ul>
										<li><a href="javascript:details('<?php echo $lid; ?>')" title="Details" ><img src='../etc/details.png' /></a></li>
										<li><a href="javascript:renew('<?php echo $lid; ?>')" title="Renew" ><img src='../etc/refresh.png' /></a></li>
								</ul>
						</div>
				</legend>
				<div class="pf-element">
						<div class='pf-description'>User ID:</div>
						<div class='pf-content'>
								<input id="userid" class="readonly" type="text" name="userid" value="<?php echo $userid; ?>" readonly="readonly" />
						</div>
				</div>
				<div class="pf-element">
						<div class='pf-description-float'>
								<p><?php echo $type; ?> ID:</p>
								<p class="pf-note">(Barcode)</p>
						</div>
						<div class='pf-content-float'>
								<input id="itemid" class="readonly" type="text" name="id" value="<?php echo $id; ?>" readonly="readonly"/>
								<input id='item-type' type='hidden' value='<?php echo $type; ?>'/>
						</div>
						<div class='clear'></div>
				</div>
				<div class="pf-element">
						<div class='pf-description-float'>Notes:</div>
						<div class='pf-content-float'>
								<textarea name="notes" id="notes" cols="35" rows="5"><?php echo $notes; ?></textarea>
						</div>
						<div class='clear'></div>
				</div>
		</fieldset>
		<br/>
		<fieldset>
				<legend>Listed Equipment</legend>
				<div id="equipment-checkout-wrapper">
						<?php
								$r = mysql_query($queryString);
								$i = 0;
								$inputHTML = '<div id="input-wrapper">'; // open 1
								$equipmentHTML = '<div id="equipment-wrapper">'; // open 1
								while ($result = mysql_fetch_assoc($r)){
										$equipmentID = $result['equipmentid'];
										$model = $result['model'];
										$notes = $result['notes'];
										
										if($i%4 == 0){
												$inputHTML = $inputHTML."<div class='holder'>"; // open 2
												$equipmentHTML = $equipmentHTML."<div class='holder'>"; //open 2
										}
										
										switch($type){
												case "Kit":
														$inputHTML = $inputHTML.'<input id="input_'.$equipmentID.'" class="equipment-input" type="text" onchange="validateEqID(this)"/>';
														$equipmentHTML = $equipmentHTML.'<div id="'.$equipmentID.'" class="equipment-wrapper';
														if($result['condID'] == 5){
																//broken item
																$equipmentHTML .=' not-scanned broken-notify">';
														}
														else if($result['condID'] == 6){
																//missing item
																$equipmentHTML .=' missing-notify">';
														}
														else{
																//  valid item
																$equipmentHTML .=' not-scanned">';
														}
														break;
												case "Equipment":
														$inputHTML = $inputHTML.'<input id="input_'.$equipmentID.'" class="equipment-input readonly" readonly="readonly" type="text" value="'.$equipmentID.'"/>';
														$equipmentHTML = $equipmentHTML.'<div id="'.$equipmentID.'" class="equipment-wrapper scanned" >';
														break;
										}
										
										$equipmentHTML .= '<div class="equipment">';
										$equipmentHTML = $equipmentHTML.'<div><p class="details-title" style="width:40%">Equipment ID:</p><p class="details-content" style="width:60%">'.$equipmentID.'</p><div class="clear"></div></div>';
										$equipmentHTML = $equipmentHTML.'<div><p class="details-title">Model:</p><p class="details-content">'.$model.'</p><div class="clear"></div></div>';
										
										if($result['condID'] == 5){
												$equipmentHTML = $equipmentHTML.'<div class="status"><p class="details-title">Status:</p><p class="details-content" style="color:white">Damaged</p><div class="clear"></div></div>';
												//$equipmentHTML = $equipmentHTML.'<div class="notes"><p class="details-title">Notes:</p><p class="details-content">'.$notes.'</p><div class="clear"></div></div>';
												$equipmentHTML = $equipmentHTML.'<input type="hidden" class="original-condition" value="Damaged"/></div>';
												// broken item can only be marked as missing
												$equipmentHTML = $equipmentHTML.'<div class="equipment-functions">';
												$equipmentHTML = $equipmentHTML.'<img class="missing-item" src="../etc/grey-cross.png" width="9" height="9" title="Missing Item"/>';
												$equipmentHTML = $equipmentHTML.'</div></div>';
										}
										else if($result['condID'] == 6){
												// item is missing, it cannot be marked as broken or missing
												$equipmentHTML = $equipmentHTML.'<div class="status"><p class="details-title">Status:</p><p class="details-content" style="color:white">Missing</p><div class="clear"></div></div>';
												//$equipmentHTML = $equipmentHTML.'<div class="notes"><p class="details-title">Notes:</p><p class="details-content">'.$notes.'</p><div class="clear"></div></div>';
												$equipmentHTML = $equipmentHTML.'<input type="hidden" class="original-condition" value="Missing"/></div>';
												$equipmentHTML = $equipmentHTML.'<div class="equipment-functions"></div></div>';
										}
										else{
												$equipmentHTML = $equipmentHTML.'<div class="status"><p class="details-title">Status:</p><p class="details-content" style="color:white">Good</p><div class="clear"></div></div>';
												//$equipmentHTML = $equipmentHTML.'<div class="notes"><p class="details-title">Notes:</p><p class="details-content">'.$notes.'</p><div class="clear"></div></div>';
												$equipmentHTML = $equipmentHTML.'<input type="hidden" class="original-condition" value="Good"/></div>';
												// item is a valid loan item
												$equipmentHTML = $equipmentHTML.'<div class="equipment-functions">';
												$equipmentHTML = $equipmentHTML.'<img class="missing-item" src="../etc/grey-cross.png" width="9" height="9" title="Missing Item"/>';
												$equipmentHTML = $equipmentHTML.'<img class="broken-item" src="../etc/wrench_icon.png" width="12" height="12" title="Broken Item"/>';
												$equipmentHTML = $equipmentHTML.'</div></div>';
										}
										
										$i++;
										
										if($i%4 == 0){
												$inputHTML = $inputHTML."<div class='clear'></div></div>";
												$equipmentHTML = $equipmentHTML."<div class='clear'></div></div>";
										}
										
								} // end while
								
								if($i%4 != 0){
										$inputHTML = $inputHTML."<div class='clear'></div></div>";
										$equipmentHTML = $equipmentHTML."<div class='clear'></div></div>";
								}
								
								$inputHTML = $inputHTML."<div class='clear'></div></div>";
								$equipmentHTML = $equipmentHTML."<div class='clear'></div></div>";
								
								echo $inputHTML;
								echo $equipmentHTML;
						?>
				</div>
				<div class="pf-element" style="width:140px; margin:auto; float:none !important; padding-top:10px">
						<div id="form-controls">
								<input type="button" id="submit" value="Submit" onclick="validateReturnSubmit()"/>&nbsp&nbsp<img id="submitWaiting" class="waiting" src="../etc/loading.gif" width="15" height="15" style="display: none"/>
								<div class="clear"></div>
						</div>
				</div>
		</fieldset>
</form>

