<?php

	$body = $_POST['bug'];

	$to = "a.k.vora@gmail.com";

	$subj = "Loaner Bug Report";

	

	if(@mail($to, $subj, $body, "from:nmurthy@usc.edu")){

		echo "OK";

	}

	else{

		echo "FAIL";

	}

?>