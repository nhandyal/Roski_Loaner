$(document).ready(function(){
		addItem();
		
		// give focus to the userid element
		$("#userid").focus().change(function(){validateUID()});
		
		// add event listeners
		$("#add-item").click(function(){addItem();});
		$("#submit-loan").click(function(){submitLoan();});
		
});

// GLOBAL VALIDATION FUNCTIONS
function validateUID(){
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
}

function validateIID(fieldsetOBJ){
		var type = $(fieldsetOBJ).find(".item-type option:selected").val();
		var itemID = $(fieldsetOBJ).find(".itemid").val();
		if($("#userid").hasClass("readonly") && itemID != ""){
				$(fieldsetOBJ).find('.idWaiting').css({"display" : "inline"});
				$.get("issueLoanFunctions.php?",
						{
								"validateItemID"	:	itemID,
								"userid"					: $("#userid").val(),
								"type"						: type
						},
						function(response){
								var jsonResponse = JSON.parse(response);
								var itemOnPage = false;
								if(jsonResponse.status == 0){
										// check if equipment already exists on page
										for(var i = 0; i < jsonResponse.equipmentIDS.length; i++){
												$('.includedEquipment').each(function(){
														if($(this).val() == jsonResponse.equipmentIDS[i]){
																itemOnPage = true;
														}
												});
										}
										
										if(!itemOnPage){
												// add included equipment to page data div
												for(var i = 0; i < jsonResponse.equipmentIDS.length; i++){
														$("#page-data").append("<input type='hidden' class='includedEquipment' value='"+jsonResponse.equipmentIDS[i]+"' />");
												}
												
												hideError();
												$(fieldsetOBJ).find(".itemid").attr({"readonly":"readonly"}).addClass("readonly");
												$(fieldsetOBJ).find('.item-type').attr({"disabled":"disabled"});
												$(fieldsetOBJ).find('.idWaiting').css({"display" : "none"});
												$(fieldsetOBJ).find('.idResultImg').css({"display" : "inline"});
												$(fieldsetOBJ).find(".loan-length").val(jsonResponse.loan_length);
												$(fieldsetOBJ).find(".hidden-elements").css("display","block");
												$(fieldsetOBJ).append(jsonResponse.htmlData);
												
												// validate loan length
												validateLoanLength($(fieldsetOBJ).find(".hidden-elements"));
										}
										else{
												showError("This item is already staged for checkout.");
												$(fieldsetOBJ).find('.idWaiting').css({"display" : "none"});
										}
								}
								else{
										$(fieldsetOBJ).find('.idWaiting').css({"display" : "none"});
										showError(jsonResponse.message);
								}
						}
				);
		}
		else if(!$("#userid").hasClass("readonly"))
				showError("You must enter a userid before scanning loan items");
}

function validateLoanLength(hiddenElementsOBJ){
		var llength = $(hiddenElementsOBJ).find(".loan-length").val();
		if (llength <= 0 || isNaN(Number(llength))){
				$(hiddenElementsOBJ).find(".valid-loan-length").val("0");
				showError("Loan length must be greater than 0")
		}
		else{
				$(hiddenElementsOBJ).find(".valid-loan-length").val("1");
				hideError();
		}
}

function validateEqID(callingOBJ){
		var fieldsetOBJ = $(callingOBJ).parent().parent().parent();
		$(fieldsetOBJ).find(".equipment-wrapper").each(function(){
				if($(this).attr("id") == $(callingOBJ).val() && !$(this).hasClass("missing-notify")){
						$(this).removeClass("not-scanned").addClass("scanned");
						$(callingOBJ).addClass("readonly").attr({"readonly":"readonly"});
				}
		});
}

function submitLoan(){
		var validItemIDS = true;
		// Check to see if a valid userID has been entered
		if(!$("#userid").hasClass("readonly")){
				showError("You must enter a valid userid.");
				return false;
		}
		
		// make sure all items on page have valid itemID's
		$('.loan-item .itemid').each(function(){
				if(!$(this).hasClass("readonly")){
						showError("All items on page do not have valid item ID's.");
						validItemIDS = false;
				}
		});
		
		if(validItemIDS){
				// make sure all equipment on page has been scanned
				if($(".not-scanned").length != 0){
						showError("All staged equipments has not been scanned.");
						return false;
				}
				
				// prep loan items for submit
				// store item id and type
				loanItemArray = [];
				$(".loan-item").each(function(i){
						var type = $(this).find(".item-type option:selected").val();
						var itemID = $(this).find(".itemid").val();
						var notes = $(this).find(".notes").val();
						var loanType = $(this).find(".loan-type option:selected").val();
						var loanLength = $(this).find(".loan-length").val();
						loanItemArray[i] = ({"itemid":itemID,"type":type,"notes":notes,"loanType":loanType,"loanLength":loanLength});
				});
				//loanItemArray = JSON.stringify(loanItemArray);
				
				
				$.post("submitLoan.php",
						{
								"userid"			:	$('#userid').val(),
								"loanItems"		: loanItemArray
						},
						function(response){
								var jsonResponse = JSON.parse(response);
								if(jsonResponse.status == 0){
										hideError();
										window.location = "http://art.usc.edu/loaner/loans/index.php";
										alert(jsonResponse.message);
								}
								else{
										showError(jsonResponse.message);
								}
						}
				);
				
		}
}

// support validation functions
// find the correct destination objects for the global validation functions
function changeItemID(callingOBJ){
		var fieldsetOBJ = $(callingOBJ).parent().parent().parent();
		validateIID(fieldsetOBJ);
}

function changeItemType(callingOBJ){
		var fieldsetOBJ = $(callingOBJ).parent().parent().parent();
		validateIID(fieldsetOBJ);
}

function changeLoanLength(callingOBJ){
		var hiddenElementsOBJ = $(callingOBJ).parent().parent().parent();
		validateLoanLength(hiddenElementsOBJ);
}

function addItem(){
		var loanItemCount = parseInt($("#loan-item-count").val())+1;
		var newItemHTML = "<fieldset class='loan-item' id='loan-item-"+loanItemCount+"' style='display:none'>"+$(".loan-item-template").html()+"</fieldset>";
		var newItemID = "loan-item-"+loanItemCount;
		$("#loanerform").append(newItemHTML);
		$("#"+newItemID).find(".item-number").html("Item: "+loanItemCount);
		if(loanItemCount != 1)
				$("#"+newItemID).find(".item-legend").append("<div class='remove-item' onclick='removeItem(this)'></div>");
		$("#loan-item-count").val(loanItemCount);
		$("#loan-item-"+loanItemCount).fadeIn(750);
}

function removeItem(callingObj){
		var parent = $(callingObj).parent().parent();
		var cssOBJ = {"overflow":"hidden"};
		$("#loan-item-count").val(parseInt($("#loan-item-count").val())-1);
		$(parent).css(cssOBJ).animate({"height":0,"opacity":0,"margin-bottom":0},750,function(){
				$(parent).remove();
				var i = 1;
				$('.loan-item').each(function(){
						$(this).attr("id","loan-item-"+i);
						$(this).find(".item-number").html("Item: "+i);
						i++;
				});
		});
}