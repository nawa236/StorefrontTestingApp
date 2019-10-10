<?php

class ProductCard {

function __construct($name,$price,$description,$foodType) {
      $image = "./images/" . str_replace(" ","",$name) . ".jpg"; 
      echo "<div class=\"card\">";
      echo "<img src=$image alt=$name style=\"width:100%\">";
      echo "<h1>$name</h1>";
      $formattedPrice = number_format ($price,2); 
      echo "<p class=\"price\">\$$formattedPrice</p>";
      echo "<p>$description</p>";
      $id = "button" . $name;
      echo "<p><button id=$id>Configure</button></p>";
      echo "</div>";
 }

}
?>
