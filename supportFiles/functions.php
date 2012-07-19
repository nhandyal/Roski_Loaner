<?php
	require_once("../includes/session.php");
	require_once("../includes/db.php");
	
	
	//Delete Accessarea
	if(isset($_GET['delete'])){
		$id = addslashes(trim($_GET['delete']));
		
			$query = "delete from accessareas where id='$id'";	//delete the account
			mysql_query($query);
			
			if(mysql_affected_rows()== 1){
				echo "200";
			}
			else{
				echo "Error deleting the Access Area";
			}
		
	}
	
	
	
	
	
	
	//save the accessarea
    if(isset($_GET['create']) || isset($_GET['edit'])){	
        //get all values            
        $accessarea = addslashes(htmlentities(trim($_POST['accessarea'])));
		$deptID = addslashes(htmlentities(trim($_POST['deptID'])));
       
					if($accessarea == ""){
						echo "Access Area cannot be blank"; //Access Area is blank
					}
					else{
							
							if(isset($_GET['create'])){
								$query = "insert into accessareas (accessarea, deptID) values ('$accessarea', '$deptID')";
							} 
							else{
								$id = htmlentities(addslashes(trim($_GET['edit'])));

								$query = "update accessareas set
											accessarea  = '$accessarea',
											deptID	=	'$deptID'
											where id = '$id'
										";				
							}
								mysql_query($query);
								if(mysql_errno() == 0){ //successfully created
									echo "200";
								}
								else{                   //error occured
									echo "Error occured";
								}
						}
					
		
    } //END OF CREATE / EDIT ACCESSAREA
?>