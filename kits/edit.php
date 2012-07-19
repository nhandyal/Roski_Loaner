<?php
		require_once("../includes/session.php");
		$id = trim($_GET['id']);
		$page_title = "Kits : Edit : " . $id . " ";
    require_once("../includes/headerOpen.php"); //opens the head tag
	
		$query = "select * from kits where kitid = '$id'";
		$result = mysql_query($query) or die (mysql_error());
		$res = mysql_fetch_assoc($result);
?>

<script type="text/javascript" src="../js/kits/edit.js"></script>

<?php
	require_once("../includes/headerClose.php"); //close the head tag and add all universal header elements
?>

<div id="sidebar">
		<?php require_once("sidebar.php"); ?>
</div>

<div id="content">
		<form class="createForm">
				<fieldset>
						<legend>
								Edit - Kit : <?php echo $id; ?>
								<div class="cf-links" >
										<ul>
												<li><img src='../etc/cross.png' onclick="deactivate(<?php echo "'".$res['kitid']."'"; ?>)" title="Deactivate" style="cursor:pointer"/></li>
										</ul>
								</div>
						</legend>
            <div class="pf-element">
                <div class='pf-description-float'>
                    Loan Period:
								</div>
								<div class='pf-content-float'>
                    <input type="text" name="loan_length" id="loan_length" value="<?php echo stripslashes($res['loan_length']); ?>"/>
                </div>
                <div class='clear'></div>
            </div>
            <div class="pf-element">
                <div class='pf-description-float'>
                    Access Areas:
								</div>
								<div class='pf-content-float'>
										<?php
												$query = "select accessid from kits_accessareas where kitid = '$id'";
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
            <div class="pf-element">
                <div class='pf-description-float'>
                    Description:
								</div>
								<div class='pf-content-float'>
                    <textarea name="desc" id="desc" cols="35" rows="5" ><?php echo stripslashes($res['kit_desc']); ?></textarea>
                </div>
                <div class='clear'></div>
            </div>
						<div class="pf-element">
                <div class='pf-description-float'>
                    Notes:
								</div>
								<div class='pf-content-float'>
                    <textarea name="notes" id="notes" cols="35" rows="5" ><?php echo stripslashes($res['notes']); ?></textarea>
                </div>
                <div class='clear'></div>
            </div>
            <br/>
						<div class="pf-element" style="width:140px; margin:auto; float:none !important; padding-top:10px">
                <input type="hidden" id="kitid" value="<?php echo $id; ?>"/>
                <input type="button" name="submit" id="submit" value="Submit" onclick="submitForm()"/>&nbsp&nbsp<img id="submitWaiting" class="waiting" src="../etc/loading.gif" width="15" height="15" style="display: none"/>
            </div>
        </fieldset>
    </form>
</div>
<div class="clear"></div>

<?php
		require_once("../includes/footerNew.php");
?>