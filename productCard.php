<?php

// Displays the current product in a formatted fashion, allowing redirection to product page
function makeProductCard($name,$price,$description,$id) {
      	$filteredName =  str_replace(array("#"," "),"",$name);
      	$image = "./images/" . $id . ".jpg";
      	echo "<div id=\"card_$id\" class=\"card\">";
      	echo "<img src=$image>";
      	echo "<h1 id='name_$id' class='name'>$name</h1>";
      	$formattedPrice = number_format ($price,2); 
      	echo "<p id='price_$id' class=\"price\">\$$formattedPrice</p>";
      	echo "<p id='description_$id' class='desc'>$description</p>";
      	echo '<a href="./productPage.php?productID=';

	//*****  Bug 5 Start ****//
     	$bugCode = bug_check(5);
      	if(is_null($bugCode))
            echo $id;
       	else
            eval($bugCode);
     	 //*****   Bug 5 End  ****//

	echo '" class="button" ';
      	echo "id=product_button_$id >Configure</a>";
      	echo "</div>";
}
?>
