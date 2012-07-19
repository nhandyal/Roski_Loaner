<?php
		require_once("../includes/session.php");
		require_once("../includes/db.php");
		require_once("../includes/global.php");
		
		$now = time();
		$today = getdate(time());
		$month = $today['mon'];
		$year = $today['year'];
		
		$daysAhead = ($today['wday']+2)%7;
		if($today['hours'] < 13 && $today['wday'] == 5)
				$daysAhead += 7;
		
		$weekStart = $today['mday'] - $daysAhead;
		if($weekStart <= 0)
				$month -= 1;
		
		
		if($month <= 0)
				$year -= 1;
		
		

		$endPastWeek = mktime(13,0,0,$month,$weekStart,$year);
		$startPastWeek = ($endPastWeek - (7*24*60*60))-60;
		
		$monthStart = mktime(13,0,0,$today['mon'],1,$today['year']);
		$yearStart = mktime(0,0,0,1,1,$today['year']);
		
?>

<!--<div id='loanReport-selection-container'>
		<div class='section-header'>System Activity</div>
		<div class='selection-container'>
				<ul>-->
						<li id='ovd-1' onclick="loadLoanReport(<?php echo $startPastWeek.",".$endPastWeek.",".$_SESSION['role'].",".$_SESSION['dept']; ?>,'ovd-1')">Past Week</li>
						<li id='ovd-2' onclick="loadLoanReport(<?php echo $monthStart.",".$now.",".$_SESSION['role'].",".$_SESSION['dept']; ?>,'ovd-2')">Month To Date</li>
						<li id='ovd-3' onclick="loadLoanReport(<?php echo $yearStart.",".$now.",".$_SESSION['role'].",".$_SESSION['dept']; ?>,'ovd-3')">Year To Date</li>
						<li id='ovd-4'>Custon Date Range</li>
<!--				</ul>
		</div>
</div>
<div class='clear'></div>-->