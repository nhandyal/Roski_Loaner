<?php
		$page_title = "Users";
		$default_sort_field = "userid";
		$current_page = "index.php";
		require_once("../includes/session.php");
		require_once("../includes/headerOpen.php"); //opens the head tag
?>

<!-- IMPORT ALL STYLESHEETS BEFORE JAVASCRIPTS -->
<link rel="stylesheet" type="text/css" href="../css/users/index.css">
<link rel="stylesheet" type="text/css" href="../fancybox/jquery.fancybox-1.3.4.css" media="screen" />

<!-- JAVASCRIPT -->
<script type="text/javascript" src="../fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="../js/buildURL.js"></script>
<script type="text/javascript" src="../js/users/index.js"></script>

<?php
	require_once("../includes/headerClose.php"); //closes the head tag and adds all universal header elements
	require_once("../includes/paging.php");
?>

<!--------------------------------------------------------------------------------------------------------------------->
<!------------------------------------------------- BEGIN MAIN PAGE CONTENT ------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->

<?php
		// Search field and sort
		// Univerasal query string
		$queryString = "SELECT userid, fname, lname, email, suspended, status, role FROM users WHERE ";
		
		if(isset($_GET['s'])){
				//check to make sure that the search field is not empty
				if($_GET['s'] != ""){
						$s = urldecode($_GET['s']);
						$s =str_replace(' ','%',$s);
						$queryString = $queryString."( userid LIKE '%".$s."%' OR fname LIKE '%".$s."%' OR lname LIKE '%".$s."%' OR email LIKE '%".$s."%') AND ";
				}
		}
		
		if(isset($_GET['type'])){
				if(isset($_GET['s']))	
						$queryString = $queryString." AND role=".$_GET['type']." AND ";
				else
						$queryString = $queryString."role=".$_GET['type']." AND ";
		}
		
		$queryString = $queryString." deptID=".$_SESSION['dept'];
		
		//set the sort order and direction
		if(isset($_GET["sf"])){
				$queryString = $queryString." ORDER BY `users`.`".$_GET["sf"]."`";
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
						<input type="text" name="s" id="searchBox" />
						<input type="submit" value="Search"/> <span class='hint'>Search by User ID, Email address, Name</span>
				</form>
		</div>
		<div id='type_selection'>
				<ul>
						<li><a href='index.php'>All Users</a></li>
						<li><a href='index.php?type=1'>Student</a></li>
						<li><a href='index.php?type=2'>Work Study</a></li>
						<?php
								$admin = ($_SESSION['role'] == 3 || $_SESSION['role'] == 5);
								if($admin){
						?>
										<li><a href='index.php?type=3'>Administrator</a></li>
										<li><a href='index.php?type=6'>Cashier</a></li>
						<?php
								}
						?>
            <li><a href='index.php?type=4'>Faculty</a></li>
				</ul>
		</div>
		<br/>
		<?php require_once("../includes/initialize-paging.php"); ?>
		<div id="contentTableWrapper">
				<table id="contentTable">		
						<tbody>
								<tr id="pagingBarTop">
										<td colspan="5"><?PHP doPages($displayableRows, $current_page, '', $total_records); ?></td>
								</tr>
								<tr id="contentTableHeader">
										<?php
												$headerClasses = array(0 => "userid", 1 => "fname", 2=> "email", 3=> "role");
												$headerTitles = array(0 => "ID", 1 => "Name", 2=> "Email", 3 => "Type");
											
												require_once("../includes/build-table-headers.php");
										?>
										<td class="filler">
												<?php
														if($admin || $_SESSION['role'] == 2){
												?>
																<a href='javascript:suspendAllStudents(true)' id='suspend_all_stud' title="Suspend All Students"><img src='../etc/suspended_user.png' /></a>
																<a href='javascript:suspendAllStudents(false)' id='unsuspend_all_stud' title="Re-Activate All Students"><img src='../etc/not_suspended_user.png' /></a>
												<?php
														}
												?>
										</td>
								</tr>
								<?php
										while($user = mysql_fetch_assoc($result)){
												$print_row = true;
												if($user['role'] == 3 || $user['role'] == 5){
														if(!$admin){
																$print_row = false;
														}
												}
												if($print_row){
								?>
														<tr id="xxx_<?php echo $user['userid']; ?>">
																<td><?php echo $user['userid']; ?></td>
																<td><?php echo $user['fname']. ' ' . $user['lname']; ?></td>
																<td><?php echo $user['email']; ?></td>
																<td><?php echo $user_role[$user['role']]; ?></td>
																<td>
																		<?php
																				if($user['suspended'] == "1"){
																						if($admin || $_SESSION['role'] == 2){
																		?>
																								<a href="javascript:suspend(false,'<?php echo $user['userid'];?>')" class="suspended" title="Un-Suspend"><img src='../etc/suspended_user.png' /></a>
																		<?php
																						}
																						else
																								echo "<img src='../etc/suspended_user.png' title='suspended' />";
																				}
																				else{
																						if($admin || $_SESSION['role'] == 2){
																		?>
																								<a href="javascript:suspend(true,'<?php echo $user['userid'];?>')" class="not_suspended" title="Suspend"><img src='../etc/not_suspended_user.png' /></a>
																		<?php
																						}
																						else
																								echo "<img src='../etc/not_suspended_user.png' title='not-suspended' />";
																				}
																		?>
																		<a href="javascript:showDetailsLightbox('<?php echo $user["userid"]?>')" ><img src='../etc/details.png' /></a>
																		<a href="edit.php?id=<?php echo $user['userid']; ?>" class="edit" title="Edit"><img src='../etc/edit.png' /></a>
																		<?php
																				if($user['status'] == "1" & $admin){
																		?>
																						<a href="javascript:lockUser(true,'<?php echo $user['userid']; ?>')" class="enable" title="Lock"><img src='../etc/unlock.png' /></a>
																		<?php
																				}
																				else if($admin){
																		?>
																						<a href="javascript:lockUser(false,'<?php echo $user['userid']; ?>')" class="disable" title="Un-Lock"><img src='../etc/lock.png' /></a>
																		<?php
																				}
																				if($admin || $_SESSION['role'] == 2) {
																		?>
																						<a href="javascript:deleteAccount('<?php echo $user['userid']; ?>')" class="delete" title="Delete"><img src='../etc/delete.png' /></a>
																		<?php
																				}
																		?>
																</td>
														</tr>
								<?php
												}
										}
								?>
								<tr id="pagingBarBottom">
										<td colspan="5"><?PHP doPages($displayableRows, $current_page, '', $total_records); ?></td>
								</tr>
						</tbody>
				</table>
		</div>
</div>
	
<div class="clear"></div>
<?php
	require_once("../includes/footerNew.php");
?>