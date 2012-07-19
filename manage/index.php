<?php
		require_once("../includes/session.php");
		$page_title = "System Options";
		require_once("../includes/headerOpen.php");
?>

		<link rel="stylesheet" type="text/css" href="../css/manage/index.css">
		<link rel="stylesheet" type="text/css" href="../fancybox/jquery.fancybox-1.3.4.css" media="screen" />
		<script type="text/javascript" src="../fancybox/jquery.fancybox-1.3.4.pack.js"></script>
		<script type="text/javascript" src="../js/manage/index.js"></script>
		
<?php
		require_once("../includes/headerClose.php");
?>
		<div id="content" style="width:100% !important">
				<div id="content-container">
						<div class="section-holder" id="sub-container-1">
								<div class="section-header" id="sub-container-1-header">Manageable Sections</div>
								<div class="section-content" id="sub-container-1-content">
										<ul>
												<li id="Access-Area" onclick="loadContainer2('accessareas','Access-Area','accessarea',this)">Access Areas</li>
												<li id="Category" onclick="loadContainer2('equipCategory','Category','equipCatName',this)">Categories</li>
												<li id="Classes" onclick="loadContainer2('classes','Classes','classname',this)">Classes</li>
												<li id="Locations" onclick="loadContainer2('locations','Locations','locationName',this)">Locations</li>
										</ul>
								</div>
						</div>
						<div class="section-holder" id="sub-container-2">
								<div class="section-header" id="sub-container-2-header"></div>
								<div class="section-content" id="sub-container-2-content"></div>
						</div>
						<div class="section-holder" id="sub-container-3">
								<div class="section-header" id="sub-container-3-header"></div>
								<div class="section-content" id="sub-container-3-content"></div>
						</div>
						<div class="clear"></div>
				</div>
		</div>
		<div class="clear"></div>
<?php
		require_once("../includes/footerNew.php");
?>