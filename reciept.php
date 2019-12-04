<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <title>Checkout</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
	<form action= "./reciept.php">
	<?php
		/*basic includes*/
		require('header.php');
		include('dbConnect.php');
		/*grab user id from cookie*/
		$id = $_COOKIE["TriStorefrontUser"];
		/*Retrieve billing infomation from get query*/
		echo '<div id="recieptbillinfo">Bill To: <br>';
		echo $_GET["firstname"] . $_GET["lastname"] . "<br>";
		echo $_GET["address"] . "<br>";
		echo $_GET["city"] . ", " . $_GET["state"] . " " .$_GET["zip"] .  "</div><br>";
		/*Retrieve shipping infomation from get query*/
		echo "<div id='recieptshipinfo'>Ship To: <br>";
		echo $_GET["shipfirstname"] . $_GET["shiplastname"] . "<br>";
		echo $_GET["shipaddress"] . "<br>";
		echo $_GET["shipcity"] . ", " . $_GET["shipstate"] . " " .$_GET["shipzip"] .  "</div><br>";
		
		/*query sql database to get incomplete order id*/
		$query = "SELECT * FROM orders WHERE custid = $id AND status = 'Incomplete';";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$total_row = $statement->rowCount();
		$oID = $result[0]['id'];
		/*query sql database to get all the order items*/
		$query = "SELECT * FROM order_products, product WHERE pid=id AND oid=$oID";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		/*display purchased items, price, amount and total*/
		foreach($result as $row){
				$price = $row['price'];
				$quantity = $row['quantity'];
				$sku = $row['sku'];
				$name = $row['name'];
				$pid = $row['pid'];
			    echo '<div class="cartcard" id="recieptbillinfo' . $pid . '">';
				echo '<label style="float:left" id="recieptitemname' . $pid . '">'. $name .'	</label>';
				$formattedPrice = number_format ($price,2);
				echo '<label style="float:left;  margin-left:10px" id="recieptitemsku' . $pid . '">	SKU: ';
				echo "$sku ";
				echo '</label>';
				echo '<label style="float:left;  margin-left:10px"  id="recieptitemquantity' . $pid . '">Quantity: ' . $quantity . '</label>';
				echo '<label style="float:left;  margin-left:10px"  id="recieptitemprice' . $pid . '">	Price: $' . $formattedPrice . ' </label>';
				echo '<label style="float:left;  margin-left:10px"  id="recieptitemtotal' . $pid . '">	Total: $';
				$total = $price * $quantity;
				echo number_format($total,2). " </label>";
				echo "</div><br><br>";
				$subtotal += ($price * $quantity);
		};
		echo "</div> <br><br>";
		/*show shipping choice*/

                //***** Bug 20 Start *****//
                $bugCode = bug_check(20);
                if(is_null($bugCode))
		    	$shipping = (float) $_GET["ship"];
                //***** Bug 20 End *****//

		echo '<p style="text-align: left; margin: 10px;" id="recieptshipping"> Shipping: $' . $shipping;
		/*display subtotal, tax, and total*/
		echo '<p style="text-align: left; margin: 10px;" id="recieptsubtotal"> Subtotal: $';
		echo number_format($subtotal,2);
		echo '<p style="text-align: left; margin: 10px;" id="reciepttax"> Tax (6%): $';
		$tax = $subtotal * .06;
		echo number_format($tax,2);
		echo '<p style="text-align: left; margin: 10px;" id="reciepttotal"> Total: $';
		$total = $subtotal + $tax + $shipping;
		echo number_format($total,2);
		echo "</p>";
		/*mark order as complete*/
		$query = "UPDATE orders SET status = 'Complete' WHERE custid = $id AND status = 'Incomplete';";
		$statement = $connect->prepare($query);

		//***** Bug 18 Start *****//
		$bugCode = bug_check(18);
		if(is_null($bugCode))
	                $statement->execute();
		//***** Bug 18 End *****//

                //***** Bug 19 Start *****//
                $bugCode = bug_check(19);
                if(!is_null($bugCode))
                        eval($bugCode);
                //***** Bug 19 End *****//
?>
