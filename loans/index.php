<?php
	$page_title = "Loans";
	$default_sort_field = "issue_date";
	$current_page = "index.php";
	$view;
	if(isset($_GET['view']))
		$view = $_GET['view'];
	else
		$view = "short";
		
	require_once("../includes/session.php");
	require_once("../includes/headerOpen.php");
?>

		<!-- STYLESHEETS  -->
		<link rel="stylesheet" type="text/css" href="../css/loans/index.css">
		<link rel="stylesheet" type="text/css" href="../fancybox/jquery.fancybox-1.3.4.css" media="screen" />
		
		<!-- JAVASCRIPTS  -->
		<script type="text/javascript" src="../fancybox/jquery.fancybox-1.3.4.pack.js"></script>
		<script type="text/javascript" src="../js/buildURL.js"></script>
		<script type="text/javascript" src="../js/loans/index.js"></script>

<?php
	require_once("../includes/headerClose.php");
	require_once("../includes/paging.php");
?>

<!--------------------------------------------------------------------------------------------------------------------->
<!------------------------------------------------- BEGIN MAIN PAGE CONTENT ------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->

<?php
		//----------------------------------------------- PAGING AND SORT CONTROLS --------------------------------------//
		
		//----------------------------------------------- Sort CONTROLS --------------------------------------//
		//set up the query for this page
		$queryString = "SELECT a.lid, a.kitid, a.equipmentid, a.userid, a.issue_date, a.due_date, a.return_date, a.fine, b.fname, b.lname FROM loans a, users b WHERE a.userid = b.userid AND a.deptID=".$_SESSION['dept'];
		
		// Pick which loans need to be displayed based on view parameter
		switch($view){
				case "short":
						$queryString = $queryString." AND (a.status=2 or a.status=3)";
						break;
				case "archive":
						$queryString = $queryString." AND a.status=4";
						break;
				case "long":
						$queryString = $queryString." AND a.status=7";
						break;
		}
		
		//check to see if the search field is populated
		if(isset($_GET['s'])){
				$s = $_GET['s'];
				$queryString = $queryString." AND (a.equipmentid LIKE '%".$s."' OR a.kitid LIKE '%".$s."' OR a.userid LIKE '%".$s."' or a.notes LIKE '%".$s."' )";
		}
		
		//set the sort field and direction
		//check to see which table the sort field needs to be applied to
		if(isset($_GET["sf"])){
				$sf = $_GET["sf"];
				$queryString = $queryString." ORDER BY `a`.`".$sf."`";	
		}
		
		//set the direction
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
						<li><a href='index.php'>Active Loans</a></li>
						<li><a href='index.php?view=archive'>Returned Loans</a></li>
						<li><a href='index.php?view=long'>Long Term Loans</a></li>
				</ul>
		</div>
		<div id="contentTableWrapper">
				<table id="contentTable">
						<tbody>
								<tr id="pagingBarTop">
										<td colspan="8">
												<?PHP doPages($displayableRows, $current_page, '', $total_records); ?>
										</td>
								</tr>
								<tr id="contentTableHeader">
										<?php
												$headerClasses = array(0 => "kitid", 1 => "equipmentid", 2 => "userid", 3 => "fname", 4 => "issue_date", 5 => "due_date", 6 => "fine", 7 => "filler");
												$headerTitles = array(0 => "KIT ID", 1 => "EQ ID", 2 => "USER ID", 3 => "NAME", 4 => "ISSUED", 5=> "DUE", 6 => "FINES", 7 => " ");		
												
												require_once("../includes/build-table-headers.php");
										?>
								</tr>
								<?php
									while($e = mysql_fetch_array($result)){
								?>
								<tr>
										<td><?php echo $e['kitid']; ?></td>
										<td><?php echo $e['equipmentid']; ?></td>
										<td><?php echo $e['userid']; ?></td>
										<td><?php echo $e['fname']; echo " "; echo $e['lname']; ?></td>
										<td><?php echo date("m-d-y" ,$e['issue_date']); ?></td>
										<td><?php echo date("m-d-y" ,$e['due_date']);	?></td>
										<td><?php echo $e['fine']; ?></td>
										<td>
												<a href="javascript:details(<?php echo $e["lid"].",'".$view."'";?>)" title="Details" ><img src='../etc/details.png' /></a>
										<?php
												if($view != "archive"){
										?>
														<a href="javascript:renew(<?php echo $e['lid'].",'".$view."'"; ?>)" title='Renew' ><img src='../etc/refresh.png' /></a>
														<a href="javascript:returnLoan(<?php echo $e['lid']; ?>)" title='Return Loan' ><img src='../etc/cross.png' /></a>
										<?php
												}
												else if($view == "archive" && ($_SESSION['role'] == 2 || $_SESSION['role'] == 3 || $_SESSION['role'] == 5)){
										?>
														<a href="javascript:editFine(<?php echo $e['lid'].",".$e['fine']; ?>)" title='Edit fine' ><img src='../etc/edit.png' /></a>
										<?php
												}
										?>
										</td>
								</tr>
								<?php } ?>
								<tr id="pagingBarBottom">
										<td colspan="8">
												<?PHP
												//Paging controls
												doPages($displayableRows, 'index.php', '', $total_records); 
												?>
										</td>
								</tr>
						</tbody>
				</table>
		</div>
</div>
<div class="clear"><!-- --></div>
<?php
	require_once("../includes/footerNew.php");
?>