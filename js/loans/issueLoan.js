var uidset = false;
var itemidset = false;
var validLoanLength = false;

$(document).ready(function(){

		$("#userid").focus();
		
		$('#userid').change(function(){
				//validate user id
				var userid = $("#userid").val();
				if(userid != ""){
						$('#uidWaiting').css({"display" : "inline"});
						$.get("issueLoanFunctions.php?",
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
		
		$('#item-type').change(function(){
				validateItemID();
		});
		
		$('#loan-type').change(function(){
				if($('#loan-type option:selected').val() == 7)
						$('#loan_length').val(365);
				else
						$('#loan_length').val($('#default-loan-length').val());
		});
		
		$('#loan_length').change(function(){
				validateLoanLength();
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
				$.get("issueLoanFunctions.php?",
						{
								"validateItemID"	:	$("#itemid").val(),
								"userid"					: $("#userid").val(),
								"type"						: $("#item-type option:selected").val()
						},
						function(response){
								var jsonResponse = JSON.parse(response);
								if(jsonResponse.status == 0){
										hideError();
										itemidset = true;
										displayEquipment();
										$('#itemid').attr({"readonly":"readonly"}).addClass("readonly");
										$('#item-type').attr({"disabled":"disabled"});
										$('#idWaiting').css({"display" : "none"});
										$('#idResultImg').css({"display" : "inline"});
								}
								else{
										$('#idWaiting').css({"display" : "none"});
										showError(jsonResponse.message);
								}
						}
				);
		}
}

function displayEquipment(){
		$.get("issueLoanFunctions.php?",
				{
						"getEQ" : $("#itemid").val(),
						"type"	:	$("#item-type option:selected").val()
				},
				function(response){
						var jsonResponse = JSON.parse(response);
						if(jsonResponse.status == 0){
								$("#equipment-checkout-wrapper").html(jsonResponse.message);
								if($('#loan-type option:selected').val() == 2)
										$("#loan_length").val(jsonResponse.loan_length);
								$('#default-loan-length').val(jsonResponse.loan_length);
								validateLoanLength();
								hideError();
								
								// Add event listeners
								$(".missing-item").click(function(){
										missingItem(this);
								});
								$('.broken-item').click(function(){
										brokenItem(this);		
								});
						}
						else{
								showError(jsonResponse.message);
						}
				}
		);
}

function validateLoanLength(){
		var llength = $("#loan_length").val();
		if (llength <= 0 || isNaN(Number(llength))){
				validLoanLength = false;
		}
		else{
				validLoanLength = true;
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
		
		validateLoanLength();
		if(!validLoanLength){
				showError('Loan length must be greater than 0');
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
		$.post("issueLoanFunctions.php?issue=1",
						{    
								"itemid"     	: $("#itemid").val(),
								"userid"    	: $("#userid").val(),
								"notes"     	: notes,
								"loan_length"	: $("#loan_length").val(),
								"type"				:	$("#item-type option:selected").val(),
								"loan_type"		: $("#loan-type option:selected").val()
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