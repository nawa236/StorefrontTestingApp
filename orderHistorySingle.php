<!DOCTYPE html>
<html>
<head>
<title>Order History</title>

<link rel="stylesheet" href="styles.css">

</head>
<body>


<?php

include('dbConnect.php');
$order = $_GET["order"];

$query = "SELECT * FROM orders,order_products WHERE oID=id AND custid=1";
$query .= " AND oID = '" . $_GET["order"] . "'";


$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();
echo "<h1> Order ID: " . $_GET["order"] . " Contents </h1>"; 
if($total_row > 0) {
	foreach($result as $row){
		echo " Product ID: " . $row['pID'] . " Quantity: " . $row['quantity'] . "<br>";
	}
}

?>
</body>
</html>
