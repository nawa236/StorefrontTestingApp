<?php
include('dbConnect.php');
$pID = $_POST["pID"];
$uID = $_POST["uID"];
$quantity = $_POST["quantity"];
$inv = $_POST["inv"];
$query = "SELECT id FROM orders WHERE custid = '" .$uID;
$query = $query . "'";
$query = $query . " AND status = 'Incomplete'";

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();

if($total_row == 1){
	insertCart($result,$pID,$quantity);
}
else if($total_row == 0){
	$query = "INSERT INTO orders(status,custid) values ('Incomplete'," . $uID . ");";
        $statement = $connect->prepare($query);
        $statement->execute();
	$query = "SELECT id FROM orders WHERE custid = '" .$uID;
	$query = $query . "'";
	$query = $query . " AND status = 'Incomplete'";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	insertCart($result,$pID,$quantity);
}
else
	echo "Well, there are multiple carts, everything is on fire apparently.";


function insertCart($result,$pID,$quantity){
        global $connect, $inv;
	if($quantity == 0){
	    echo "You can't order 0 items.";
	    return;
	}
	if($inv < $quantity){
	    echo "Not enough stock for that order";
	    return;
	}
	$oID =  $result[0]['id'];
       	$query = "SELECT * FROM order_products WHERE oid = " . $oID . " AND pid = " . $pID . ";";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$total_row = $statement->rowCount();
	if($total_row == 0){
            $query = "INSERT INTO order_products VALUES(" . $oID . ", " . $pID . ", " . $quantity  . ");";
            $statement = $connect->prepare($query);
            if($statement->execute())
        	echo "Successfully added the item to the cart.";
            else
                echo "Item was not added to the cart.";
	}
	else{
	    if($inv < $quantity + $result[0]['quantity']){
		echo "You currently have " . $result[0]['quantity'] . " of this item in your cart, there is not enough stock to fill this additional order.";
		return;
	    }
 	    $query = "UPDATE order_products SET quantity=quantity+" . $quantity . " WHERE  oid = " . $oID . " AND pid = " . $pID . ";";
	    $statement = $connect->prepare($query);
            if($statement->execute())
                echo "Successfully updated the quantity of the product in cart.";
            else
                echo "Item quantity was not successfully updated.";
	}
}
?>


