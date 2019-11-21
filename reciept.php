<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <title>Checkout</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
	<form action= "./reciept.php">
	<?php
		require('header.php');
		include('dbConnect.php');
		$id = $_COOKIE["TriStorefrontUser"];
		foreach($_GET as $query_string_variable => $value) {
			if($query_string_variable != 'search'){
				echo "$query_string_variable = $value <br>";
			}
		};
	?>