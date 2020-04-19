<?php
include('dbConnect.php');

// Extract post variables
$pID = $_POST["pID"];
$uID = $_POST["uID"];
$quantity = $_POST["quantity"];
$inv = $_POST["inv"];

// Find current user's wishlist
$query = "SELECT id FROM orders WHERE custid = '" .$uID;
$query = $query . "'";
$query = $query . " AND status = 'Wishlist'";

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();

// If wishlist found, run insert wishlist function
if($total_row == 1){
	insertWishlist($result,$pID,$quantity);
}

// If user does not have a wishlist
else if($total_row == 0){
	// create a wishlist
	$query = "INSERT INTO orders(status,custid) values ('Wishlist'," . $uID . ");";
        $statement = $connect->prepare($query);
        $statement->execute();

	// find the id of that created wishlist and add item
	$query = "SELECT id FROM orders WHERE custid = '" .$uID;
	$query = $query . "'";
	$query = $query . " AND status = 'Wishlist'";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();

	insertWishlist($result,$pID,$quantity);
}
else  	// this should never happen, but if multiple wishlists exist, acknowledge the issue
	echo "Well, there are multiple wishlists, everything is on fire apparently.";


function insertWishlist($result,$pID,$quantity){
        global $connect, $inv;
	$oID =  $result[0]['id'];

	// Check if there is already some quantity of this product already in wishlist
       	$query = "SELECT * FROM order_products WHERE oid = " . $oID . " AND pid = " . $pID . ";";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$total_row = $statement->rowCount();

	// If no matching products, safe to insert
	if($total_row == 0){
            $query = "INSERT INTO order_products VALUES(" . $oID . ", " . $pID . ", " . $quantity  . ");";
            $statement = $connect->prepare($query);
            if($statement->execute())
        	echo "Successfully added the item to the wishlist.";
            else
                echo "Item was not added to the wishlist.";
	}
	elseif($quantity == 0){
		$query = "DELETE FROM order_products WHERE pid = $pID AND oid=$oID";
					$statement = $connect->prepare($query);
					//***** Bug 16 Start *****//
					$bugCode = bug_check(16);
					if(is_null($bugCode))
					    $statement->execute();
					//***** Bug 16 End *****//
					echo "Item removed from wishlist.";
	}
	else{
		echo "Item already in wishlist";
	}
}
?>


