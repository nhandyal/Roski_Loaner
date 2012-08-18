<?php
		//check if Loan ID was send or not.
		$view;
		if(!isset($_GET['lid'])){
				header("location: index.php");
		}
		else{
				$lid = $_GET['lid'];
				$view = $_GET['view'];
		}
		
		require_once("../../includes/session.php");
		require_once("../../includes/db.php");
		require_once("../../includes/global.php");
?>


<div id="content-details">
		<?php
				$iskit = true;
				$id = "";
				$desc = "";
				$query = "SELECT * FROM loans WHERE lid=".$lid;
				$result = mysql_query($query);
				$res = mysql_fetch_assoc($result);
				if($res['equipmentid'] != ""){
						$id = $res['equipmentid'];
						$iskit = false;
						$query = "SELECT equipment_desc FROM equipments WHERE equipmentid='".$id."'";
						$r = mysql_fetch_assoc(mysql_query($query));
						$desc = $r['equipment_desc'];
				}
				else if($res['kitid'] != ""){
						$id = $res['kitid'];
						$query = "SELECT kit_desc FROM kits WHERE kitid='".$id."'";
						$r = mysql_fetch_assoc(mysql_query($query));
						$desc = $r['kit_desc'];
				}
				
		?>
		
		<form class="createForm">
			<fieldset>
					<legend>
								<div>
										<div id="details-header" style="float:left">
												<?php
														echo "Reservation Details : ";
														echo $lid;
												?>
										</div>
										<?php
												if($view == "active"){
										?>
														<div class="cf-links">
																<ul>
																		<li><a href="javascript:validateCheckout('<?php echo $res['lid']; ?>')" class="issue" title="Issue"><img src="../etc/issue.png" /></a></li>
																		<li><a href="javascript:cancel('<?php echo $res['lid']; ?>')" class="cancel" title='Cancel'><img src='../etc/cross.png' /></a></li>
																</ul>
														</div>
										<?php
												}
										?>
										<div class="clear"></div>
								</div>
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
										<?php echo $id; ?>
								</div>
						</div>
						<div class="pf-element">
								<div class='pf-description'>
										Loan Period:
										<p class="pf-note">Default loan period (days)</p>
								</div>
								<div class='pf-content'>
										<?php echo $res['loan_length'];?>
								</div>
						</div>
						<div class="pf-element">
								<div class='pf-description'>
										Issue Date:
								</div>
								<div class='pf-content'>
										<?php echo friendlyDate($res['issue_date']); ?>
								</div>
						</div>
						<div class="pf-element">
								<div class='pf-description'>
										Due Date:
								</div>
								<div class='pf-content'>
										<?php echo friendlyDate($res['due_date']); ?>
								</div>
						</div>
						<div class="pf-element">
								<div class='pf-description'>
										Description:
								</div>
								<div class='pf-content'>
										<?php echo $desc; ?>
								</div>
						</div>
						<div class="pf-element">
								<div class='pf-description'>
										Notes:
								</div>
								<div class='pf-content'>
										<?php echo str_replace("\n","<br/>",$res['notes']); ?>
								</div>
						</div>
			</fieldset>
			<br/>
			<fieldset>
						<legend>Equipment</legend>
						<div class="pf-element">
								<div class="pf-description-float" style='width:15%'>Listed Equipment</div>
								<div class="pf-content-float" style='width:84%'>
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
						</div>
				</fieldset>
		</form>
</div>
<div class="clear"></div>