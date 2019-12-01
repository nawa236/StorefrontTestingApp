<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <title>Cart</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

	<?php
		require('header.php');
		include('dbConnect.php');
		$id = $_COOKIE["TriStorefrontUser"];
		$query = "SELECT * FROM orders WHERE custid = $id AND status = 'Incomplete';";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$total_row = $statement->rowCount();

		if ($total_row == 0){
			echo "<p id='emptycart'> There is nothing in your cart </p>";
		} else if ($total_row >= 2) {
			echo "<p id='errorcart'> Error: Multiple incomplete orders detected </p>";
		} else {
			$oID = $result[0]['id'];
			$query = "SELECT * FROM order_products, product WHERE pid=id AND oid=$oID";
			$statement = $connect->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();
			echo "<h1> Cart Contents </h1>";
			echo '<form action="./save.php" id="checkoutform">';
			echo '<input type="hidden" name="oID" value="' . $oID . '" />';
			$subtotal = 0;
			echo '<div id="wrapper" class="filter">';
			foreach($result as $row){
				$price = $row['price'];
				$quantity = $row['quantity'];
				$sku = $row['sku'];
				$name = $row['name'];
				$pid = $row['pid'];
			    echo '<div class="cartcard" id="cartitem' . $pid . '">';
				echo '<p style="float:left; margin: 10px;">'. $name .'	</p>';
				$formattedPrice = number_format ($price,2);
				echo '<p style="float:left; margin: 10px;" id="cartsku' . $pid . '">	SKU: ';
				echo "$sku ";
				echo '</p>';
				echo '<p style="float:right; margin: 10px;" id="carttotal' . $pid . '">	Total: $';
				$total = $price * $quantity;
				echo number_format($total,2). " </p>";
				echo '<p style="float:right; margin: 10px;" id="cartquantity' . $pid . '">	Quantity: <input type="number" id="cart_quantity_' . $pid; 
				echo '" style="width: 60px" name="' . $pid;
				echo '" min="0" onkeypress="return event.charCode >= 48" step="1" value=' . $quantity . ' id="cartquantityinput' . $pid . '> </p>';
				echo '<p style="float:right; margin: 10px;" id="carttotal' . $pid . '">	$' . $formattedPrice . ' </p>';
				echo "</div>";
				$subtotal += ($price * $quantity);
			};
			echo "</div>";
			echo '<p style="text-align: right; margin: 10px;" id="cartsubtotal"> Subtotal: $';
			echo number_format($subtotal,2) . '</p>';
			echo '<p style="text-align: right; margin: 10px;" id="carttax"> Tax (6%): $';
			$tax = $subtotal * .06;
			echo number_format($tax,2) . '</p>';
			echo '<p style="text-align: right; margin: 10px;" id="carttotal"> Total: $';
			$total = $subtotal + $tax;
			echo number_format($total,2);
			echo "</p>";
			echo '<input type="submit" value="Checkout" name="checkout" style="float: right; margin: 10px;" id="cartcheckout">';
			echo '<input type="submit" value="Save" name="save" style="float: right; margin: 10px;" id="cartsave">';
			echo "</form>";
		}
	?>
</body>
</html>
