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
						<div class="pf-element" style="padding: 0px">
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
				</fieldset>
				<fieldset class="loan-item-template">
						<legend class='item-legend'>
								<span class="item-number">Item: 1</span>
						</legend>
						<div class="pf-element">
								<div class='pf-description-float'>
										Item ID<span class="required">*</span>:
										<p class="pf-note">(Barcode)</p>
								</div>
								<div class='pf-content-float'>
										<input class="pf-text-input itemid" type="text" name="itemid" onchange="changeItemID(this)"/>
										<img class="idResultImg" src="../etc/checkMark.png" width="15" height="15" style="display:none"/>
										<img class="idWaiting" class="waiting" src="../etc/loading.gif" width="15" height="15" style="display: none"/>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Item Type<span class="required">*</span>:
								</div>
								<div class='pf-content-float'>
										<select class="item-type" onchange="changeItemType(this)">
												<option value='Kit' <?php if($_SESSION['dept']==2)echo "selected='selected'"; ?>>Kit</option>
												<option value='Equipment' <?php if($_SESSION['dept']!=2)echo "selected='selected'"; ?>>Equipment</option>
										</select>
								</div>
								<div class='clear'></div>
						</div>
						<div class="pf-element">
								<div class='pf-description-float'>
										Loan Type<span class="required">*</span>:
								</div>
								<div class='pf-content-float'>
										<select class="loan-type" >
												<option value='2' selected="selected">Short Term</option>
												<option value='7'>Long Term</option>
										</select>
								</div>
								<div class='clear'></div>
						</div>
						<div class="hidden-elements">
								<div class="pf-element">
										<div class='pf-description-float'>
												Loan Length<span class="required">*</span>:
												<p class="pf-note">In days</p>
										</div>
										<div class='pf-content-float'>
												<input class="pf-text-input loan-length" type="text" name="loanLength" onchange="changeLoanLength(this)"/>
										</div>
										<div class='clear'></div>
								</div>
								<div class="pf-element">
										<div class='pf-description-float'>
												Notes
										</div>
										<div class='pf-content-float'>
												<textarea class="pf-text-input notes" name="notes" cols="35" rows="5" ></textarea>
										</div>
										<div class='clear'></div>
								</div>
								<input type='hidden' class="valid-loan-length" value="0"/>
						</div>
				</fieldset>
		</form>
		<div id='form-controls'>
				<div id="add-item" class="form-button">Add Item</div>
				<div id="submit-loan" class="form-button">Submit</div>
				<div class="clear"></div>
		</div>
</div>
<div id="page-data">
		<input type='hidden' id="loan-item-count" value="0"/>
		<input type="hidden" id="deptID" value="<?php echo $_SESSION['dept']; ?>"/>
</div>
<div class="clear"></div>

<?php
		require_once("../includes/footerNew.php");
?>