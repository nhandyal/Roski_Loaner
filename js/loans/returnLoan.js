var uidset = false;
var itemidset = false;

$(document).ready(function(){

		$("#userid").focus();
		
		$('#userid').change(function(){
				//validate user id
				var userid = $("#userid").val();
				if(userid != ""){
						$('#uidWaiting').css({"display" : "inline"});
						$.get("returnLoanFunctions.php?",
								{
										"validateUID"	:	userid
								},
								function(response){
										var jsonResponse = JSON.parse(response);
										if(jsonResponse.status == 0){
												uidset = true;
												$('#userid').attr({"readonly":"readonly"}).addClass("readonly");
												$('#uidWaiting').css({"display" : "none"});
												$('#uidResultImg').css({"display" : "inline"});
												hideError();
										}
										else{
												$('#uidWaiting').css({"display" : "none"});
												showError(jsonResponse.message);
										}
								}
						);
				}
		});
		
		$('#itemid').change(function(){
				validateItemID();
		});
		
		$('#submit').click(function(){
				submitLoan();
		});
		
		$('#reset').click(function(){
				window.location.reload();
		});
});

function validateItemID(){
		if(uidset == true){
				$('#idWaiting').css({"display" : "inline"});
				$.get("returnLoanFunctions.php?",
						{
								"validateItemID"	:	$("#itemid").val(),
								"userid"					: $("#userid").val()
						},
						function(response){
								var jsonResponse = JSON.parse(response);
								if(jsonResponse.status == 0){
										hideError();
										itemidset = true;
										$('#itemid').attr({"readonly":"readonly"}).addClass("readonly");
										$('#idWaiting').css({"display" : "none"});
										$('#idResultImg').css({"display" : "inline"});
										$("#loan-details-container").html(jsonResponse.loanInformation);
										$("#equipment-checkout-wrapper").html(jsonResponse.listedEquipment);
										
										// Add event listeners for missing and broken items
										$(".missing-item").click(function(){
												missingItem(this);
										});
										$('.broken-item').click(function(){
												brokenItem(this);		
										});
								}
								else{
										$('#idWaiting').css({"display" : "none"});
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

function submitLoan(){
		
		var notes = "";
		
		if(!uidset || !itemidset){
				showError("Make sure all required fields are filled.");
				return false;
		}
		
		if( (document.getElementsByClassName("not-scanned")).length != 0){
				showError("Make sure all items have been scanned.");
				return false;
		}
		
		if($('#notes').val() == ""){
				notes = "None";
		}
		else{
				notes = $('#notes').val();
		}
		
		
		//make an ajax post request.
		$('#submitWaiting').css({"display" : "inline"});
		$.post("returnLoanFunctions.php?return=1",
						{    
								"itemid"     	: $("#itemid").val(),
								"userid"    	: $("#userid").val(),
								"notes"     	: notes,
								"type"				:	$('#item-type').val()
						},
						function(response){
								var jsonResponse = JSON.parse(response);
								if(jsonResponse.status == 0){
										$('#submitWaiting').css({"display" : "none"});
										alert(jsonResponse.message);
										window.location = "http://art.usc.edu/loaner/loans";
								}
								else{
										showError(jsonResponse.message);
										$('#submitWaiting').css({"display" : "none"});
								}
						}
		);//end of Ajax Post Request
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