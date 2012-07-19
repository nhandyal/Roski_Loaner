<?php
		// initialize rows to load based on page, search, and category
			
		//figure out how many rows are in the entire table
		$sqlQueryNUM = $queryString;
		$resultNum=mysql_query($sqlQueryNUM);
		echo mysql_error();
		$total_records=mysql_num_rows($resultNum);
		
		//get the page number from the page URL - GET
		$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 0; 
		
		//calculate the limit for the sql statment
		//enables paging by chosing start, stop rows
		if ($page){
			$from = ($page * $displayableRows) - $displayableRows;
		}
		else{
			$from = 0;
		}
		
		$queryString = $queryString." LIMIT ".$from.','.$displayableRows;
	
		$result = mysql_query($queryString);
		
		echo mysql_error();
?>