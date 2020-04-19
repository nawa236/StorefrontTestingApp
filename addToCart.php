<?php
include('dbConnect.php');

// Extract post variables
$pID = $_POST["pID"];
$uID = $_POST["uID"];
$quantity = $_POST["quantity"];
$inv = $_POST["inv"];

// Find current user's cart
$query = "SELECT id FROM orders WHERE custid = '" . $uID;
$query = $query . "'";
$query = $query . " AND status = 'Incomplete'";

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();

// Check if there is a store-wide discount currently set
$query = "SELECT * FROM configuration WHERE parameter = 'discount'";
$statement = $connect->prepare($query);
$statement->execute();
$disc_result = $statement->fetchAll();
$discount = $disc_result[0]["value"];

// If cart found, run insert cart function
if ($total_row == 1) {
	insertCart($result, $pID, $quantity);
}

// If user does not have a cart
else if ($total_row == 0) {
	// create a cart
	$query = "INSERT INTO orders(status,custid,discount) values ('Incomplete'," . $uID . "," . $discount . ");";
	$statement = $connect->prepare($query);
	$statement->execute();

	// find the id of that created cart and add item
	$query = "SELECT id FROM orders WHERE custid = '" . $uID;
	$query = $query . "'";
	$query = $query . " AND status = 'Incomplete'";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();

	insertCart($result, $pID, $quantity);
} else  	// this should never happen, but if multiple carts exist, acknowledge the issue
	echo "Well, there are multiple carts, everything is on fire apparently.";


function insertCart($result, $pID, $quantity)
{
	global $connect, $inv;
	if ($quantity == 0) {
		echo "You can't order 0 items.";
		return;
	}
	// Validate that there is enough stock for the order
	if ($inv < $quantity) {
		echo "Not enough stock for that order";
		return;
	}
	$oID =  $result[0]['id'];

	// Check if there is already some quantity of this product already in cart
  $query = "SELECT * FROM order_products WHERE oid = " . $oID . " AND pid = " . $pID . ";";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$total_row = $statement->rowCount();

	// If no matching products, safe to insert
	if ($total_row == 0) {
		$query = "INSERT INTO order_products VALUES(" . $oID . ", " . $pID . ", " . $quantity  . ");";
		$statement = $connect->prepare($query);
		if ($statement->execute())
			echo "Successfully added the item to the cart.";
		else
			echo "Item was not added to the cart.";
	}
	// If there is matching product, update quantity
	else {
		// As long as new purchase total is within stock limits
		if ($inv < $quantity + $result[0]['quantity']) {
			echo "You currently have " . $result[0]['quantity'] . " of this item in your cart, there is not enough stock to fill this additional order.";
			return;
		}
		$query = "UPDATE order_products SET quantity=quantity+" . $quantity . " WHERE  oid = " . $oID . " AND pid = " . $pID . ";";
		$statement = $connect->prepare($query);
		if ($statement->execute())
			echo "Successfully updated the quantity of the product in cart.";
		else
			echo "Item quantity was not successfully updated.";
	}
}
