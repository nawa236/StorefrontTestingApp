<?php

include('dbConnect.php');
include('productCard.php');
include('bugCheck.php');

//*****  Bug 1 Start ****//
$bugCode = bug_check(1);
if(is_null($bugCode)) 
  $categories = array("Paper"=>1,"Envelopes"=>2,"Boxes"=>3,"Labels"=>4,"Clothing"=>5,"Misc"=>6);
else
  eval($bugCode);
//*****   Bug 1 End  ****//

$search = $_POST["searchInput"];
$minPrice = $_POST["minPrice"];
$maxPrice = $_POST["maxPrice"];

//*****  Bug 2 Start ****//
$bugCode = bug_check(2);
if(is_null($bugCode))
	$sort = $_POST["sort"];
else
  	eval($bugCode);
//*****   Bug 2 End  ****//

// Search box tests if product names share substring match with user search
if(str_replace(" ","",$search) == "")
        $query = "SELECT * FROM product WHERE name IS NOT NULL";
else
        $query = "SELECT * FROM product WHERE name LIKE '%" . $search . "%'";

// Price falls within range of user input
$query .= " AND price >= " . $minPrice . " AND price <= " . $maxPrice;


// Product in category user specified
if($_POST["category"] != "All")
        $query .= " AND category = '" . $categories[$_POST["category"]] . "'";

// Wont show on main page items with 0 stock
$query .= " AND id in (SELECT min(id) FROM product WHERE inventory > 0 group by name)";
$query .= " ORDER BY " . $sort . ";";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();

// Display all matches using specially formatted function
if($total_row > 0) {
	foreach($result as $row){
		makeProductCard($row['name'],$row['price'],$row['description'],$row['id']);
	}
}

?>
