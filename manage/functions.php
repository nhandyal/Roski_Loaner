<?php
		require_once("../includes/session.php");
		require_once("../includes/db.php");
		require_once("../includes/global.php");
		
		if(isset($_GET['loadContainer2'])){
				$query = "";
				if($_GET['tableName'] == "equipCategory")		
						$query = "SELECT * FROM ".$_GET['tableName'];
				else
						$query = "SELECT * FROM ".$_GET['tableName']." WHERE deptID=".$_SESSION['dept'];
				
				$fieldName = $_GET['fieldName'];
				$result = mysql_query($query);
				$header = $_GET['header'];
				$tableName = $_GET['tableName'];
				
				echo "<table id='sub-container-1-table'>";
						while($r = mysql_fetch_assoc($result)){
								echo "<tr>"; // begin table row
								if($tableName == "equipCategory"){
										if($r['equipCatID']!=0){
												$onclick = "loadSubCategories(".$r['equipCatID'].",this)";
												$id = $r['equipCatID'];
												echo "<td class='table-content' id='ec-$id' onclick=".$onclick.">".$r[$fieldName]."</td>";
										}
								}
								else if($tableName == "accessareas"){
										$escapedFieldName = urlencode($r[$fieldName]);
										$id = $r['id'];
										$onclick = "loadAccessAreaUsers('$id','$escapedFieldName',this)";
										echo "<td class='table-content' id='aa-$id' onclick=".$onclick.">".$r[$fieldName]."</td>";
								}
								else
										echo "<td class='table-content'>".$r[$fieldName]."</td>";
								
								if($_GET['tableName'] == "equipCategory"){
										if($r['equipCatID']!=0){
												$deleteHref = 'javascript:del("'.$_GET['tableName'].'","'.$header.'","'.$fieldName.'","'.$r[$fieldName].'")';
												$editHref = 'javascript:edit("'.$_GET['tableName'].'","'.$header.'","'.$fieldName.'","'.$r[$fieldName].'")';
												echo "<td class='table-filler'>";
												echo "<a href='".$editHref."'><img src='../etc/edit.png'></a>&nbsp&nbsp";
												echo "<a href='".$deleteHref."'><img src='../etc/delete.png'></a>";
												echo "</td>";
										}
								}
								else{
										$deleteHref = 'javascript:del("'.$_GET['tableName'].'","'.$header.'","'.$fieldName.'","'.$r[$fieldName].'")';
										$editHref = 'javascript:edit("'.$_GET['tableName'].'","'.$header.'","'.$fieldName.'","'.$r[$fieldName].'")';
										echo "<td class='table-filler'>";
										echo "<a href='".$editHref."'><img src='../etc/edit.png'></a>&nbsp&nbsp";
										echo "<a href='".$deleteHref."'><img src='../etc/delete.png'></a>";
										echo "</td>";
								}
								echo "</tr>";	// end table row
						}
				echo "</table>";
				
				if($_GET['tableName'] == "accessareas"){
						echo "<div class='div-button' onclick='removeUsersFromAllAccessAreas()'>Remove Users From All Access Areas</div>";
				}
				exit(0);
		}
		
		if(isset($_GET['loadAccessAreaUsers'])){
				$header = $_GET['header'];
				$id = $_GET['loadAccessAreaUsers'];
				$query = "SELECT userid FROM users_accessareas WHERE accessid=$id";
				$result = mysql_query($query);
				
				echo "<table id='sub-container-3-table'>";
						while($r = mysql_fetch_assoc($result)){
								$userid = urlencode($r['userid']);
								$href = 'javascript:deleteUserFromAccessArea('.$id.',"'.$userid.'")';
								echo "<tr>";
										echo "<td class='table-content'>".$r['userid']."</td>";
										echo "<td class='table-filler'>";
												echo "<a href='".$href."'><img src='../etc/delete.png'></a>";
										echo "</td>";
								echo "</tr>";
						}
				echo "</table>";
				
				echo "<div class='div-button' onclick='removeUsersFromAccessArea($id)'>Remove All Users From: $header<img id='submitWaiting-aau' src='../etc/loading.gif' width='15' height='15' style='float:right; margin:7px; opacity:0'/></div>";
				echo "<input type='hidden' id='aa-header' value='$header'/>";
				exit(0);
		}
		
		if(isset($_GET['loadSubCategories'])){
				$query = "SELECT * FROM equipSubCategory WHERE equipCatID=".$_GET['equipCatID'];
				$result = mysql_query($query);
				
				echo "<table id='sub-container-3-table'>";
						while($r = mysql_fetch_assoc($result)){
								$id = $r['equipCatID'];
								$equipSubCatName = $r['equipSubName'];
								$editHref = 'javascript:editSubCategory('.$id.',"'.$equipSubCatName.'")';
								$deleteHref = 'javascript:deleteSubCategory('.$id.',"'.$equipSubCatName.'")';
								echo "<tr>";
										echo "<td class='table-content'>".$r['equipSubName']."</td>";
										echo "<td class='table-filler'>";
												echo "<a href='$editHref'><img src='../etc/edit.png'></a>&nbsp&nbsp";
												echo "<a href='$deleteHref'><img src='../etc/delete.png'></a>";
										echo "</td>";
								echo "</tr>";
						}
				echo "</table>";
				exit(0);
		}
		
		if(isset($_GET['addSubCat'])){
				$newSubCat = $_POST['newSubCat'];
				$equipCatId = $_POST['equipCatID'];
				
				$insertQuery = "INSERT INTO equipSubCategory (equipCatID,equipSubName) VALUES ($equipCatId,'$newSubCat')";
				mysql_query($insertQuery);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				$response['status'] = 0;
				echo json_encode($response);
				exit(0);
				
		}
		
		if(isset($_GET['editSubCat'])){
				$newSubCat = $_POST['newSubCat'];
				$oldSubCat = $_POST['oldSubCat'];
				$equipCatId = $_POST['equipCatID'];
				
				$updateQuery = "UPDATE equipSubCategory SET equipSubName='".$newSubCat."' WHERE equipSubName='".$oldSubCat."' AND equipCatID=".$equipCatId;
				mysql_query($updateQuery);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				$response['status'] = 0;
				echo json_encode($response);
				exit(0);
				
		}
		
		if(isset($_GET['deleteSubCat'])){
				$subCat = $_POST['subCat'];
				$equipCatId = $_POST['equipCatID'];
				
				$deleteQuery = "DELETE FROM equipSubCategory WHERE equipSubName='".$subCat."' AND equipCatID=".$equipCatId;
				mysql_query($deleteQuery);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				$response['status'] = 0;
				echo json_encode($response);
				exit(0);
				
		}
		
		if(isset($_POST['add'])){
				$response = "";
				$insertQuery = "";
				$add = $_POST['add'];
				$tableName = $_POST['tableName'];
				$fieldName = $_POST['fieldName'];
				
				if($tableName == "equipCategory")
						$insertQuery = "INSERT INTO equipCategory (equipCatName) VALUES ('".$add."')";
				else
						$insertQuery = "INSERT INTO ".$tableName." (".$fieldName.", deptID) VALUES ('".$add."','".$_SESSION['dept']."')";
						
				mysql_query($insertQuery);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				$response['status'] = 0;
				echo json_encode($response);
				exit(0);
		}
		
		if(isset($_POST['edit'])){
				$response = "";
				$edit = $_POST['edit'];
				$currentValue = $_POST['currentValue'];
				$tableName = $_POST['tableName'];
				$fieldName = $_POST['fieldName'];
				$updateQuery = "";
				
				if($tableName == "equipCategory")
						$updateQuery = "UPDATE ".$tableName." SET ".$fieldName."='".$edit."' WHERE ".$fieldName."='".$currentValue."'";
				else
						$updateQuery = "UPDATE ".$tableName." SET ".$fieldName."='".$edit."' WHERE ".$fieldName."='".$currentValue."' AND deptID=".$_SESSION['dept'];
						
				mysql_query($updateQuery);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				$response['status'] = 0;
				echo json_encode($response);
				exit(0);
		}
		
		if(isset($_POST['delete'])){
				$response = "";
				$delete = $_POST['delete'];
				$tableName = $_POST['tableName'];
				$fieldName = $_POST['fieldName'];
				$deleteQuery = "";
				
				if($tableName == "equipCategory")
						$deleteQuery = "DELETE FROM ".$tableName." WHERE $fieldName='".$delete."'";
				else
						$deleteQuery = "DELETE FROM ".$tableName." WHERE $fieldName='".$delete."' AND deptID=".$_SESSION['dept'];
						
				mysql_query($deleteQuery);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				$response['status'] = 0;
				echo json_encode($response);
				exit(0);
		}
		
		if(isset($_GET['rufa'])){
				$accesid = $_POST['accessid'];				
				
				$deleteQuery = "DELETE FROM users_accessareas WHERE accessid=$accesid";
				
				mysql_query($deleteQuery);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				$response['status'] = 0;
				echo json_encode($response);
				exit(0);
		}
		
		if(isset($_GET['addUsersAA'])){
				$header = $_GET['header'];
				$accesid = $_GET['accessid'];
				$query = "SELECT userid, fname, lname FROM users WHERE userid NOT IN (SELECT userid FROM users_accessareas WHERE accessid=$accesid) AND deptID=".$_SESSION['dept'];
				$result = mysql_query($query);
				
				
				$htmlContent = "<div id='aa-users-div' style='width:350px'>";
				$htmlContent = $htmlContent. "<div style='width:100%; padding:5px 0px 5px 0px; text-align:center; background:rgb(80,80,80); color:white; font-size:16px; font-weight:bold'>Add users to ".$header."</div>";
				$htmlContent = $htmlContent. "<form id='addUsers-form' method='POST' style='margin:15px 30px 15px 30px'>";
				$htmlContent = $htmlContent. "<div style='height:200px; overflow-y:auto'>";
				$htmlContent = $htmlContent. "<table>";
				$htmlContent = $htmlContent. "<tr style='line-height:25px'>";
				$htmlContent = $htmlContent. "<th><input type='checkbox' id='aa-select-all' name='user' value='select-all' style='text-align:center; margin-left:3px'/></th>";
				$htmlContent = $htmlContent. "<th style='text-align:center'>Name</th>";
				$htmlContent = $htmlContent. "<th style='text-align:center'>User ID</th>";
				$htmlContent = $htmlContent. "</tr>";
				while($r = mysql_fetch_assoc($result)){
						$uid = $r['userid'];
						$htmlContent = $htmlContent. "<tr class='aa-tr'>";
						$htmlContent = $htmlContent. "<td><input class='all-users' type='checkbox' name='user' value='$uid'/></td>";
						$htmlContent = $htmlContent. "<td class='aa-name centered'>".$r['fname']." ".$r['lname']."</td>";
						$htmlContent = $htmlContent. "<td class='centered'>$uid</td>";
						$htmlContent = $htmlContent. "</tr>";
				}
				$htmlContent = $htmlContent. "</table>";
				$htmlContent = $htmlContent. "</div>";
				$htmlContent = $htmlContent. "<div style='width:100px; margin:auto; margin-top:15px;'><input type='button' value='Add Users' onclick='aaSubmitUsers()'/>&nbsp&nbsp<img id='submitWaiting' src='../etc/loading.gif' width='15' height='15' style='display: none'/>";
				$htmlContent = $htmlContent. "<input type='hidden' id='aa-accessid' value='$accesid'/><input type='hidden' id='aa-header' value='$header'/></div>";
				$htmlContent = $htmlContent. "</form>";
				$htmlContent = $htmlContent. "</div>";
				
				echo $htmlContent;
				exit(0);
		}
		
		if(isset($_GET['aaAddBatchUsers'])){
				$accesid = $_POST['accessid'];
				$users = $_POST['users'];
				
				$users = explode(',',$users);
				
				foreach($users as $user){
						$insertQuery = "INSERT INTO users_accessareas (userid, accessid) VALUES ('$user',$accesid)";
						mysql_query($insertQuery);
						if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				}
				
				$response["status"] = 0;
				echo json_encode($response);
				exit(0);
		}
		
		if(isset($_GET['deleteUserAA'])){
				$userid = $_POST['userid'];
				$accesid = $_POST['accessid'];
				
				$deleteQuery = "DELETE FROM users_accessareas WHERE userid='$userid' AND accessid=".$accesid;
				mysql_query($deleteQuery);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				$response["status"] = 0;
				echo json_encode($response);
				exit(0);
		}
		
		if(isset($_GET['removeUsersFromAllAccessAreas'])){
				$query = "SELECT id FROM accessareas WHERE deptID=".$_SESSION['dept'];
				$result = mysql_query($query);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				
				
				$r = mysql_fetch_assoc($result);
				$accessids = "( accessid=".$r['id'];
				while($r = mysql_fetch_assoc($result)){
						$accessids = $accessids." OR accessid=".$r['id'];
				}
				$accessids = $accessids." )";
				
				$deleteQuery = "DELETE FROM users_accessareas WHERE ".$accessids;
				mysql_query($deleteQuery);
				if(mysql_errno() != 0){sqlError(mysql_errno(),mysql_error());}
				
				$response["status"] = 0;
				echo json_encode($response);
				exit(0);
		}
?>