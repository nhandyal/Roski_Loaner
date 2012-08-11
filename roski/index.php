<?php
		$page_title = "USC Roski Loaner";	
		require_once("../includes/headerOpen.php"); //opens the head tag
?>

		
				
<?php
		require_once("../includes/headerClose.php"); //closes the head tag and adds all universal header elements
?>	
	<div id="login" class="ui-widget">
		<h3 class="ui-widget-header">Login</h3>
		<form name="login" action="login.php" method="post">
			<dl class="ui-content">
				<dt>Username</dt>
				<dd><input type="text" name="username" id="username"/></dd>
				<dt>Password</dt>
				<dd><input type="password" name="password" id="password"/></dd>
				<dt></dt>
				<dd>
					<input type="submit" name="signin" id="signin" value="Sign In"/>
					<a href="forgot_password.php">Forgot Password?</a>
				</dd>
			</dl>
		</form>
        <div class="ui-content">
        	<strong>For best performance use Mozilla Firefox.  (Do not use Safari!)</strong>
        </div>
	</div>
<?php
	require_once("../includes/footerNew.php");
?>
<script language="JavaScript" type="text/javascript">
	(function() {
		$("#username").focus();
		
		$("#signin").click(function(){
			
			if ($.trim($("#username").val()) == ""){
				showError("Username is required.");
				return false;
			}
			else if($.trim($("#password").val()) == ""){
				showError("Password is required");
				return false;
			}
			else{
				return true;
			}
		}); //signin click handler end
		
		<?php
			if(isset($_GET['err'])){
		?>
			showError("Invalid username or password.");
		<?php		
			}
		?>
		
	})(); //function end
</script>
