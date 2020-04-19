<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <title>Wishlist</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

	<?php
		/*basic includes*/
		require('header.php');
		include('dbConnect.php');
		/*grab user id from cookie*/
		$id = $_COOKIE["TriStorefrontUser"];
		/*query sql database to get wishlist "order" id*/
		$query = "SELECT * FROM orders WHERE custid = $id AND status = 'Wishlist';";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$total_row = $statement->rowCount();
		/*no wishlist items therefore no wishlist*/
		if ($total_row == 0){
			echo "<p id='emptywishlist'> There is nothing in your wishlist </p>";
		} else if ($total_row >= 2) { /*multiple incomplete orders found so something went wrong and needs server admin to fix*/
			echo "<p id='errorwishlist'> Error: Multiple lists detected </p>";
		} else {
			$oID = $result[0]['id'];
			/*query sql database to get all the order items*/
			$query = "SELECT * FROM order_products, product WHERE pid=id AND oid=$oID";
			$statement = $connect->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();
			if ($statement->rowCount() == 0){
				echo "<h1> Wishlist is Empty </h1>";
				return;
			}
			echo "<h1> Wishlist </h1>";
			/*wishlist form start*/
			//Form made for user inputs regarding removing an item from the wishlist or adding one to the cart
			echo '<form action="./saveW.php" id="wishlistForm">';
			echo '<input type="submit" value="Add to Cart" name="tocart" style="float: right; margin: 10px;" id="wishlistForm">';
			echo '<input type="submit" value="Remove" name="remove" style="float: right; margin: 10px;" id="wishlistForm">';
			echo '<input type="hidden" name="oID" value="' . $oID . '" />';
			$subtotal = 0;
			echo '<div id="wrapper" class="filter">';
			/*loop through results to display each item in its own box*/
			foreach($result as $row){
				/*extract variables from query row*/
				$price = $row['price'];
				$quantity = $row['quantity'];
				$sku = $row['sku'];
				$name = $row['name'];
				$pid = $row['pid'];
				/*display information in a new div*/
			    echo '<div class="cartcard" id="cartitem' . $pid . '">';
				echo '<p style="float:left; margin: 10px;">'. $name .'	</p>';
				echo '<p style="float:left; margin: 10px;" id="cartsku' . $pid . '">	SKU: ';
				echo "$sku ";
				echo '</p>';
				echo '<p style="float:right; margin: 10px;" id="cartquantity' . $pid . '"><input type="checkbox" value="Yes"   id="wishCheck"' . $pid;
				echo '" style="width: 60px" name="' . $pid;	
				echo '<p style="float:right; margin: 10px;" id="carttotal' . $pid . '">' . ' </p>';
				echo '</div>';
				
			};
			echo "</div>";
			/*submit buttons go to the same save page but pass different values*/
			
			echo "</form>";
		}
		
	?>
</body>
</html>
