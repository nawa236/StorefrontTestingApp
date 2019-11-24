
<?php

include('dbConnect.php');
$pName = $_POST["pName"];
$currentColor = $_POST["currentColor"];
$currentSize = $_POST["currentSize"];

// Find the product that matches the input for name, color and size
$query = "SELECT * FROM product WHERE name = '" .$pName;
$query = $query . "'";
$query = $query . " AND color = '" . $currentColor;
$query = $query . "'";
$query = $query . " AND size = '" . $currentSize;
$query = $query . "'";


// If sku search, append sku to query as well
if(!empty($_POST['currentSKU'])){
$currentSKU = $_POST["currentSKU"];
$query = $query . " AND SKU = '" . $currentSKU;
$query = $query . "'";
}

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
echo $result[0]['id'];

?>
