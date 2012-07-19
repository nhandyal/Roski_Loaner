<?php
		$page_title = "Forgot Password";
		require_once("../includes/headerOpen.php");
?>
<?php
		require_once("../includes/headerClose.php");
		
		if(isset($_POST['userid'])){
				// check to see if valid username
				$query = "SELECT email FROM users WHERE userid='".$_POST['userid']."'";
				$result = mysql_query($query);
		}
?>



<div id="forgot_passowrd" class="ui-widget">
	  <h3 class="ui-widget-header">Forgot Password</h3>
		<dl class="ui-content">
				<dd>
				<?php
						if(isset($_POST['userid'])){
								if(mysql_num_rows($result) == 0){
				?>
										<dt style='color:rgb(205,20,20); font-size:12px'>Invalid username</dt>
												<form action='forgot_password.php' method='post'>
												<input type="text" name="userid" id="userid"/>
												<input type='submit' name="submit" id="submit" value="Submit"/>
										</form>
				<?php
								}
								else{
										$r = mysql_fetch_assoc($result);
										$pwd = strval(rand(65,90));
										$pwd .= chr(rand(65,90));
										$pwd .= chr(rand(65,90));
										$pwd .= chr(rand(65,90));
										$pwd .= rand(65,90);
										$pwd .= chr(rand(65,90));
										
										$to = $r['email'];
										$subject = "Roski Loaner Password Reset";
										$message = "Looks like you forgot your password. No worries, we're here to help!\n\nYour temporary password is: $pwd\n After logging in at http://art.usc.edu/loaner/roski/ you can change your password with the \"Change Password\" link located in the upper right hand corner.\n\n Cheers.\n";
										$headers = "From: no_reply@art.usc.edu"."\r\n";
										$headers .="Reply-To: no_reply@art.usc.edu"."\r\n";
										$headers .= "MIME-Version: 1.0" . "\r\n";
										$headers .= "Content-type:text;charset=iso-8859-1" . "\r\n";
										if(mail($to,$subject,$message,$headers)){
												$encryptedPwd = sha1($pwd);
												$updateQuery = "UPDATE users SET password='".$encryptedPwd."' WHERE userid='".$_POST['userid']."'";
												mysql_query($updateQuery);
												echo "<dt style='color:rgb(205,20,20); font-size:12px'>Temporary password sent to $to</dt>";
										}
										else
												echo "<dt style='color:rgb(205,20,20); font-size:12px'>There was an error reseting your password, please try again later.</dt>";
								}
						}
						else{
				?>
								<dt>Enter your username</dt>
								<form action='forgot_password.php' method='post'>
										<input type="text" name="userid" id="userid"/>
										<input type='submit' name="submit" id="submit" value="Submit"/>
								</form>
				<?php
						}
				?>
				</dd>
		</dl>		
</div>

<?php
		require_once("../includes/footerNew.php");
?>