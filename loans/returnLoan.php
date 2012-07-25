<?php
		require_once("../includes/session.php");
    $page_title = "Return Loan";
    require_once("../includes/headerOpen.php");
?>

		<!--<link rel="stylesheet" type="text/css" href="../css/loans/loans.css">-->
		<script type="text/javascript" src="../js/loans/returnLoan.js"></script>

<?php
	require_once("../includes/headerClose.php");
?>



<div id="sidebar">
	<?php require_once("sidebar.php"); ?>
</div>
<div id="content">
		<form id="loanerform" class="createForm">
				<fieldset>
						<legend>Return Loan</legend>
						<div class="pf-note"><span class="required">*</span> Required fields<br /><br /></div>
						<div class="pf-element">
								<div class='pf-description-float'>
										User ID<span class="required">*</span>:
								</div>
								<div class='pf-content-float'>
										<input class="pf-text-input" type="text" name="userid" id="userid" />
										<img id="uidResultImg" src="../etc/checkMark.png" width="15px" height="15px" style="display:none"/>
										<img id="uidWaiting" class="waiting" src="../etc/loading.gif" width="15" height="15" style="display: none"/>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Item ID<span class="required">*</span>:
										<p class="pf-note">(Barcode)</p>
								</div>
								<div class='pf-content-float'>
										<input class="pf-text-input" type="text" name="itemid" id="itemid" />
										<img id="idResultImg" src="../etc/checkMark.png" width="15" height="15" style="display:none"/>
										<img id="idWaiting" class="waiting" src="../etc/loading.gif" width="15" height="15" style="display: none"/>
								</div>
								<div class='clear'></div>
						</div>
						<div id='loan-details-container'></div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Notes:
								</div>
								<div class='pf-content-float'>
										<textarea class="pf-text-input" name="notes" id="notes" cols="35" rows="5"></textarea>
								</div>
								<div class='clear'></div>
						</div>
				</fieldset>
				<fieldset style='margin-top:15px'>
						<legend>Listed Equipment</legend>
						<div id="equipment-checkout-wrapper">
						</div>
				</fieldset>
				<div class="pf-element" style="width:140px; margin:auto; float:none !important; padding-top:10px">
						<input type="button" name="submit" id="submit" value="Submit"/>&nbsp;&nbsp;<input type="button" id="reset" value="Reset"/>&nbsp&nbsp<img id="submitWaiting" class="waiting" src="../etc/loading.gif" width="15" height="15" style="display: none"/>
				</div>
		</form>
</div>
<div class="clear"></div>

<?php
		require_once("../includes/footerNew.php");
?>