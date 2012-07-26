<?php
		require_once("../includes/session.php");
		require_once("../includes/db.php");
		require_once("../includes/global.php");
		
		$userNum = $_GET['userNum'];
		$query = "SELECT userid, fname, lname FROM users WHERE userNum=".$userNum;
		$result = mysql_query($query);
		$r = mysql_fetch_assoc($result);
		$userid = $r['userid'];
		$name = $r['fname']." ".$r['lname'];
		$userString = $r['fname']." ".$r['lname']." - ".$userid;
		
		$totalFine = 0;
		$totalPayment = 0;
		$outstandingBalance = 0;
?>

<div id='user-fines-header'>
		<div class='user-fines-header-element' id='user-fines-user'><?php echo $userString;?></div>
		<div class='user-fines-header-element'><?php echo date("D M d, Y h:ia",time());?></div>
</div>
<div id='user-fines-content'>
		<table id='user-fines-fineTable'>
				<tr class='user-fines-tableHeader'>
						<td class='user-fines-tableHeader-element table-element-title'>Fine</td>
						<td class='user-fines-tableHeader-element' >Due Date</td>
						<td class='user-fines-tableHeader-element' >Return Date</td>
						<td class='user-fines-tableHeader-element' >Days Late</td>
						<td class='user-fines-tableHeader-element table-element-amount'>Amount ($)</td>
				</tr>
				<?php
						$query = "SELECT * FROM loans WHERE userid='".$userid."' AND fine > 0";
						$result = mysql_query($query);
						while($r = mysql_fetch_assoc($result)){
								$totalFine = $totalFine + $r['fine'];
								$days_late = ceil(($current_time - $r['due_date'])/(24*60*60));
								$iskit = true;
								if($r['equipmentid'] != "")
										$iskit = false;
								
								echo "<tr>";
										echo "<td class='table-element'>";
												echo "Loan ".$r['lid'];
												if($iskit)
														echo ": Kit ID: ".$r['kitid'];
												else
														echo ": Equipment ID: ".$r['equipmentid'];
										echo "</td>";
										echo "<td class='table-element'>".date("D M d, Y",$r['due_date'])."</td>";
										if($r['return_date'] == 0)
												echo "<td class='table-element' style='text-align:center'>--</td>";
										else
												echo "<td class='table-element'>".date("D M d, Y",$r['return_date'])."</td>";
										echo "<td class='table-element'>".$days_late."</td>";
										echo "<td class='table-element'>".$r['fine']."</td>";
								echo "</tr>";
						}
								echo "<td class='total red' colspan='4'>Total</td>";
								echo "<td class='total red'>$".$totalFine."</td>";
						echo "</tr>";
				?>
		</table>
		<table id='user-fines-paymentsTable'>
				<tr class='user-fines-tableHeader'>
						<td class='user-fines-tableHeader-element table-element-title'>Payment</td>
						<td class='user-fines-tableHeader-element' >Date Paid</td>
						<td class='user-fines-tableHeader-element' >Accepted By</td>
						<td class='user-fines-tableHeader-element table-element-amount'>Amount ($)</td>
				</tr>
				<?php
						$query = "SELECT * FROM finePayments WHERE userid='".$userid."'";
						$result = mysql_query($query);
						$i = 1;
						while($r = mysql_fetch_assoc($result)){
								$totalPayment = $totalPayment + $r['amount'];
								echo "<tr>";
										echo "<td class='table-element'>Payment: ".$i."</td>";
										echo "<td class='table-element'>".date("D M d, Y",$r['timestamp'])."</td>";
										echo "<td class='table-element'>".$r['accepted_by']."</td>";
										echo "<td class='table-element'>".$r['amount']."</td>";
								echo "</tr>";
								$i++;
						}
						echo "<td class='total green' colspan='3'>Total</td>";
						echo "<td class='total green'>$".$totalPayment."</td>";
				?>
		</table>
		<?php
				$outstandingBalance = $totalFine - $totalPayment;
		?>
		<table id='user-fines-balanceTable'>
				<tr>
						<td class='total red' style="width:90%">Outstanding Balance</td>
						<td class='total red table-element-amount'>$<?php echo $outstandingBalance; ?></td>
				</tr>
		</table>
		<div id='div-button' onclick='payUserFine(<?php echo $userNum.',"'.$userid.'","'.$name.'"'; ?>)'>Pay Fine</div>
		<input id='outstandingBalance' type='hidden' value='<?php echo $outstandingBalance; ?>'/>
</div>

