<?php

// Displays the current product in a formatted fashion, allowing redirection to product page
function makeProductCard($name,$price,$description,$id) {
      	$filteredName =  str_replace(array("#"," "),"",$name);
      	$image = "./images/" . $id . ".jpg";
      	echo "<div id=\"card_$id\" class=\"card\" style=\"border-width:0px;\">";
		echo "<img src=$image style='float:left;width:165px;'>";
		echo "<div class=\"textBox\">";
      	echo "<h1 id='name_$id' class='name'>$name</h1>";
      	$formattedPrice = number_format ($price,2); 
		echo "<p id='description_$id' class='desc'>$description</p>";
		echo "<hr />";
      	echo "<p id='price_$id' class=\"price\">\$$formattedPrice</p></div>";
      	echo '<a href="./productPage.php?productID=';

	//*****  Bug 5 Start ****//
     	$bugCode = bug_check(5);
      	if(is_null($bugCode))
            echo $id;
       	else
            eval($bugCode);
     	 //*****   Bug 5 End  ****//

	echo '" class="button" ';
      	echo "id=product_button_$id>Configure</a>";
      	echo "</div>";
}
?>
