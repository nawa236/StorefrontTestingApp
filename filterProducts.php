<?php

include('dbConnect.php');
include('productCard.php');

if($_POST["food"] == "All")
        $query = "SELECT * FROM product WHERE catagory IS NOT NULL";
else{
        $query = "SELECT * FROM product WHERE catagory = '" . $_POST["food"] . "'";
}
if($_POST["price"] == "Low")
        $query .= " AND price < 4";
if($_POST["price"] == "Med")
        $query .= " AND price >= 4 AND price <= 8";
if($_POST["price"] == "High")
        $query .= " AND price > 8";

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();

if($total_row > 0) {
	foreach($result as $row){
		new ProductCard($row['name'],$row['price'],$row['description'],$row['catagory']);
	}
}

?>