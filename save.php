	<?php
		require('header.php');
		include('dbConnect.php');
		$query = "SELECT * FROM orders WHERE custid = $id AND status = 'Incomplete';";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$order = $row['oID'];
		foreach($_GET as $query_string_variable => $value) {
			if($query_string_variable === 'save'){
				$option = 1;
			} else if($query_string_variable === 'checkout'){
				$option = 2;
			} else {
			echo "$query_string_variable  = $value <br>";
			}
		};
		if($option === 1){
			echo "Saved";
		}else if($option === 2){
			echo "Checking out";
		}else{
			echo "How did you get here?";
		}
	?>