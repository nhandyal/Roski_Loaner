		</div><!-- end main -->
	
		<div id="overlay-win"> </div>
		
<!-- end footer -->
		
		
	</div><!-- end page -->
	
	<!-- Import all Javascripts -->
	<script language="JavaScript" type="text/javascript" src="../js/jquery.js"></script> <!-- jQuery Framework -->
	<script language="JavaScript" type="text/javascript" src="../js/jquery_ui.js"></script> <!-- jQuery UI Framework -->
	<script language="JavaScript" type="text/javascript" src="../js/jquery.scrollTo-min.js"></script> <!-- jQuery scrollTo plugin -->
	<script language="JavaScript" type="text/javascript" src="../js/jQuery.tableSort-min.js"></script> <!-- jQuery tableSorter plugin -->
	<script language="JavaScript" type="text/javascript" src="../js/default.js"></script> <!-- Default Javascript -->
	</body>
</html>
<?php
    mysql_close($connectionString);
?>
<script>
(function(){
	
	<?php
		if(isset($_GET['err'])){
	?>
		showError("Invalid ID");
	<?php
		}
	?>
		
		
	$("a").live("click", function(){
		//hide error message when any link is clicked
		$(".error").hide();
	});
	
	$("input[type=button]").live("click", function(){
		//hide error message when any link is clicked
		//$(this).attr("disabled","disabled");
		$(".error").hide();
	});
	
	$("input[type=submit]").live("click", function(){
		//hide error message when any link is clicked
		$(".error").hide();
	});
	
	$("#report_bug_link").click(function(){
			$('#bug_reporter').dialog('open');
	});


	

	$("#search").click(function(){
		if($.trim($("#users_search").val()) != ""){
			return true;
		}
		else{
			return false;
		}
	});
	
	
	$("#list").tablesorter(); //to make list sortable
	
//	$("#list tbody tr:odd").addClass("stripe");

})();

function info(str){
	$("#debug").append(str);
}

function showError(str){
	//show error message	
	$(".error").html(str).show();
	
	$("input[type=button]").removeAttr("disabled");

	//scroll to Top of the page
	//$.scrollTo(".error");
}
</script>