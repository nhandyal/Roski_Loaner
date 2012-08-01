$(document).ready(function() {
		
		setSearchField();
		
		document.getElementById("contentTableHeader").addEventListener("click", function(e) {
				var target =  e.target.className;
				buildURL({
						pageURL: "http://art.usc.edu/loaner/loans/index.php",
						sortField: target,
						defaultSort: "issue_date",
						setSearchField: false
				});
		});

});

function details(loanID, view){
		var targetURL = "lightboxPages/indexDetails.php?lid="+loanID+"&view="+view;
		$.fancybox.showActivity();
		$.get(targetURL,function(response){
				$.fancybox({
								'autoDimensions'	: false,
								'centerOnScroll'	: true, 
								'width'						: 1100,
								'height'					: 618,
								'content'					: response,
								'onComplete'			: function(){fancyBoxResize()}
						});		
				}
		);
}

function returnLoan(loanID){
		var targetURL = "lightboxPages/indexReturn.php?lid="+loanID;
		$.fancybox.showActivity();
		$.get(targetURL,function(response){
				$.fancybox({
						'autoDimensions'	: false,
						'centerOnScroll'	: true, 
						'width'			: 1100,
						'height'		: 618,
						'content'		: response,
						'onComplete'		: function(){fancyBoxResize()}
				});
				
				// Add event listeners
				$(".missing-item").click(function(){
						missingItem(this);
				});
				$('.broken-item').click(function(){
						brokenItem(this);		
				});
		});
}

function renew(loanID, view){
		if(confirm("Are you sure you want to renew loan "+loanID+"?")){
				$.get("renewFunctions.php",{
						"loanID"	: loanID,
						"view"		: view
				},
				function(response){
						var jsonResponse = JSON.parse(response);
						if(jsonResponse.status == 0){
								alert(jsonResponse.message);
								window.location.reload();
						}
						else{
								$.fancybox.close();
								showError(jsonResponse.message);
						}
				}
				);
		}
}

function validateEqID(obj){ // function to see if scanned item is displayed in the item list
		var unScannedItems = document.getElementsByClassName("not-scanned");
		for (var i = 0; i < unScannedItems.length; i++){
				if(obj.value == unScannedItems[i].id){
						var itemID = '#'+unScannedItems[i].id;
						$(itemID).removeClass('not-scanned').addClass('scanned');;
						$('#'+obj.id).attr({"readonly":"readonly"}).addClass("readonly");
						break;
				}
		}
}

function validateReturnSubmit(){
		if( (document.getElementsByClassName("not-scanned")).length == 0 ){
				returnSubmit();
		}
		else{
				returnSubmit();
				//alert("Please verify all items have been scanned");
		}
}

function returnSubmit(){
		var confirmReturn = true;
		
		// return loan preprocessing
		var notes = "";
		
		if($('#notes').val() == ""){
				notes = "+";
		}
		else{
				notes = $('#notes').val();
		}
		
		// generate a comma seperated list of broken and missing equipment
		 var brokenEQ = $('.broken').map(function() {
												return $(this).attr("id");
										}).get().join(',');
		 
		 var missingEQ = $('.missing').map(function() {
												return $(this).attr("id");
										}).get().join(',');
		 
		 // set the broken and missing variables to N/A if there are no broken or missing equipment
		 if (brokenEQ == ""){
				brokenEQ = "N/A";
		 }
		 
		 if (missingEQ == ""){
				missingEQ = "N/A";
		 }
		 
		 // notify the user if they are submitting a loan with broken / missing equipment
		 if (brokenEQ != "N/A" || missingEQ != "N/A")
				confirmReturn = confirm("You have indicated that there are broken or missing items. Are you sure this is correct?");
		 
		if (confirmReturn)
				console.log("returning loan");
		
		
		if(confirmReturn){
				$('#submitWaiting').css({"display" : "inline"});
				$.post('returnLoanFunctions.php?return=true',
								{    
										"itemid"		  : $("#itemid").val(),
										"userid"    	: $("#userid").val(),
										"notes"     	: notes,
										"type"				: $('#item-type').val(),
										"brokenEQ"		: brokenEQ,
										"missingEQ"		: missingEQ
								},
								function(response){
										var jsonResponse = JSON.parse(response);
										if(jsonResponse.status == 0){
												$('#submitWaiting').css({"display" : "none"});
												alert(jsonResponse.message);
												window.location = "http://art.usc.edu/loaner/loans";
										}
										else{
												$('#submitWaiting').css({"display" : "none"});
												alert(jsonResponse.message);
										}
								}
				);//end of Ajax Post Request
		}
}

function  missingItem(callingObj){
		var equipmentWrapper = $(callingObj).parent().parent();
		if($(equipmentWrapper).hasClass("missing")){
				$(equipmentWrapper).removeClass("missing").addClass("not-scanned");
		}
		else{
				$(equipmentWrapper).removeClass("not-scanned scanned broken").addClass("missing");
		}
}

function brokenItem(callingObj){
		var equipmentWrapper = $(callingObj).parent().parent();
		if($(equipmentWrapper).hasClass("broken")){
				$(equipmentWrapper).removeClass("broken").addClass("not-scanned");
		}
		else{
				$(equipmentWrapper).removeClass("not-scanned missing").addClass("broken");
		}
}