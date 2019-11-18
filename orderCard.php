<?php

class orderCard {

function __construct($name,$sku,$price,$quantity,$pid) {
 echo '<div class="cartcard">';
      echo '<p style="float:left; margin: 10px;"><b>'. $name .'</b></p>';
      $formattedPrice = number_format ($price,2);
      echo '<p style="float:left; margin: 10px;">	SKU: ';
      echo "$sku ";
      echo '</p>';
      echo '<p style="float:right; margin: 10px;">	Total: $';
      $total = $price * $quantity;
      echo number_format($total,2). " </p>";
      echo '<p style="float:right; margin: 10px;">Quantity: ' . $quantity . '</p>';
      echo '<p style="float:right; margin: 10px;">	$' . $formattedPrice . ' </p>';
      echo "</div>";
 }

}
?>
