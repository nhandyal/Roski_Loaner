var uidset = false;
var itemidset = false;
var validLoanLength = false;

$(document).ready(function(){
		// give focus to the userid element
		$("#userid").focus();
		
		$("#add-item").click(function(){addItem()});
});

function addItem(){
		var loanItemCount = parseInt($("#loan-item-count").val())+1;
		var newItemHTML = "<br/><fieldset class='loan-item' id='loan-item-"+loanItemCount+"' style='display:none'>"+$(".loan-item-template").html()+"</fieldset>";
		var newItemID = "loan-item-"+loanItemCount;
		$("#loanerform").append(newItemHTML);
		$("#"+newItemID).find(".item-number").html("Item: "+loanItemCount);
		$("#"+newItemID).find(".item-legend").append("<div class='remove-item'></div>");
		$("#loan-item-count").val(loanItemCount);
		$("#loan-item-"+loanItemCount).fadeIn(750);
		$(".remove-item").click(function(){removeItem(this)});
}

function removeItem(callingObj){
		var parent = $(callingObj).parent().parent();
		var cssOBJ = {"overflow":"hidden"};
		$(parent).css(cssOBJ).animate({"height":0,"opacity":0},200,function(){
				$(parent).remove();
				$("#loan-item-count").val(parseInt($("#loan-item-count").val())-1);
				var i = 1;
				$('.loan-item').each(function(){
						$(this).find(".item-number").html("Item: "+i);
						i++;
				});
		});
		//$(parent).fadeOut(500,function(){
		//		
		//});
}