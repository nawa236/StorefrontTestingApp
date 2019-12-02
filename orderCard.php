<?php

// Builds a simple formatted display of the contents of a past order
class orderCard {

function __construct($name,$sku,$price,$quantity,$pid) {
 echo '<div id="orderitem_' . $pid .'" class="cartcard">';
      echo '<p id="name_' . $pid . '" style="float:left; margin: 10px;"><b>'. $name .'</b></p>';
      $formattedPrice = number_format ($price,2);
      echo '<p id="sku_' . $pid . '" style="float:left; margin: 10px;">	SKU: ';
      echo "$sku ";
      echo '</p>';
      echo '<p id="total_' . $pid . '" style="float:right; margin: 10px;">	Total: $';
      $total = $price * $quantity;
      echo number_format($total,2). " </p>";
      echo '<p id="quantity_' . $pid . '" style="float:right; margin: 10px;">Quantity: ' . $quantity . '</p>';
      echo '<p id="itemcost_' . $pid . '" style="float:right; margin: 10px;">	$' . $formattedPrice . ' </p>';
      echo "</div>";
 }

}
?>
