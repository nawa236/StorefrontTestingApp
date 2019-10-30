<!DOCTYPE html>
<html>
<head>
<title>Order History</title>

<link rel="stylesheet" href="styles.css">

</head>
<body>


<?php

include('dbConnect.php');
include('productCard.php');
$query = "SELECT DISTINCT oID FROM orders,order_products WHERE oID=id AND custid=1;";


$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();

if($total_row > 0) {
	foreach($result as $row){
		echo "<p>Order ID: " . $row['oID']. "  ";
		echo '<a href="./orderHistorySingle.php?order=';
      		echo $row['oID']; 
      		echo '" class="oButton" ';
		echo "id=$id >Inspect</a> </p>";
	}
}

?>
</body>
</html>
