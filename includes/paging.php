<?php

   function check_integer($which) {
        if(isset($_REQUEST[$which])){
            if (intval($_REQUEST[$which])>0) {
                //check if the paging variable was set or not, 
                //if yes then return its number:
                //for example: ?page=5, then it will return 5 (integer)
                return intval($_REQUEST[$which]);
            } else {
                return false;
            }
        }
        return false;
    }//end of check_integer()

    function get_current_page() {
        if(($var=check_integer('page'))) {
            //return value of 'page', in support to above method
            return $var;
        } else {
            //return 1, if it wasnt set before, page=1
            return 1;
        }
    }//end of method get_current_page()

    function setSearchString(){
				//setup the search variables
	
				$searchString = "";
	
				foreach($_GET as $key => $value){
						if($key != "page"){
							 $searchString = $searchString."&".$key."=".$value;
						}
				 }
	
				return $searchString;
    }
    
    
    function doPages($page_size, $thepage, $query_string, $total) {
	
				$searchString = setSearchString();
	
        //items per page
        $index_limit = $page_size;

        //set the query string to blank, then later attach it with $query_string
        $query='';
        
        if(strlen($query_string)>0){
            $query = "&amp;".$query_string;
        }
        
        //get the current page number example: 3, 4 etc: see above method description
        $current = get_current_page();
        
        $total_pages=ceil($total/$page_size);							// total number of pages based on query
        
				if ($current > 4){
						if ($total_pages > 10 && ($current >= $total_pages-5))
								$start = $total_pages-9;
						else
								$start = $current-4;
								
						$end = $current+5;
				}
				else{
						$start = 1;
						$end = 10;
				}
				
        echo '<div class="paging">';

        if($current==1) {
            echo '<span class="prn">&lt; &lt;</span>&nbsp;';
						echo '<span class="prn">&lt;</span>&nbsp;';
        } else {
            $i = $current-1;
						echo '<a href="'.$thepage.'?page=1'.$query.$searchString.'" class="prn" rel="nofollow" title="go to first page">&lt;&lt;</a>&nbsp;';
            echo '<a href="'.$thepage.'?page='.$i.$query.$searchString.'" class="prn" rel="nofollow" title="go to page '.$i.'">&lt;</a>&nbsp;';
        }

        if($start > 1) {
            echo '<span class="prn">...</span>&nbsp';
        }

        for ($i = $start; $i <= $end && $i <= $total_pages; $i++){
            if($i==$current) {
                echo '<span>'.$i.'</span>&nbsp;';
            } else {
                echo '<a href="'.$thepage.'?page='.$i.$query.$searchString.'" title="go to page '.$i.'">'.$i.'</a>&nbsp;';
            }
        }

        if($total_pages > 10 && !($current >= $total_pages-5)){
            echo '<span class="prn">...</span>&nbsp';
        }

        if($current < $total_pages) {
            $i = $current+1;
            echo '<a href="'.$thepage.'?page='.$i.$query.$searchString.'" class="prn" rel="nofollow" title="go to page '.$i.'">&gt;</a>&nbsp;';
						echo '<a href="'.$thepage.'?page='.$total_pages.$query.$searchString.'" class="prn" rel="nofollow" title="go to last page">&gt;&gt;</a>&nbsp;';
        } else {
            echo '<span class="prn">&gt;</span>&nbsp;';
						echo '<span class="prn">&gt;&gt;</span>&nbsp;';
        }
        
        //if nothing passed to method or zero, then dont print result, else print the total count below:
        if ($total != 0){
            //prints the total result count just below the paging
            echo '<p id="total_count">(total '.$total.' records)</p>';
        }
				
				echo '</div>';
				
    }//end of method doPages()
?>