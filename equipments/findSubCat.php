<?php
    require_once("../includes/db.php");
        
		$equipCatID = intval($_GET['equipCatID']);

		$query="select equipSubName, equipSubCatID from equipSubCategory WHERE equipCatID=$equipCatID";
		$result=mysql_query($query);

?>
<div class='pf-description-float'>
		Sub Category:
</div>
<div class='pf-content-float'>
		<select class="pf-field" name="equipSubCatID" id="equipSubCatID">
				<option value="-1">Select Category</option>
				<?php
						while($row=mysql_fetch_array($result)) {
						echo "<option value='".$row['equipSubCatID']."'";
						echo ">".$row['equipSubName']."</option>";
						} 
				?>
		</select>
</div>
<div class='clear'></div>


                                            
