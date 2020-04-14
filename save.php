	<?php
		/*basic includes*/
		require('header.php');
		include('dbConnect.php');
		/*loop through get variables*/
		foreach($_GET as $query_string_variable => $value) {
			/*if they are saving do option 1*/
			if($query_string_variable == 'save'){
				$option = 1;
			} else if($query_string_variable == 'checkout'){/*if they ar checking out do option 2*/
				$option = 2;
			} else if($query_string_variable == 'tocart'){
				$option = 3;
			} else if($query_string_variable == 'remove'){
				$option = 4;
			} else if($query_string_variable == 'oID'){ /*save oID for later*/
				$oID = $value;
			} else { /*update quantities in sql table*/
				$q = (int)$value;
				$pID = (int)$query_string_variable;
				if($q == 0){ /*if quantity is set to 0, delete matching row*/
					$query = "DELETE FROM order_products WHERE pid = $pID AND oid=$oID";
					$statement = $connect->prepare($query);
					//***** Bug 16 Start *****//
					$bugCode = bug_check(16);
					if(is_null($bugCode))
					    $statement->execute();
					//***** Bug 16 End *****//
				} else {/*else update quantity*/
					$query = "UPDATE order_products SET quantity = $q WHERE pid = $pID AND oid=$oID";
					$statement = $connect->prepare($query);
                                        //***** Bug 17 Start *****//
                                        $bugCode = bug_check(17);
                                        if(is_null($bugCode))
                                            $statement->execute();
                                        //***** Bug 17 End *****//
				}
			}
		};
		
		if($option == 1){/*user hit save, return to cart*/
			header('Location: ./cart.php');
			die();
		}else if($option == 2){/*user hit checkout, advance to checkout page*/
			header('Location: ./checkout.php');
			die();
		}else if($option == 3){/*user hit add to cart from the wishlist page, return to wishlist*/
			header('Location: ./wishlist.php');
			die();
		}else if($option == 4){/*user hit remove from the wishlist page, return to wishlist*/
			header('Location: ./wishlist.php');
			die();
		}else{/*something went wrong*/
			echo "How did you get here?";
		}
	?>
