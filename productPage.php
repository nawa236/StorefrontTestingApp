<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


    <title>Product Page</title>
    <link rel="stylesheet" href="styles.css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class="topnav">
  <a class="active" href="./productList.php">Home</a>
  <a href="#cart">Cart</a>
  <a href="#orders">Orders</a>
  <a class="account" href="#account">Account</a>

  <div class="search-container">
      <input type="text" class="textbox" placeholder="Search.." name="search" id="searchBox">
      <button id="buttonSearchBox"><i class="fa fa-search"></i></button>
  </div>
</div>

<?php
if(isset($_GET['productName'])){
	$pName = $_GET['productName'];
	echo "<h2> Product Page for: $pName </h2>";
	include('dbConnect.php');
	include('productCard.php');

	$query = "SELECT * FROM product WHERE name = '" .$pName;
	$query = $query . "'";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$total_row = $statement->rowCount();
	if($total_row > 0) {
		foreach($result as $row){
			new ProductCard($row['name'],$row['price'],$row['description'],$row['catagory']);
		}
	}
}
echo "<h1> This does not match the intended final design this purely exists as a base template to demonstrate that the pages are properly communicating. </h1>";
?>


</body>
</html>
