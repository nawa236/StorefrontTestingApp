<?php

class cartCard {

function __construct($name,$sku,$price,$quantity,$pid) {
      echo '<div class="cartcard">';
      echo "<h2 style=\"float:left;\">$name</h2>";
      $formattedPrice = number_format ($price,2);
      echo "<p style=\"float:left;\">SKU: " . $sku"</p>";
      echo '<p class=\"price\" style=\"float:left;\">$' . $formattedPrice . '</p>';
      echo '<p style=\"float:left;\">Quantity: <input type="number" id="cart_quantity_' . $pid; 
      echo '" style="width: 60px" name="quantity" min="0" onkeypress="return event.charCode >= 48" step="1" value=' . $quantity . '></p>';
      echo "<p style=\"float:right;\">>Total: $";
      $total = $price * $quantity;
      echo number_format($total,2)."<p>";
    echo "</div>";
 }

}
?>
