<?php

class cartCard {

function __construct($name,$sku,$price,$quantity,$pid) {
      echo '<div class="cartcard">';
      echo "<h1>$name</h1>";
      $formattedPrice = number_format ($price,2);
      echo "SKU: " . $sku;
      echo '<p class=\"price\">$' . $formattedPrice . '</p>';
      echo 'Quantity: <input type="number" id="cart_quantity_' . $pid; 
      echo '" style="width: 60px" name="quantity" min="0" onkeypress="return event.charCode >= 48" step="1" value=' . $quantity . '>';
      echo "<p>Total: $";
      $total = $price * $quantity;
      echo number_format($total,2)."<p>";
    echo "</div>";
 }

}
?>
