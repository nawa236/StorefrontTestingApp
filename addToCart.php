<?php
include('dbConnect.php');
$pID = $_POST["pID"];
$uID = $_POST["uID"];
$query = "SELECT id FROM orders WHERE custid = '" .$uID;
$query = $query . "'";
$query = $query . " AND status = 'Incomplete'";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();
if($total_row == 1){
	$query = "INSERT INTO order_products VALUES(" . $result[0]['id'] . ", " . $pID . ",1);";
	$statement = $connect->prepare($query);
	$statement->execute();
	echo "Successfully added the item to the cart.";
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
        $query = "INSERT INTO order_products VALUES(" . $result[0]['id'] . ", " . $pID . ",1);";
        $statement = $connect->prepare($query);
        if($statement->execute())
            echo "Successfully added the item to the cart.";
	else
	    echo "Item was not added to the cart.";
}
else
echo "Well, there are multiple carts, everything is on fire apparently.";
?>
