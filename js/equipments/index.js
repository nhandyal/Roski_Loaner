$(document).ready(function() {
		
		setSearchField();
		
		document.getElementById("contentTableHeader").addEventListener("click", function(e) {
				var target =  e.target.className;
				buildURL({
						pageURL: "http://art.usc.edu/loaner/equipments/index.php",
						sortField: target,
						defaultSort: "equipmentid"
				});
		});
		
		$('#category-select').change(function(){
				$('#search').submit();
		});
});

function showDetailsLightbox(id){
		var targetURL = "lightboxPages/indexDetails.php?id="+id;
		$.fancybox.showActivity();
		$.get(targetURL,function(response){
				$.fancybox({
								'autoDimensions'	: false,
								'width'						: 1100,
								'height'					: 618,
								'content'					: response
						});
				}
		);
}

function deactivate(id){
		if(confirm("Are you sure you want to deactivate equipment "+id+"?")){
				$.get("functions.php",
						{
								"deactivate":id
						},
						function(response){
								var jsonResponse = JSON.parse(response);
								if(jsonResponse.status == 0){
										alert(jsonResponse.message);
										window.location.reload();
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