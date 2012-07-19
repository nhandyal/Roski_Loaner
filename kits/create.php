<?php
		require_once("../includes/session.php");
    $page_title = "Kit : Create";
    require_once("../includes/headerOpen.php"); //opens the head tag		
?>

<script type="text/javascript" src="../js/kits/create.js"></script>

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
		<form class="createForm">
			<fieldset>
					<legend>Create New Kit</legend>
							<div class="pf-element">
										<div class='pf-description-float'>
												Kit ID:
												<p class="pf-note">Maximum 15 characters. Letters and Numbers.</p>
										</div>
										<div class='pf-content-float'>
												<input class="pf-field" id="kitid" name="kitid" type="text" />
										</div>
										<div class='clear'></div>
								</div>
								<div class="pf-element">
									<div class='pf-description-float'>
											Loan Length:
												<p class="pf-note">If left blank, default value is 3.</p>
									</div>
									<div class='pf-content-float'>
												<input class="pf-field" id="loan_length" name="loan_length" type="text" />
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
				
				<input type="checkbox" name="access_area" class="access_area" value='<?php echo $area['id']; ?>'/><?php echo $area['accessarea']; ?><br/>
				
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
												<textarea class="pf-field" name="desc" id="desc" cols="35" rows="5" ></textarea>
										</div>
										<div class='clear'></div>
								</div>
								<div class="pf-element">
									<div class='pf-description-float'>
											Notes:
									</div>
									<div class='pf-content-float'>
												<textarea class="pf-field" name="notes" id="notes" cols="35" rows="5" ></textarea>
										</div>
										<div class='clear'></div>
								</div>
								<div class="pf-element" style="width:140px; margin:auto; float:none !important; padding-top:10px"> 
										<input class="pf-buttons" type="button" name="submit" id="submit" value="Submit" onclick="submitForm()"/>&nbsp&nbsp<img id="submitWaiting" class="waiting" src="../etc/loading.gif" width="15" height="15" style="display: none"/>
								</div>
				</fieldset>
	</form>                    
</div>
<div class="clear"></div>

<?php
		require_once("../includes/footerNew.php");
?>
