<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <title>Order Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php
	require('header.php');
	include('dbConnect.php');
	include('orderCard.php');
	$id = $_COOKIE["TriStorefrontUser"];
	$order = $_GET["order"];

	// Retrieve all informaton for the order specifed in get parameter, if it belongs to current customer
 	$query = "SELECT * FROM orders WHERE custid = $id AND id = $order;";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$total_row = $statement->rowCount();
	if ($total_row == 0){
		echo "<p> No order with this id associated with your account </p>";
	} else if ($total_row >= 2) {
		echo "<p> Error: Multiple orders with this id </p>";
	} else {
		//*****  Bug 7 Start ****//
		$bugCode = bug_check(7);
		if(!is_null($bugCode)) 
		    eval($bugCode);
		//*****  Bug 7 End  ****//

		// Get all individual product data for the previously validated order
		$query = "SELECT * FROM order_products, product WHERE pid=id AND oid=$order";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		echo "<h1> Order $order Contents </h1>";
		$subtotal = 0;
		echo '<div id="wrapper" class="filter">';

		// Print each item purchased as its own formatted element
		foreach($result as $row){
			$price = $row['price'];
			$quantity = $row['quantity'];
		        new orderCard($row['name'],$row['sku'],$price,$quantity,$row['id']);
			$subtotal += ($price * $quantity);
		}

		// Display overall price at the end
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
	}
?>
</body>
</html>
