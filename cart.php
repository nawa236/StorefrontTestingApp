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
			echo '<form action="./checkout.php">';
			$total = 0;
			echo '<div id="wrapper" class="filter">';
			foreach($result as $row){
				$price = $row['price'];
				$quantity = $row['quantity'];
			        new cartCard($row['name'],$row['sku'],$price,$quantity,$row['id']);
				$total += ($price * $quantity);
			}
			echo "</div>";
			echo "<p> Total: $";
			echo number_format($total,2);
			echo "</p>";
			echo '<input type="submit" value="Checkout">';
			echo "</form>";
		}
	?>
</body>
</html>
