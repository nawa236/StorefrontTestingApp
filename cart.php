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
		include('cartCard.php');
		$id = $_COOKIE["TriStorefrontUser"];
		$query = "SELECT * FROM orders WHERE custid = $id AND status = 'Incomplete';"; /*need to confirm status possibilites*/
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$total_row = $statement->rowCount();

		if ($total_row == 0){
			echo "<p> There is nothing in your cart </p>";
		} else if ($total_row >= 2) {
			echo "<p> Error: Multiple incomplete orders detected </p>";
		} else {
			$oID = $result[0]['id'];
			$query = "SELECT * FROM order_products, product WHERE pid=id AND oid=$oID";
			$statement = $connect->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();
			echo "<h1> Cart Contents </h1>";
			echo '<form action="./save.php">';
			echo '<input type="hidden" name="oID" value="' . $oID . '" />';
			$subtotal = 0;
			echo '<div id="wrapper" class="filter">';
			foreach($result as $row) {
			    echo '<div class="cartcard">';
				echo '<p style="float:left; margin: 10px;">'. $row['name'] .'	</p>';
				$formattedPrice = number_format ($row['price'],2);
				echo '<p style="float:left; margin: 10px;">	SKU: ';
				echo "$row['sku'] ";
				echo '</p>';
				echo '<p style="float:right; margin: 10px;">	Total: $';
				$total = $row['price'] * $row['quantity'];
				echo number_format($total,2). " </p>";
				echo '<p style="float:right; margin: 10px;">	Quantity: <input type="number" id="cart_quantity_' . $row['pid']; 
				echo '" style="width: 60px" name="' . $row['pid'];
				echo '" min="0" onkeypress="return event.charCode >= 48" step="1" value=' . $row['quantity'] . '> </p>';
				echo '<p style="float:right; margin: 10px;">	$' . $formattedPrice . ' </p>';
				echo "</div>";
				$subtotal += ($row['price'] * $row['quantity'];
			}
			echo "</div>";
			echo '<p style="text-align: right; margin: 10px;"> Subtotal: $';
			echo number_format($subtotal,2);
			echo '<p style="text-align: right; margin: 10px;"> Tax (6%): $';
			$tax = $subtotal * .06;
			echo number_format($tax,2);
			echo '<p style="text-align: right; margin: 10px;"> Total: $';
			$total = $subtotal + $tax;
			echo number_format($total,2);
			echo "</p>";
			echo '<input type="submit" value="Checkout" name="checkout" style="float: right; margin: 10px;">';
			echo '<input type="submit" value="Save" name="save" style="float: right; margin: 10px;">';
			echo "</form>";
		}
	?>
</body>
</html>
