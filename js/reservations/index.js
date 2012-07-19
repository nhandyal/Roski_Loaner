$(document).ready(function() {
		
		setSearchField();
		
		document.getElementById("contentTableHeader").addEventListener("click", function(e) {
				var target =  e.target.className;
				buildURL({
						pageURL: "http://art.usc.edu/loaner/reservations/index.php",
						sortField: target,
						defaultSort: "issue_date"
				});
		});
});

function cancel(loanID){
		if(confirm("This will cancel reservation #"+loanID+". Are you sure you want to continue?")){
				$.get("issueReservationFunctions.php",{
								"cancel"	: loanID
						},
						function(response){
								var jsonResponse = JSON.parse(response);
								if(jsonResponse.status == 0){
										hideError();
										alert(jsonResponse.message);
										window.location = "http://art.usc.edu/loaner/reservations/index.php";
								}
								else{
										showError(jsonResponse.message);
								}
						}
				);
		}
		else{
				return false;
		}
}

function validateCheckout(loanID){
		$.get("issueReservationFunctions.php",{
						"validateCheckoutTime" : loanID
				},
				function(response){
						var jsonResponse = JSON.parse(response);
						if(jsonResponse.status == 0){
								$('#lightBoxData').html("");
								var dueDate_Input = "<input type='hidden' id='res_due_date' value='"+jsonResponse.due_date+"' />";
								$('#lightBoxData').html(dueDate_Input);
								showCheckoutLightbox(loanID);
								
						}
						else{
								$.fancybox.close();
								showError(jsonResponse.message);
						}
				}
		);
}

function showDetailsLightbox(loanID,view){
		var targetURL = "lightboxPages/indexDetails.php?lid="+loanID+"&view="+view;
		$.fancybox.showActivity();
		$.get(targetURL,function(response){
				$.fancybox({
								'autoDimensions'	: false,
								'width'						: 1100,
								'height'					: 618,
								'content'					: response,
								'onComplete'			: function(){fancyBoxResize()}
						});
				}
		);
}

function showCheckoutLightbox(loanID){
		var targetURL = "lightboxPages/checkoutReservation.php?lid="+loanID;
		$.fancybox.showActivity();
		$.get(targetURL,function(response){
				$.fancybox({
								'autoDimensions'	: false,
								'width'						: 1100,
								'height'					: 618,
								'centerOnScroll'	: true,
								'content'					: response,
								'onComplete'			: function(){fancyBoxResize()}
						});
				}
		);
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

function validateSubmit(){
		if( (document.getElementsByClassName("not-scanned")).length == 0){
				submitReservation();
		}
		else{
				alert("Please verify all form elements are correct and try again.");
		}
}

function submitReservation(){
		var notes = "";
		
		if($('#notes').val() == ""){
				notes = "None";
		}
		else{
				notes = $('#notes').val();
		}
		
		$('#submitWaiting').css({"display" : "inline"});
		$.get("issueReservationFunctions.php",{
				'checkoutReservation'	: $('#lid').val(),
				'notes'								: $('#notes').val(),
				'itemID'							: $('#itemID').val(),
				'type'								: $('#type').val(),
				'due_date'						: $('#res_due_date').val()
		},
		function(response){
				var jsonResponse = JSON.parse(response);
				if(jsonResponse.status == 0){
						$('#submitWaiting').css({"display" : "none"});
						alert(jsonResponse.message);
						window.location = "http://art.usc.edu/loaner/reservations/index.php";
				}
				else{
						$('#submitWaiting').css({"display" : "none"});
						alert(jsonResponse.message);
				}
		}
		);
}