<?php
		require_once("../includes/session.php");   
		$page_title = "Reports";
		require_once("../includes/headerOpen.php");
?>
		<link rel="stylesheet" type="text/css" href="../fancybox/jquery.fancybox-1.3.4.css" media="screen" />
		<style>
				html, body{
						height:100%;
				}
				#page{
						height:100%;
				}
				#content{
						height:75%;
						width: 100% !important;
				}
				#report-iframe-container{
						float:left;
						width:79%;
						height:100%;
				}
				#master-menu-container{
						float:left;
						display:inline-block;
						width:20%;
						height:100%;
						padding-right:10px;
						border-right:1px solid;
						overflow:hidden;
				}
				#master-menu-header{
						color:rgb(60,60,60);
						font-size:16px;
						line-height:34px;
						text-align:center;
						border-bottom:3px solid;
				}
				#master-menu-selection-container{
						margin-top:20px;
				}
				#master-menu-level1{
						position:relative;
				}
				#master-menu-level2{
						position:relative;
						opacity:0;
				}
				#master-menu-selection-container li{
						font-size:14px;
						width:100%;
						line-height:30px;
						text-align:center;
						margin-bottom:5px;
				}
				#master-menu-selection-container li:hover{
						background:rgb(60,60,60);
						color:white;
						cursor:pointer;
				}
				.master-menu-level2-selected{
						background:rgb(205,20,20) !important;
						color:white;
				}
				#report-controls{
						position:absolute;
						width:150px;
						height:50px;
						margin:10px;
						opacity:0;
				}
				#report-controls:hover{
						opacity:1;
				}
				#toggleFullscreen{
						opacity:0.3;
				}
				#toggleFullscreen:hover{
						opacity:1
				}
		</style>
		<script type="text/javascript" src="../fancybox/jquery.fancybox-1.3.4.pack.js"></script>
		<script type='text/javascript'>
				$('document').ready(function(){
						positionReportControls();
				});
				
				function fullscreen(){
						var fsw = $(document).width()-100;
						var fsy = Math.floor((9*fsw)/16);
						var targetURL = $('#report-url').val();
						if(targetURL != ""){
								$.fancybox({
										'type'						: 'iframe',
										'autoDimensions'	: false,
										'width'						: fsw,
										'height'					: fsy,
										'href'						: targetURL
								});
						}
				}
				
				function loansMenu1(reportHeader){
						$.get("loans-menu1.php",{},function(response){
								var top = -$('#master-menu-level1').outerHeight()-5;
								var masterMenuWidth = $('#master-menu-level1').width();
								$('#master-menu-level2').html(response).css({'top':top,'left':masterMenuWidth});
								
								//begin animations
								$('#master-menu-level1').animate({
												'left'			: -masterMenuWidth,
												'opacity'		: 0
								},300,function(){});
								
								$('#master-menu-level2').animate({
												'left'			: 0,
												'opacity'		: 1
								},300,function(){});
								
								// change master-menu-header
								$('#master-menu-header').html(reportHeader);
						});
				}
				
				function loadLoanReport(startTime, endTime, role, deptID, callingObj){
						$('.master-menu-level2-selected').removeClass('master-menu-level2-selected');
						$('#'+callingObj).addClass('master-menu-level2-selected');
						var targetURL = "http://art.usc.edu/loaner/reports/generateLoanReport.php?startTime="+startTime+"&endTime="+endTime+"&role="+role+"&deptID="+deptID;
						var iframeHTML = "<iframe id='report-iframe' src='"+targetURL+"' width='100%' height='100%' frameborder='0'></iframe>"
						$('#report-iframe-container').html(iframeHTML);
						$('#report-url').val(targetURL);
				}
				
				function positionReportControls(){
						var iframeContainerPosition = $('#report-iframe-container').position();
						var cssOBJ = {
								'top' 	: iframeContainerPosition.top,
								'left'	: iframeContainerPosition.left
						};
						$('#report-controls').css(cssOBJ);
				}
		</script>

<?php
		require_once("../includes/headerClose.php");
?>

<div id="content">
		<div id='master-menu-container'>
				<div id='master-menu-header'>Reports</div>
				<div id='master-menu-selection-container'>
						<ul id='master-menu-level1'>
								<li id='report-1' onclick="loansMenu1('Loans')">Loans</li>
								<li id='report-2'>Issued Items Based On User</li>
								<li id='report-3'>Items Past Their Expected Life</li>
						</ul>
						<ul id='master-menu-level2'></ul>
				</div>
		</div>
		<div id='menu1-content'></div>
		<div id='report-iframe-container'></div>
		<div id='report-controls'>
				<a href='javascript:fullscreen()'><img id='toggleFullscreen' src='../etc/fullscreen.png' width='26' height='20'/></a>
		</div>
		<input type='hidden' id='report-url'/>
		<div class='clear'></div>
</div>
<div class='clear'></div>
<?php
	require_once("../includes/footerNew.php");
?>
