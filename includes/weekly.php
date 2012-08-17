<?php
	require_once("../includes/db.php");
	require_once("../includes/global.php");

	echo "CRON Process End<br>";


    echo "Financial Transactions<br>";
	
	$current_time = time();

	$end_time     =  mktime(23,59,59, date('m',$current_time), date('d',$current_time), date('Y',$current_time));
	$start_time   =  $end_time - (60*60*24*7) + 1;

	$subj = "Transactions Report - ". friendlyDate($start_time)." to ". friendlyDate($end_time);

	
	$query = "select * from fines where timestamp >= $start_time AND timestamp <= $end_time";
	$result = mysql_query($query) or die (mysql_error());

	if(mysql_num_rows($result) == 0){
		$msg = 'No transactions';
	}
	else{
		
		$msg = '<table>';
		$msg .= '<thead><tr><th>Loan ID</th><th>Amount</th><th>Cashier</th><th>Date</th>';
		
		while($r = mysql_fetch_assoc($result)){
			$msg .= '<tr>';
	
			$msg .= '<td>'.$r["loanid"].'</td>';
			$msg .= '<td>'.$r["amount"].'</td>';
			$msg .= '<td>'.$r["accepted_by"].'</td>';
			$msg .= '<td>'.friendlyDate($r["timestamp"]).'</td>';
			
			$msg .= '</tr>';
		}

		$msg.= '</table>';
			
	}

	echo $msg;

	echo $subj;

	$headers = "From: ".$admin_email;
	$headers .= "Reply-To: ".$admin_email."\r\n";

	mail($admin_email, $subj, $msg, $headers);









	$subj = "Loans Report - ". friendlyDate($start_time)." to ". friendlyDate($end_time);

	
	$query = "select distinct * from loans where (status = 1 OR status = 3) AND (issue_date >= $start_time || issue_date<=$end_time || due_date >= $start_time || due_date <= $end_time || return_date >= $start_time || return_date <= $end_time)";
			$result = mysql_query($query) or die(mysql_error());
			
			
			$html = "";
			
			//$html .= $sd ."-".$ed."<br>";
			
			$html .="<table id='list'>";
			$html .="<thead>";
			$html .="<tr>";
			$html .="<th>";
			$html .="User ID";
			$html .="</th>";
			$html .="<th>";
			$html .="Kit ID";
			$html .="</th>";
			$html .="<th>";
			$html .="# Renewed";
			$html .="</th>";
			$html .="<th>";
			$html .="Fine";
			$html .="</th>";
			$html .="<th>";
			$html .="Fine Paid";
			$html .="</th>";
			$html .="<th>";
			$html .="Issue Date";
			$html .="</th>";
			$html .="<th>";
			$html .="Due Date";
			$html .="</th>";
			$html .="<th>";
			$html .="Return Date";
			$html .="</th>";
			$html .="</tr>";
			$html .="</thead>";
			
			$html .="<tbody>";
			while($r = mysql_fetch_assoc($result)){
				$html .="<tr>";
				$html .="<td>";
				$html .=$r['userid'];
				$html .="</td>";
				$html .="<td>";
				$html .=$r['kitid'];
				$html .="</td>";
				$html .="<td>";
				$html .=$r['renew_count'];
				$html .="</td>";
				$html .="<td>";
				$html .=$r['fine'];
				$html .="</td>";
				$html .="<td>";
				$html .=$r['fine_paid'];
				$html .="</td>";
				$html .="<td>";
				$html .=friendlyDate($r['issue_date']);
				$html .="</td>";
				$html .="<td>";
				$html .=friendlyDate($r['due_date']);
				$html .="</td>";
				$html .="<td>";
				$html .=friendlyDate($r['return_date']);
				$html .="</td>";
				$html .="</tr>";
			}
			$html .="</tbody>";
			$html .= "<table>";
			
			echo $html;

	echo $subj;

	$headers = "From: ".$admin_email;
	$headers .= "Reply-To: ".$admin_email."\r\n";
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	mail($admin_email, $subj, $html, $headers);
	
	
	echo "CRON Process End<br>";
?>
