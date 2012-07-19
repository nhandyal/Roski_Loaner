<?php
	$page_title = "Equipment";
	$default_sort_field = "equipmentid";
	$current_page = "index.php";
	$displayableRows = 25;
	require_once("../includes/session.php");
	require_once("../includes/headerOpen.php"); //opens the head tag
?>

<!-- IMPORT ALL STYLESHEETS BEFORE JAVASCRIPTS -->
<link rel="stylesheet" type="text/css" href="../css/equipments/index.css">
<link rel="stylesheet" type="text/css" href="../fancybox/jquery.fancybox-1.3.4.css" media="screen" />

<!-- JAVASCRIPT -->
<script type="text/javascript" src="../fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="../js/buildURL.js"></script>
<script type="text/javascript" src="../js/equipments/index.js"></script>

<?php
	require_once("../includes/headerClose.php"); //closes the head tag and adds all universal header elements
	require_once("../includes/paging.php");
?>

<!--------------------------------------------------------------------------------------------------------------------->
<!------------------------------------------------- BEGIN MAIN PAGE CONTENT ------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->

<?php
	//search field, category search, and sort
	//you should be able to search and select a category
	//univerasal query string, if sort by category is all and no search items, use this query
	$queryString = "SELECT equipments.kitid, equipments.equipmentid, equipments.model, equipCategory.equipCatName, locations.locationName FROM equipments LEFT JOIN equipCategory ON equipments.equipCatID = equipCategory.equipCatID LEFT JOIN locations ON equipments.locationID = locations.locationID WHERE ";
	
	//check to see if sort by category or search field is populated and append to the query string
	if(isset($_GET['s'])){
		//check to make sure that the search field is not empty
		if($_GET['s'] != ""){
				$s = urldecode($_GET['s']);
				$s =str_replace(' ','%',$s);
				$queryString = $queryString."( equipmentid LIKE '%".$s."%' OR kitid LIKE '%".$s."%' OR model LIKE '%".$s."%' OR equipCatName LIKE '%".$s."%' OR locationName LIKE '%".$s."%' )";	
		}
	}
	if(isset($_GET['q'])){
		//make sure user did not select all categories, and search field is active and not empty
		//if search is active, prepend AND
		if($_GET['q'] != "ALL" && isset($_GET['s']) && $_GET['s'] != ""){
			$queryString = $queryString." AND equipments.equipCatID=".$_GET['q'];
		}
		elseif($_GET['q'] != "ALL"){
			$queryString = $queryString." equipments.equipCatID=".$_GET['q'];
		}
	}
	
	//append final search parameters and sort order
	//check if s or q are set
	if(isset($_GET['q']) || isset($_GET['s'])){
		if($_GET['q'] != "ALL" || $_GET['s'] != ""){
			$queryString = $queryString." AND";	
		}
	}
	$queryString = $queryString." equipments.deptID=".$_SESSION['dept']." AND equipments.status<>-1";
	
	//sort order and check to see which table the sort needs to be applied to
	if(isset($_GET["sf"])){
		$sf = $_GET["sf"];
		if($sf == "equipCatName"){
				$queryString = $queryString." ORDER BY `equipCategory`.`".$sf."`";
		}
		else if($sf == "locationName"){
				$queryString = $queryString." ORDER BY `locations`.`".$sf."`";
		}
		else{
			$queryString = $queryString." ORDER BY `equipments`.`".$sf."`";
		}
		
	}
	
	if(isset($_GET["dir"])){
		if($_GET["dir"] == "ASC"){
			$queryString = $queryString." ASC";	
		}
		else{
			$queryString = $queryString." DESC";
		}	
	}
	
?>

<div id="sidebar">
	<?php require_once("sidebar.php"); ?>
</div>

<div id="content">
	<!--Search-->
	<div id='contentSearch'>
		<form id="search" method="GET">
				<div>
						<input type="text" name="s" id="searchBox"  size="35px"/>
						<input type="submit" value="Search"/>
						<span class='hint'>Search by Equipment ID, Kit ID, Model, Category, or Location</span>
				</div>
				<div style="margin-top:5px">
						<select id="category-select" class="pf-field" name="q">
							<?php
								if(isset($_GET['q'])){
									$q = $_GET['q'];
								}
								else{
									$q = "All";
								}
								echo '<option value="ALL">All Categories</option>';
								$query = "select equipCatID, equipCatName from equipCategory ORDER BY equipCatName";
								$result = mysql_query($query) or die (mysql_error());
								while($equipCat = mysql_fetch_array($result)){
									if($equipCat['equipCatID'] == $q){
										echo "<option value='".$equipCat['equipCatID']."' selected='selected'";
										echo ">".$equipCat['equipCatName']."</option>";
									}
									else{
										echo "<option value='".$equipCat['equipCatID']."'";
										echo ">".$equipCat['equipCatName']."</option>";
									}
								}
							?>
						</select>
				</div>
		</form>
	</div>
	<?php require_once("../includes/initialize-paging.php"); ?>
	<div id="contentTableWrapper">
		<table id="contentTable">
			<tbody>
			<tr id="pagingBarTop">
				<td colspan="6"><?PHP doPages($displayableRows, $current_page, '', $total_records); ?></td>
			</tr>
			<tr id="contentTableHeader">
				<?php
					$headerClasses = array(0 => "equipmentid", 1 => "kitid", 2=> "model", 3=> "equipCatName", 4=> "locationName");
					$headerTitles = array(0 => "ID", 1 => "KIT", 2=> "MODEL", 3=> "Category", 4=>"Location");
					
					require_once("../includes/build-table-headers.php");
				?>
				<td class="filler"></td>
			</tr>
			<?php
				while($e = mysql_fetch_array($result)){
			?>
			<tr id="<?php echo 'xxx_'.$e['equipmentid']; ?>">
				<td><?php echo $e['equipmentid']; ?></td>
				<td><?php echo $e['kitid']; ?></td>
				<td><?php echo $e['model']; ?></td>
				<td><?php echo $e['equipCatName']; ?></td>
				<td><?php echo $e['locationName']; ?></td>
				<td>
						<a href="javascript:showDetailsLightbox('<?php echo $e["equipmentid"]?>')"><img src='../etc/details.png' /></a>
						<a href="edit.php?id=<?php echo $e['equipmentid']; ?>" class="edit" title="Edit"><img src='../etc/edit.png' /></a>
						<img src='../etc/cross.png' onclick="deactivate(<?php echo "'".$e['equipmentid']."'"; ?>)" title="Deactivate" style="cursor:pointer"/>
				</td>
			</tr>
			<?php } ?>
			<tr id="pagingBarBottom" >
				<td colspan="6"><?PHP doPages($displayableRows, $current_page, '', $total_records); ?></td>
			</tr>
			</tbody>
		</table>
	</div>
</div>

<div class="clear"></div>

<?php
	require_once("../includes/footerNew.php");
?>
