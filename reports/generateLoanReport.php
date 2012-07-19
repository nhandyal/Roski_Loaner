<?php
		require_once("../includes/db.php");
		require_once("../includes/global.php");

		$startTime = $_GET['startTime'];
		$endTime = $_GET['endTime'];
		$role = $_GET['role'];
		$deptID = $_GET['deptID'];
		
		// issued kits for date range
		$query = "SELECT * FROM loans WHERE (issue_date>=$startTime AND issue_date<=$endTime) AND status<=4 AND equipmentid='' AND deptID=".$deptID;
		if($role == 5) // Sys-admin all department view
				$query = "SELECT * FROM loans WHERE (issue_date>=$startTime AND issue_date<=$endTime) AND status<=4 AND equipmentid=''";
		$issuedKitsResult = mysql_query($query);
		
		// issued equipment for date range
		$query = "SELECT * FROM loans WHERE (issue_date>=$startTime AND issue_date<=$endTime) AND status<=4 AND kitid='' AND deptID=".$deptID;
		if($role == 5) // Sys-admin all department view
				$query = "SELECT * FROM loans WHERE (issue_date>=$startTime AND issue_date<=$endTime) AND status<=4 AND kitid=''";
		$issuedEquipmentResult = mysql_query($query);
		
		// kits overdue / late for date range
		$query = "SELECT * FROM loans WHERE (issue_date>=$startTime AND issue_date<=$endTime) AND equipmentid='' and fine<>0 AND deptID=".$deptID." ORDER BY return_date ASC, due_date ASC";
		if($role == 5) // Sys-admin all department view
				$query = "SELECT * FROM loans WHERE (issue_date>=$startTime AND issue_date<=$endTime) AND equipmentid='' and fine<>0 ORDER BY return_date ASC, due_date ASC";
		$kitsOverdueResult = mysql_query($query);
		
		// equipment overdue / late for date range
		$query = "SELECT * FROM loans WHERE (issue_date>=$startTime AND issue_date<=$endTime) AND kitid='' and fine<>0 AND deptID=".$deptID." ORDER BY return_date ASC, due_date ASC";
		if($role == 5) // Sys-admin all department view
				$query = "SELECT * FROM loans WHERE (issue_date>=$startTime AND issue_date<=$endTime) AND kitid='' and fine<>0 ORDER BY return_date ASC, due_date ASC";
		$equipmentOverdueResult = mysql_query($query);
		
		// total fines incurred on kits
		$query = "SELECT SUM(fine) as totalFines FROM loans WHERE (issue_date>=$startTime AND issue_date<=$endTime) AND equipmentid='' AND fine<>0 AND deptID=".$deptID." GROUP BY equipmentid";
		if($role == 5) // Sys-admin all department view
				$query = "SELECT SUM(fine) as totalFines FROM loans WHERE (issue_date>=$startTime AND issue_date<=$endTime) AND equipmentid='' AND fine<>0 GROUP BY equipmentid";
		$totalKitFines = mysql_fetch_assoc(mysql_query($query));
		
		// total fines incurred on equipment
		$query = "SELECT SUM(fine) as totalFines FROM loans WHERE (issue_date>=$startTime AND issue_date<=$endTime) AND kitid='' AND fine<>0 AND deptID=".$deptID." GROUP BY kitid";
		if($role == 5) // Sys-admin all department view
				$query = "SELECT SUM(fine) as totalFines FROM loans WHERE (issue_date>=$startTime AND issue_date<=$endTime) AND kitid='' AND fine<>0 GROUP BY kitid";
		$totalEquipmentFines = mysql_fetch_assoc(mysql_query($query));
