	<?php
		require('header.php');
		include('dbConnect.php');
		$query = "SELECT * FROM orders WHERE custid = $id AND status = 'Incomplete';";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$order = $row['oID'];
		foreach($_GET as $query_string_variable => $value) {
			echo "$query_string_variable  = $value <br>";
		};
		if($_POST["save"]) {
			//User hit the save button, handle accordingly
			echo "save";
		}
		//You can do an else, but I prefer a separate statement
		if($_POST["approve"]) {
			echo "checkout";
		}
	?>