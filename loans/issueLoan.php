<?php
		require_once("../includes/session.php");
    $page_title = "Issue Loan";
    require_once("../includes/headerOpen.php");
?>

		<link rel="stylesheet" type="text/css" href="../css/loans/loans.css">
		<script type="text/javascript" src="../js/loans/issueLoan.js"></script>

<?php
	require_once("../includes/headerClose.php"); 
?>

<div id="sidebar">
	<?php require_once("sidebar.php"); ?>
</div>
<div id="content">
		<form id="loanerform" class="createForm">
				<fieldset>
						<legend>New Loan</legend>
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
						<div class="pf-element">
								<div class='pf-description-float'>
										Item Type<span class="required">*</span>:
								</div>
								<div class='pf-content-float'>
										<select id="item-type">
												<option value='Kit' selected='selected'>Kit</option>
												<option value='Equipment'>Equipment</option>
										</select>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Loan Type<span class="required">*</span>:
								</div>
								<div class='pf-content-float'>
										<select id="loan-type" >
												<option value='2' selected="selected">Short Term</option>
												<option value='7'>Long Term</option>
										</select>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element" id='loan-length-container' style='padding:1px 2px 0px 0px !important; margin-bottom:15px; overflow:hidden'>
								<div class='pf-description-float'>
										Loan Period:
										<p class="pf-note">Default length can be changed (days).</p>
								</div>
								<div class='pf-content-float'>
										<input id="loan_length" class="pf-text-input" type="text" name="loanLength" />
										<input id='default-loan-length' type='hidden' value=' '/>
								</div>
								<div class='clear'></div>
						</div>
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
						<div id="equipment-checkout-wrapper"></div>
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