<?php
		//check if Kit's ID was send or not.
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

	<div id="content" style="width:100% !important">
			<?php
				$query = "select * from kits WHERE kitid='$id' LIMIT 1";
				$result = mysql_query($query);		

				if(mysql_num_rows($result) != 1){
					header("location: index.php?err=201");
				}
				else{
					$res = mysql_fetch_assoc($result);
				}
			?>
            <form class="createForm">
            	<fieldset>
                	<legend>
                    	<div>
														Kit Details : <?php echo $id; ?>
                            <div class="cf-links">
                            	<ul>
                                        <li><a href="edit.php?id=<?php echo $res['kitid']; ?>" id="edit" title="Edit"><img src='../etc/edit.png' /></a></li>
                                        <li><img src='../etc/cross.png' onclick="deactivate(<?php echo "'".$id."'"; ?>)" title="Deactivate" style="cursor:pointer"/></li>
                                </ul>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </legend>
                    <div class="pf-element">
                        <div class='pf-description-float'>
                            Kit ID:
												</div>
												<div class='pf-content-float'>
                            <?php echo $id; ?>
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
                                Loan Period:
                                <p class="pf-note">Default Loan Period.</p>
														</div>
														<div class='pf-content-float'>
                                <?php echo $res['loan_length']; ?>
                            </div>
														<div class='clear'></div>
                        </div>
                        <div class="pf-element">
                            <div class='pf-description-float'>
                                Kit Added:
														</div>
														<div class='pf-content-float'>
                                <?php echo friendlyDate($res['created_on']); ?>
                            </div>
                        <div class='clear'></div>
                        </div>
                        <div class="pf-element">
                            <div class='pf-description-float'>
                                Kit Updated:
														</div>
														<div class='pf-content-float'>
                                <?php echo friendlyDate($res['updated_on']); ?>
                            </div>
                        <div class='clear'></div>
                        </div>
                        <div class="pf-element">
                            <div class='pf-description-float'>
                                Access Areas:
														</div>
                            <div class='pf-content-float'>    
																<?php
																		$query = "select a.accessarea as accessarea from kits_accessareas k JOIN accessareas a ON (k.accessid = a.id) where k.kitid = '$id'";
																		$result = mysql_query($query) or die (mysql_error());
										
																		echo "<ol>";
																		while ($r = mysql_fetch_assoc($result)){
																				echo "<li>". $r['accessarea'] ."</li>";
																		}
																		echo "</ol>";
																?>
                            </div>
														<div class='clear'></div>
                        </div>
                        <div class="pf-element">
                            <div class='pf-description-float'>
                                Description:
														</div>
														<div class='pf-content-float'>
                                <?php echo $res['kit_desc']; ?>
                            </div>
                        <div class='clear'></div>
                        </div>
                        <div class="pf-element">
                            <div class='pf-description-float'>
                                Notes:
														</div>
														<div class='pf-content-float'>
                                <?php echo $res['notes']; ?></span>
                            </div>
                        <div class='clear'></div>
                        </div>
              </fieldset>
              <br />
              <fieldset>
                   	<legend>Kit Equipment</legend>
                        <div class="pf-element">
                            <div class='pf-description-float'>
                            	Listed Equipment
														</div>
                            <div class='pf-content-float'>
																<?php 
																		$query = "SELECT * FROM equipments WHERE equipments.kitid = '$id' AND equipments.deptID = '$_SESSION[dept]' AND status<>-1";
																		$result = mysql_query($query) or die (mysql_query());
																		
																		while($res = mysql_fetch_assoc($result)){
																?>
																				<ul>
																						<li>Equipment ID: <?php echo $res['equipmentid']; ?></li>
																						<li>Description: <?php echo $res['equipment_desc']; ?></li>
																						<li>Model: <?php echo $res['model']; ?></li>
																						<li>Notes: <?php echo $res['notes']; ?></li>
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