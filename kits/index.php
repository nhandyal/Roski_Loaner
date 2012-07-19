<?php
	$page_title = "Kits";
	$default_sort_field = "kitid";
	$current_page = "index.php";
	$displayableRows = 25;
	require_once("../includes/session.php");
	require_once("../includes/headerOpen.php"); //opens the head tag
?>

<!-- IMPORT ALL STYLESHEETS BEFORE JAVASCRIPTS -->
<link rel="stylesheet" type="text/css" href="../css/kits/index.css">
<link rel="stylesheet" type="text/css" href="../fancybox/jquery.fancybox-1.3.4.css" media="screen" />

<!-- JAVASCRIPT -->
<script type="text/javascript" src="../fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="../js/buildURL.js"></script>
<script type="text/javascript" src="../js/kits/index.js"></script>

<?php
	require_once("../includes/headerClose.php"); //closes the head tag and adds all universal header elements
	require_once("../includes/paging.php");
?>

<!--------------------------------------------------------------------------------------------------------------------->
<!------------------------------------------------- BEGIN MAIN PAGE CONTENT ------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->

<?php
	$queryString = "SELECT kitid, kit_desc, loan_length FROM kits WHERE ";
	
	//check to see if the search field is populated
	if(isset($_GET['s'])){
		if($_GET['s'] != ""){
			$queryString = $queryString."( kitid LIKE '%".$_GET['s']."%' OR kit_desc LIKE '%".$_GET['s']."%' OR loan_length LIKE '%".$_GET['s']."%' ) AND ";
		}
	}	

	$queryString = $queryString." deptID=".$_SESSION['dept']." AND status<>-1";	
	
	
	//set the sort order and direction
	if(isset($_GET["sf"])){
		$queryString = $queryString." ORDER BY `kits`.`".$_GET["sf"]."`";
	    	if($_GET["dir"] == "ASC"){
			$queryString = $queryString." ASC";	
		}
		else{
			$queryString = $queryString." DESC";
		} 
	}
	
	$result = mysql_query($queryString);
?>

<div id="sidebar">
	<?php require_once("sidebar.php"); ?>
</div>

<div id="content">
	<div id="contentSearch">
		<form method="GET">
			<input type="text" name="s" id="searchBox"  size="35px"/>
			<input type="submit" value="Search"/>
			<span class='hint'>Search by Kit ID, Kit Description, or Notes</span>
		</form>
	</div>
	<?php require_once("../includes/initialize-paging.php"); ?>
	<div id="contentTableWrapper">
		<table id="contentTable">
			<tbody>
				<tr id="pagingBarTop">
					<td colspan="4"><?PHP doPages($displayableRows, $current_page, '', $total_records); ?></td>
				</tr>
				<tr id="contentTableHeader">
				    <?php
					    $headerClasses = array(0 => "kitid", 1 => "kit_desc", 2=> "loan_length");
					    $headerTitles = array(0 => "ID", 1 => "Description", 2=> "Loan Period");
					    
							require_once("../includes/build-table-headers.php");
				    ?>
				    <td class="filler"></td>
				</tr>
				<?php
					while($kit = mysql_fetch_assoc($result)){
				?>
				<tr id="xxx_<?php echo $kit['kitid']; ?>">
					<td><?php echo $kit['kitid']; ?></td>
					<td><?php echo $kit['kit_desc']; ?></td>
					<td><?php echo $kit['loan_length']; ?></td>
					<td>
						<a href="javascript:showDetailsLightbox('<?php echo $kit["kitid"]?>')" ><img src='../etc/details.png' /></a>
						<a href="edit.php?id=<?php echo $kit['kitid']; ?>" class="edit" title="Edit"><img src='../etc/edit.png' /></a>
						<img src='../etc/cross.png' onclick="deactivate(<?php echo "'".$kit['kitid']."'"; ?>)" title="Deactivate" style="cursor:pointer"/>
					</td>
				</tr>
				<?php
					}
				?>
				<tr id="pagingBarBottom">
					<td colspan="4"><?PHP doPages($displayableRows, $current_page, '', $total_records); ?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<div class="clear"><!-- --></div>
	
<?php
	require_once("../includes/footerNew.php");
?>