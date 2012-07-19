<?php

	require_once("../includes/session.php");

	require_once("../includes/db.php");

	

	

	if(isset($_GET['changepass'])){

		$oldpass	= sha1(trim(addslashes($_POST['oldpassword'])));

		$newpass	= sha1(trim(addslashes($_POST['newpassword'])));

		$cnewpass	= sha1(trim(addslashes($_POST['cnewpassword'])));

		if(trim($newpass) != trim($cnewpass)){

			echo "Password and Confirm password doesn't match";

		}

		else{

			$query = "update users set password = '$newpass' where password = '$oldpass' AND userid = '$user'";

			mysql_query($query) or die(mysql_error());	

			

			if(mysql_affected_rows() == 1){

				echo "Success, Password changed";

			}

			else{

				echo "Error changing your password";

			}

		}

	}

	

	

	if(isset($_POST['forgot'])){

		$id = $_POST['username'];

		$query = "select email from users where userid = '$id'";

		$result = mysql_query($query) or die (mysql_error());

		

		if(mysql_num_rows($result) == 1){

			$res = mysql_fetch_assoc($result);

			$new_pass = substr(sha1(time() * rand()), 1, 8); //generate new random password.

			$email = $res['email'];





			$headers = "From: Loaner Admin, Roski School of Fine Arts";

			$headers .= "Reply-To: nmurthy@usc.edu\r\n";

			





			if(mail($email, 'New Password : Loaner, Roski School of Fine Arts', 'You new password is: '.$new_pass, '$headers')){

			//if(mysql_errno() == 0){

				$hashed_pass = sha1($new_pass);

				$query = "update users set password = '$hashed_pass' where userid = '$id'";

				$result = mysql_query($query) or die (mysql_error());

				if(mysql_errno() == 0){

					echo "New Password has been emailed at ".$email;

				}

				else{

					echo "Error emailing the password. Please ask administartor to reset it for you.";

				}

			}

			else{

				echo "Error occured";

			}

			

		}

		else{

			echo "Invalid User ID";

		}

	}

?>