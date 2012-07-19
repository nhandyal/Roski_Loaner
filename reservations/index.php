<?php
	$page_title = "Reservations";
	$default_sort_field = "issue_date";
	$current_page = "index.php";
	$displayableRows = 25;
	$view;
		if(isset($_GET['view']))
				$view = $_GET['view'];
		else
				$view = "active";
	require_once("../includes/session.php");
	require_once("../includes/headerOpen.php"); //opens the head tag
?>

		<!-- IMPORT ALL STYLESHEETS BEFORE JAVASCRIPTS -->
		<link rel="stylesheet" type="text/css" href="../css/reservations/index.css">
		<link rel="stylesheet" type="text/css" href="../fancybox/jquery.fancybox-1.3.4.css" media="screen" />
		
		<!-- JAVASCRIPT -->
		<script type="text/javascript" src="../fancybox/jquery.fancybox-1.3.4.pack.js"></script>
		<script type="text/javascript" src="../js/buildURL.js"></script>
		<script type="text/javascript" src="../js/reservations/index.js"></script>

<?php
	require_once("../includes/headerClose.php"); //closes the head tag and adds all universal header elements
	require_once("../includes/paging.php");
?>

<?php
		//search field, category search, and sort
		//you should be able to search and select a category
		//univerasal query string, if sort by category is all and no search items, use this query		
		$queryString = "SELECT * FROM loans a WHERE deptID=".$_SESSION['dept'];
		if($view == "active"){
				$queryString = $queryString." AND status=5";
		}
		else if($view == "canceled"){
				$queryString = $queryString." AND status=6";
		}
		if(isset($_GET['s'])){
	      $s = urldecode($_GET['s']);
				$s = str_replace(' ','%',$s);
	      $queryString = $queryString." AND (equipmentid LIKE '%".$s."%' OR kitid LIKE '%".$s."%' OR userid LIKE '%".$s."%' or notes LIKE '%".$s."%' )";
	  }
	  
		//append final search parameters and sort order
		if(isset($_GET['sf'])){
				$sf = $_GET['sf'];
				$queryString = $queryString." ORDER BY `a`.`".$sf."`";
		}
		else{
				$queryString = $queryString." ORDER BY `a`.`".$default_sort_field."`";
		}
		
		if(isset($_GET["dir"])){
				if($_GET["dir"] == "ASC"){
						$queryString = $queryString." ASC";	
				}
				else{
						$queryString = $queryString." DESC";
				}	
		}
		
	  //----------------------------------------------- PAGING CONTROLS --------------------------------------//
		require_once("../includes/initialize-paging.php");
?>
<div id="sidebar">
		<?php require_once("sidebar.php"); ?>
</div>
<div id="content">
		<div id="contentSearch">
				<form method="GET">
						<input type="text" name="s" id="searchBox"  size="35px"/>
						<input type="submit" value="Search"/>
						<span class='hint'>Search by Equipment ID, Kit ID, User ID, or Notes</span>
				</form>
		</div>
		<div id='type_selection'>
				<ul>
						<li><a href='index.php'>Active Reservations</a></li>
						<li><a href='index.php?view=canceled'>Canceled Reservations</a></li>
				</ul>
		</div>
		<p class='error'></p>
		<div id="contentTableWrapper">
				<table id="contentTable">
						<tbody>
								<tr id="pagingBarTop">
										<td colspan="6"><?PHP doPages($displayableRows, $current_page, '', $total_records); ?></td>
								</tr>
								<tr id="contentTableHeader">
										<?php
												$headerClasses = array(0 => "kitid", 1 => "equipmentid", 2=> "userid", 3=> "issue_date", 4=> "due_date", 5=>"filler");
												$headerTitles = array(0 => "Kit ID", 1 => "Equipment ID", 2=> "User ID", 3=> "Issue Date", 4=> "Due Date", 5=>"");
												
												require_once("../includes/build-table-headers.php");
										?>
								</tr>
								<?php
										while($loan = mysql_fetch_array($result)){
								?>
												<tr id="<?php echo "xxx_".$loan['loanid']; ?>">
												<td><?php echo $loan['kitid']; ?></td>
												<td><?php echo $loan['equipmentid']; ?></td>
												<td><?php echo $loan['userid']; ?></td>
												<td><?php echo date("m-d-y -- h:i A" ,$loan['issue_date']); ?></td>
												<td><?php echo date("m-d-y -- h:i A" ,$loan['due_date']); ?></td>
												<td>
														<a href="javascript:showDetailsLightbox(<?php echo $loan['lid'].",'".$view."'"; ?>)" class="details" title="Details" ><img src='../etc/details.png' /></a>
														<?php
																if($view == "active"){
																		echo "<a href='javascript:validateCheckout(".$loan['lid'].")' class='issue' title='Issue'><img src='../etc/issue.png' /></a>";
																		echo "<a href='javascript:cancel(".$loan['lid'].")' class='cancel' title='Cancel'><img src='../etc/cross.png' /></a>";
																}
														?>
												</td>
										</tr>
								<?php
										}
								?>
								<tr id="pagingBarBottom">
										<td colspan="6"><?PHP doPages($displayableRows, $current_page, '', $total_records); ?></td>
								</tr>
						</tbody>
				</table>
		</div>
		<div id='lightBoxData'></div>
</div>
<div class="clear"></div>

<?php
	require_once("../includes/footerNew.php");
?>