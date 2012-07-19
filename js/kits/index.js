// Author: Nikhil Handyal
// Date Created: 03/15/2012
// Dept: USC ROSKI SCHOOL OF FINE ARTS
// PROJECT: Loaner
// Description: Javascript functios required for kits/index page
//
// -------------------------------------------- KITS/INDEX.JS ----------------------------------------- //

$(document).ready(function() {
		
		setSearchField();
		
		document.getElementById("contentTableHeader").addEventListener("click", function(e) {
				var target =  e.target.className;
				buildURL({
						pageURL: "http://art.usc.edu/loaner/kits/index.php",
						sortField: target,
						defaultSort: "kitid"
				});
		});
});

function showDetailsLightbox(id){
		var targetURL = "lightboxPages/indexDetails.php?id="+id;
		$.fancybox.showActivity();
		$.get(targetURL,function(response){
				$.fancybox({
								'autoDimensions'	: false,
								'width'						: 850,
								'height'					: 500,
								'content'					: response
						});
				}
		);
}

function deactivate(id){
		if(confirm("Are you sure you want to deactivate kit "+id+"?")){
				$.get("functions.php",
						{
								"deactivate":id
						},
						function(response){
								var jsonResponse = JSON.parse(response);
								if(jsonResponse.status == 0){
										alert(jsonResponse.message);
										window.location = "http://art.usc.edu/loaner/kits";
								}
								else
										$.fancybox.close();
										showError(jsonResponse.message);
						}
				);
		}
		else{
				return false;
		}
}
