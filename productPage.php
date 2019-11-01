<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <title>Product Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>



<?php
require('header.php');
if(isset($_GET['productID'])){
	$id = $_GET['productID'];
	include('dbConnect.php');
	include('productCard.php');

	$query = "SELECT * FROM product WHERE id = '" .$id;
	$query = $query . "'";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$total_row = $statement->rowCount();
        $pName = $result[0]['name'];
        echo "<h2> Product Page for: $pName </h2>";
	if($total_row > 0) {
		foreach($result as $row){
			new ProductCard($row['name'],$row['price'],$row['description'],$row['id']);
		}
	}
}

echo "<h1> This does not match the intended final design this purely exists as a base template to demonstrate that the pages are properly communicating. </h1>";
?>

</body>
</html>
