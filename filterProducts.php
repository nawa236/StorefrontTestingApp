<?php

include('dbConnect.php');
include('productCard.php');
$categories = array("Paper"=>1,"Envelopes"=>2,"Boxes"=>3,"Labels"=>4,"Clothing"=>5,"Misc"=>6);
$search = $_POST["searchInput"];
if(str_replace(" ","",$search) == "")
        $query = "SELECT * FROM product WHERE name IS NOT NULL";
else
        $query = "SELECT * FROM product WHERE name LIKE '%" . $search . "%'";

if($_POST["category"] != "All")
        $query .= " AND category = '" . $categories[$_POST["category"]] . "'";

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
		new ProductCard($row['name'],$row['price'],$row['description'],$row['id']);
	}
}

?>
