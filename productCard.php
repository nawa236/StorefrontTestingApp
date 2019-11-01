<?php

class ProductCard {

function __construct($name,$price,$description,$id) {
      $filteredName =  str_replace(array("#"," "),"",$name);
      $image = "./images/" . $filteredName . ".jpg"; 
      echo '<div class="card">';
      echo "<img src=$image>";
      echo "<h1>$name</h1>";
      $formattedPrice = number_format ($price,2); 
      echo "<p class=\"price\">\$$formattedPrice</p>";
      echo "<p>$description</p>";
      echo '<p><a href="./productPage.php?productID=';
      echo $id; 
      echo '" class="button" ';
      echo "id=product_button_$id >Configure</a></p>";
    echo "</div>";
 }

}
?>
