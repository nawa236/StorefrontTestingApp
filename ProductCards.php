<?php

include('dbConnect.php');

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
	foreach($result as $row)
   		makeCard($row['name'],$row['price'],$row['description'],$row['catagory']);
}


function makeCard($name,$price,$description,$foodType) {
      $image = "./images/" . str_replace(" ","",$name) . ".jpg"; 
      echo "<div class=\"card\">";
      echo "<img src=$image alt=$name style=\"width:100%\">";
      echo "<h1>$name</h1>";
      $formattedPrice = number_format ($price,2); 
      echo "<p class=\"price\">\$$formattedPrice</p>";
      echo "<p>$description</p>";
      $id = "button" . $name;
      echo "<p><button id=$id>Configure</button></p>";
      echo "</div>";
 }

?>
