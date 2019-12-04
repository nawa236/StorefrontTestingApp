<!DOCTYPE html>
<html>
<head>
<title>Order History</title>

<link rel="stylesheet" href="styles.css">

</head>
<body>

<?php
require('header.php');
include('dbConnect.php');
$id = $_COOKIE["TriStorefrontUser"];

echo "<h1>Past Orders</h1>";
// Retrieve all orders and status for the current customer that have been completed
$query = "SELECT DISTINCT oID, status FROM orders,order_products WHERE oid=id AND status != 'Incomplete' and custid=$id;";

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();

// For each order, display basic information and a button to see more information for each order
if($total_row > 0) {
	foreach($result as $row){
		$oID = $row['oID'];
		echo "<p style='margin-left: 10px;' >Order ID: " . $row['oID']. "  ";
                echo "Status: " . $row['status']. "  ";
		echo '<a href="./orderHistorySingle.php?order=';
		echo $oID;
      		echo '" class="oButton" ';
		echo "id=buttonOrder$oID >Inspect</a> </p>";
	}
}

else {
	echo "<h1>You have no previous orders.</h1>";
}

?>
</body>
</html>
