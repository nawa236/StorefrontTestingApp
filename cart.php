<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <title>Cart</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

	<?php
		require('header.php');
		include('dbConnect.php');
		$query = "SELECT * FROM orders WHERE custid = $_COOKIE["TriStorefrontUser"] AND status = "incomplete""; /*need to confirm status possibilites*/
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		if (mysql_num_rows($result)==0){
			echo "<p> There is nothing in your cart </p>";
		} else if (mysql_num_rows($result)>=2) {
			echo "<p> Error: Multiple incomplete orders detected </p>";
		} else {
			/*need to figure out how products stored*/
		}
	?>
</body>