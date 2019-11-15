	<?php
		require('header.php');
		include('dbConnect.php');
		foreach($_GET as $query_string_variable => $value) {
			if($query_string_variable == 'save'){
				$option = 1;
			} else if($query_string_variable == 'checkout'){
				$option = 2;
			} else if($query_string_variable == 'oID'){
				$oID = $value;
			} else {
				$q = (int)$value;
				$pID = (int)$query_string_variable;
				$query = "UPDATE order_products SET quantity = $q WHERE pid = $pID AND oid=$oID";
				$statement = $connect->prepare($query);
				$statement->execute();
			}
		};
		
		if($option == 1){
			echo "Saved";
		}else if($option == 2){
			echo "Checking out";
		}else{
			echo "How did you get here?";
		}
	?>