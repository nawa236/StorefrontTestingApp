<?php

include('dbConnect.php');
include('productCard.php');
$categories = array("Paper"=>1,"Envelopes"=>2,"Boxes"=>3,"Labels"=>4,"Clothing"=>5,"Misc"=>6);
$search = $_POST["searchInput"];
$minPrice = $_POST["minPrice"];
$maxPrice = $_POST["maxPrice"];
$sort = $_POST["sort"];
if(str_replace(" ","",$search) == "")
        $query = "SELECT * FROM product WHERE name IS NOT NULL";
else
        $query = "SELECT * FROM product WHERE name LIKE '%" . $search . "%'";

$query .= " AND price >= " . $minPrice . " AND price <= " . $maxPrice;


if($_POST["category"] != "All")
        $query .= " AND category = '" . $categories[$_POST["category"]] . "'";


$query .= " AND id in (SELECT min(id) FROM product WHERE inventory > 0 group by name)";
$query .= " ORDER BY " . $sort . ";";
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
