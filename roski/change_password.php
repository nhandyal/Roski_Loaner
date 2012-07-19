<?php
		$page_title = "Change Password";	
		require_once("../includes/session.php");
		require_once("../includes/headerOpen.php"); //opens the head tag
?>

		
				
<?php
		require_once("../includes/headerClose.php"); //closes the head tag and adds all universal header elements
?>	

	<div id="login" class="ui-widget">

		<h3 class="ui-widget-header">Change Password</h3>

			<dl class="ui-content">

				<dd><div class="error"></div></dd>

				<dt>Old Password</dt>

				<dd><input type="password" name="opassword" id="opassword"/></dd>

				<dt>New Password</dt>

				<dd><input type="password" name="password" id="password"/></dd>

				<dt>Confirm New Password</dt>

				<dd><input type="password" name="cpassword" id="cpassword"/></dd>

				<dt></dt>

				<dd>

					<input type="button" name="submit" id="submit" value="Submit"/>

				</dd>

			</dl>

	</div>

<?php

	require_once("../includes/footerNew.php");

?>

<script language="JavaScript" type="text/javascript">

	(function() {

		$("#username").focus();

		

		$("#submit").click(function(){

			if(($.trim($("#password").val()) != $.trim($("#cpassword").val())) || $.trim($("#password").val()).length ==0){

				showError("Password and Confirm Password doesn't match");

				return;

			}

			$.post("functions.php?changepass=1",

				{

					"oldpassword" 	: $.trim($("#opassword").val()),

					"newpassword"		: $.trim($("#password").val()),

					"cnewpassword"		: $.trim($("#cpassword").val())

				},

				function(response){

					if(response == 200){

						showError("Password changed successfully");

					}

					else{

						showError(response);

					}

				}

			);//end of ajax request

			

		}); //signin click handler end

		

	})(); //function end

</script>