?>
<html>
		<head>
				<title>Loan Report <?php echo date('l F j, Y',$startTime)." -- ".date('l F j, Y',$endTime); ?></title>
				<style>
						html, body{
								height:100%;
						}
						#loanReport-master-container{
								padding:10px;
						}
						#report-controls{
								display:inline-block;
								float:right;
								position:relative;
								top:5px;
						}
						#toggleFullscreen{
								opacity:0.3;
						}
						#toggleFullscreen:hover{
								cursor:pointer;
								opacity:1;
						}
						#summary-container{
								margin-top:20px;
						}
						.report-header-element{
								line-height:27px;
								font-weight:bold;
								text-align:center;
						}
						.loanReport-summary-table-container{
								display:inline-block;
								width:100%;
						}
						#loanReport-summary-table {
								margin:auto;
						}
						#loanReport-summary-table td{
								text-align:right;
						}
						#loanReport-summary-table .table-header{
								font-weight:bold;
								width:20%;
						}
						#loanReport-summary-table .data{
								width:5%;
								font-size:13px;
						}
						#loanReport-summary-table .spacer{
								width:12.5%;
						}
						#loanReport-details-container{
								margin-top:20px;
						}
						.loanReport-details-table{
								margin:0px;
								margin-bottom:10px;
								width:100%;
								font-size:13px;
						}
						.loanReport-headerElement{
								width:20%;
								font-weight:bold;
						}
						.loanReport-details-table td{
								border-bottom:1px solid;
								border-color:rgb(120,120,120);
						}
						#report-controls{
								display:none;
								position:absolute;
						}
				</style>
		</head>
		<body>
				<div id='loanReport-master-container'>
						<div id='report-header'>
								<?php
										if($role == 5) // Sys-admin all department view
												echo "<div class='report-header-element' style='font-size:18px'>System Activity Report -- All Departments</div>";
										else
												echo "<div class='report-header-element' style='font-size:18px'>System Activity Report</div>";
								?>
								<div class='report-header-element'><?php echo date('l F j, Y',$startTime)." -- ".date('l F j, Y',$endTime); ?></div>
						</div>
						<div id='summary-container'>
								<div class='report-header-element'>System Summary</div>
								<div class='loanReport-summary-table-container'>
										<table id='loanReport-summary-table'>
												<tr>
														<td class='table-header'>Issued Loans</td>
														<td class='data'></td>
														<td class='spacer'></td>
														<td class='table-header'>Overdue Loans</td>
														<td class='data'></td>
														<td class='spacer'></td>
														<td class='table-header'>Fines Incurred</td>
														<td class='data'></td>
												</tr>
												<tr>
														<td>Kits:</td>
														<td><?php echo mysql_num_rows($issuedKitsResult); ?></td>
														<td></td>
														<td>Kits:</td>
														<td><?php echo mysql_num_rows($kitsOverdueResult); ?></td>
														<td></td>
														<td>Kits:</td>
														<td><?php echo $totalKitFines['totalFines']; ?></td>
												</tr>
												<tr>
														<td>Equipment:</td>
														<td><?php echo mysql_num_rows($issuedEquipmentResult); ?></td>
														<td></td>
														<td>Equipment:</td>
														<td><?php echo mysql_num_rows($equipmentOverdueResult); ?></td>
														<td></td>
														<td>Equipment:</td>
														<td><?php echo $totalEquipmentFines['totalFines']; ?></td>
												</tr>
										</table>
								</div>
						</div>
						<div id='loanReport-details-container'>
								<div class='report-header-element'>Overdue Kit Details</div>
								<table class='loanReport-details-table'>
										<tr class='loanReport-header'>
												<td class='loanReport-headerElement'>Kit ID</td>
												<td class='loanReport-headerElement'>User ID</td>
												<td class='loanReport-headerElement'>Due Date</td>
												<td class='loanReport-headerElement'>Return Date</td>
												<td class='loanReport-headerElement'>Fine</td>
										</tr>
										<?php
												while($r = mysql_fetch_assoc($kitsOverdueResult)){
														echo "<tr>";
														echo "<td class='loanReport-tableElement'>".$r['kitid']."</td>";
														echo "<td class='loanReport-tableElement'>".$r['userid']."</td>";
														echo "<td class='loanReport-tableElement'>".friendlyDateNoTime($r['due_date'])."</td>";
														echo "<td class='loanReport-tableElement'>";
														if($r['return_date']==0)
																echo "--";
														else
																echo friendlyDateNoTime($r['return_date']);
														echo "</td>";
														echo "<td class='loanReport-tableElement'>".$r['fine']."</td>";
														echo "</tr>";
												}
										?>
								</table>
								<div class='report-header-element'>Overdue Equipment Details</div>
								<table class='loanReport-details-table'>
										<tr class='loanReport-header'>
												<td class='loanReport-headerElement'>Equipment ID</td>
												<td class='loanReport-headerElement'>User ID</td>
												<td class='loanReport-headerElement'>Due Date</td>
												<td class='loanReport-headerElement'>Return Date</td>
												<td class='loanReport-headerElement'>Fine</td>
										</tr>
										<?php
												while($r = mysql_fetch_assoc($kitsOverdueResult)){
														echo "<tr>";
														echo "<td class='loanReport-tableElement'>".$r['kitid']."</td>";
														echo "<td class='loanReport-tableElement'>".$r['userid']."</td>";
														echo "<td class='loanReport-tableElement'>".friendlyDateNoTime($r['due_date'])."</td>";
														echo "<td class='loanReport-tableElement'>";
														if($r['return_date']==0)
																echo "--";
														else
																echo friendlyDateNoTime($r['return_date']);
														echo "</td>";
														echo "<td class='loanReport-tableElement'>".$r['fine']."</td>";
														echo "</tr>";
												}
										?>
								</table>
						</div>
				</div>
		</body>
</html>
