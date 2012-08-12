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
				<br/>
				<fieldset class="loan-item-template loan-item" id="loan-item-1">
						<legend class='item-legend'>
								<span class="item-number">Item: 1</span>
						</legend>
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
										<select id="loan-type" >
												<option value='2' selected="selected">Short Term</option>
												<option value='7'>Long Term</option>
										</select>
								</div>
								<div class='clear'></div>
						</div>
				</fieldset>
				<input type='hidden' id="loan-item-count" value="1"/>
				<input type="hidden" id="deptID" value="<?php echo $_SESSION['dept']; ?>"/>
		</form>
		<div id='form-controls'>
				<div id="add-item" class="form-button">Add Item</div>
				<div id="submit-item" class="form-button">Submit</div>
				<div class="clear"></div>
		</div>
</div>
<div class="clear"></div>

<?php
		require_once("../includes/footerNew.php");
?>