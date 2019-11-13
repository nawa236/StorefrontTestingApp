<?php

class cartCard {

function __construct($name,$price,$quantity) {
      $filteredName =  str_replace(array("#"," "),"",$name);
      echo '<div class="card">';
      echo "<h1>$name</h1>";
      $formattedPrice = number_format ($price,2); 
      echo "<p class=\"price\">\$$formattedPrice</p>";
	  echo "<input type="number" name="quantity" step="1" value=$quantity>";
      echo "<p>Total: ";
	  $total = $price * $quantity;
	  echo "$total<p>";
    echo "</div>";
 }

}
?>
