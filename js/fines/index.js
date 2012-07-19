var xhr;
$(document).ready(function(){
		xhr = getXmlHttpRequestObject();
		liveSearchUpdateUsers("");
		
		
		$('#liveSearch').keyup(function(){
				if($('#liveSearch').val() != $('#liveSearch-previous').val()){
						$('#liveSearch-previous').val($('#liveSearch').val());
						liveSearchUpdateUsers($('#liveSearch').val());
				}
		});
		
});

function liveSearchUpdateUsers(search){
		if(xhr.readyState == 0 || xhr.readyState == 4){
				var url = "liveSearch.php?search="+search;
				xhr.open('GET',url,true);
				xhr.onreadystatechange = function() {
						if(xhr.readyState == 4){
								$('#user-selection-table-container').html(xhr.responseText);
						}
				}
				xhr.send();
		}
}

function getXmlHttpRequestObject() {
		if (window.XMLHttpRequest) {
				return new XMLHttpRequest();
		} else if(window.ActiveXObject) {
				return new ActiveXObject("Microsoft.XMLHTTP");
		} else {
				alert("Unable to process request");
		}
}

function loadUserFines(userNum){
		$('.user-selected').removeClass('user-selected');
		$('#user-'+userNum).addClass('user-selected');
		$.get("loadUserFines.php",{"userNum":userNum},function(response){
				$('#user-fines-container').html(response);
				var outstandingBalance = parseInt($('#outstandingBalance').val());
				if(outstandingBalance == 0){
						$('#user-fines-container').html("<div id='user-fines-placeholder'>This user has no outstanding fines.</div>");
				}
		});
}

function payUserFine(userNum, userid, name){
		var htmlContent = "<div style='width:400px; min-height:100px'>";
		htmlContent += "<div style='width:100%; padding:5px 0px 5px 0px; text-align:center; background:rgb(80,80,80); color:white; font-size:16px; font-weight:bold'>Pay Fine For: "+name+"</div>";
		htmlContent += "<form id='payFine-form' method='POST' style='margin-top:15px'>";
		htmlContent += "<div><span style='width:38% ;float:left'>Payment Amount ($):</span>";
		htmlContent += "<input type='text' id='payFine-fancybox'/><div class='clear'></div></div>";
		htmlContent += "<div style='width:150px; margin:auto; margin-top:7px'><input type='submit' value='Pay' style='width:95px'/>&nbsp&nbsp<img id='submitWaiting' src='../etc/loading.gif' width='15' height='15' style='display: none'/>";
		htmlContent += "<input type='hidden' id='payFine-userNum' value='"+userNum+"'/>";
		htmlContent += "<input type='hidden' id='payFine-userid' value='"+userid+"'/>";
		htmlContent += "</form>";
		htmlContent += "</div>";
		$.fancybox({'content': htmlContent});
		$('#payFine-form').submit(function(event){
				submitUserFine(event);
		});
}

function submitUserFine(formEvent){
		hideError();
		$('#submitWaiting').css({"display" : "inline"});
		var userid = $('#payFine-userid').val();
		var userNum = $('#payFine-userNum').val();
		var paymentAmount = parseInt($('#payFine-fancybox').val());
		var outstandingBalance = parseInt($('#outstandingBalance').val());
		if(paymentAmount > outstandingBalance){
				$.fancybox.close();
				showError("User payment cannot exceed outstanding balance");
				paymentAmount = 0;
		}
		else if(paymentAmount <= 0 || isNaN(paymentAmount)){
				$.fancybox.close();
				showError("Invalid input");
		}
		else{
				$.get("payFine.php",{
								"userid"					: userid,
								"paymentAmount"		: paymentAmount
						},function(response){
								var jsonResponse = JSON.parse(response);
								if(jsonResponse.status == 0){
										$('#submitWaiting').css({"display" : "none"});
										$.fancybox.close();
										loadUserFines(userNum);
								}
								else{
										$('#submitWaiting').css({"display" : "none"});
										$.fancybox.close();
										showError(jsonResponse.message);
								}
						}
				);
		}
		formEvent.preventDefault();
}