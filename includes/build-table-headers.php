<?php
		if(isset($_GET["sf"])){
				$sf = $_GET["sf"];
				$dir = $_GET["dir"];
		}
		else{
				$sf = $default_sort_field;
				$dir = "ASC";
		}
		
		foreach($headerClasses as $key => $value){
				if($value == $sf){
						if($dir == "DESC"){// sort direction is DESC, display sortDOWN.png
								echo '<td class="'.$value.'">'.$headerTitles[$key];
								echo '<span id="sortArrow"><img width="10px" height="10px" src="../etc/sortDOWN.png"/></span>';
								echo '</td>';
						}
						else if($dir == "ASC"){ // sort direction is ASC, display sortUP.png
								echo '<td class="'.$value.'">'.$headerTitles[$key];
								echo '<span id="sortArrow"><img width="10px" height="10px" src="../etc/sortUP.png"/></span>';
								echo '</td>';
						}
				}
				else{
						//default header labels w/o sort arrow
						echo '<td class="'.$value.'">'.$headerTitles[$key].'</td>';	
				}
		}
?>
