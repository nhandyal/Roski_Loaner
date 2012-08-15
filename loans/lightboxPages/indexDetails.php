<?php
		require_once("../../includes/session.php");
		require_once("../../includes/db.php");
		require_once("../../includes/global.php");
?>

<div id="content-details">
<?php
		$view = $_GET['view'];
		$id;
		$iskit;
		$queryString = "Select a.* FROM loans a WHERE a.lid=".$_GET['lid'];
		$r = mysql_fetch_assoc(mysql_query($queryString));
		if($r['equipmentid'] != ""){
				$id = $r['equipmentid'];
				$iskit = FALSE;
				$queryString = "SELECT * from equipments WHERE equipmentid='".$r['equipmentid']."'";
		}
		else if($r['kitid'] != ""){
				$id = $r['kitid'];
				$iskit = TRUE;
				$queryString = "SELECT * from kits WHERE kitid='".$r['kitid']."'";
		}
		$res = mysql_fetch_assoc(mysql_query($queryString));
?>
		<form class="createForm">
				<fieldset>
						<legend>
								<?php
										echo "Loan Details: ";
										echo $_GET['lid'];
								?>
								<div class="cf-links">
										<ul>
												<?php
														if ($view != "archive"){
												?>
																		<li><a href="javascript:renew(<?php echo $_GET['lid'].",'".$view."'"; ?>)" title="Renew" ><img src='../etc/refresh.png' /></a></li>
																		<li><a href="javascript:returnLoan(<?php echo $_GET['lid']; ?>)" title="Return Loan" ><img src='../etc/cross.png' /></a></li>
												<?php
														}
												?>												
										</ul>
								</div>
								<div class="clear"></div>
						</legend>
						<div class="pf-element">
								<div class='pf-description'>
										
												<?php
														if($iskit)
																echo "Kit ID: ";
														else
																echo "Equipment ID: ";
												?>
										
								</div>
								<div class='pf-content'>
										
												<?php
														if($iskit){
																echo $res['kitid'];
														}
														else{
																echo $res['equipmentid'];
														}
												?>
										
								</div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										<p>Loan Period:</p>
										<p class="pf-note">Default loan period (days)</p>
								</div>
								<div class='pf-content-float'>
										<?php
												if ($iskit){
														echo $res['loan_length'];
												}
												else{
														echo $res['loan_lengthEQ'];
												}
										?>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description'>
										Issued:  
								</div>
								<div class='pf-content'>
										<?php echo friendlyDate($r['issue_date']); ?>	
								</div>
						</div>
						<div class="pf-element">
								<div class='pf-description'>
										Due:  
								</div>
								<div class='pf-content'>
										<?php echo friendlyDate($r['due_date']); ?>	
								</div>
						</div>
						<div class="pf-element">
								<div class='pf-description'>
										Returned:  
								</div>
								<div class='pf-content'>
										<?php echo friendlyDate($r['return_date']); ?>	
								</div>
						</div>
						<div class="pf-element">
								<div class='pf-description'>
										Access Areas:  
								</div>
								<div class='pf-content'>
										<?php
												if ($iskit)
														$accessQuery = "select a.accessarea as accessarea from kits_accessareas k JOIN accessareas a ON (k.accessid = a.id) where k.kitid = '$id'";
												else
														$accessQuery = "SELECT a.accessarea FROM accessareas a INNER JOIN equipments_accessareas b ON a.id = b.accessid WHERE b.equipmentid=".$id;
														
												$accessResult = mysql_query($accessQuery) or die (mysql_error());
												
												echo "<ul>";
												
												while ($aRes = mysql_fetch_assoc($accessResult)){
														echo "<li>". $aRes['accessarea'] ."</li>";
												}
												
												echo "</ul>";
										?>
								</div>
						</div>
						<div class="pf-element">
								<div class='pf-description'>
										Description:  
								</div>
								<div class='pf-content'>
										
												<?php
														if($iskit){
																echo $res['kit_desc'];
														}
														else{
																echo $res['equipment_desc'];
														}
												?>
										
								</div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>		
										Notes:  
								</div>
								<div class='pf-content-float'>
										<?php echo str_replace("\n","<br/>",$r['notes']); ?>
								</div>
								<div class='clear'></div>
						</div>
				</fieldset>
				<br />
				<fieldset>
						<legend>Equipment</legend>
						<div class="pf-element">
								<div class='pf-description-float'>Listed Equipment</div>
								<div class='pf-content-float'>
										<?php 
												if($iskit){
														$query = "SELECT * FROM equipments WHERE equipments.kitid='".$id."' AND status<>-1";
												}
												else{
														$query = "SELECT * FROM equipments WHERE equipmentid='".$id."'";
												}
												
												$result = mysql_query($query) or die (mysql_query());
												
												while($res = mysql_fetch_assoc($result)){
										?>
														<ul>
																<li>Equipment ID:<?php echo $res['equipmentid']; ?></li>
																<li>Description:<?php echo $res['equipment_desc']; ?></li>
																<li>Model:<?php echo $res['model']; ?></li>
																<li>Notes:<?php echo $res['notes']; ?></li>
														</ul><br/>
										<?php
												}
										?>
								</div>
								<div class='clear'></div>
						</div>
				</fieldset>
		</form>
</div>
<div class="clear"><!-- --></div>