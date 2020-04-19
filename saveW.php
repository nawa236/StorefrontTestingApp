<?php
		/*basic includes*/
		require('header.php');
        include('dbConnect.php');
		/*loop through get variables*/
		global $type;
		foreach($_GET as $query_string_variable => $value) {
			/*if they are removing an item do option 1*/
			if($query_string_variable == 'remove'){
				$type = 'remove';
				
			} else if($query_string_variable == 'tocart'){/*if adding to cart do option 2*/
				$type = 'tocart';
            } else if($query_string_variable == 'oID'){ /*save oID for later*/
				$oID = $value;
			} else if($type == 'remove'){ /*update quantities in sql table*/
                if($value == 'Yes'){
                    $q = 0;
                }
                else{
                    $q = 1;
                }
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
			else{ /*update quantities in sql table*/
                if($value == 'Yes'){
                    $q = 1;
                }
                else{
                    $q = 0;
                }
				$pID = (int)$query_string_variable;
				if($q == 1){ /*if user checked an item it is added to their cart*/
					$query = "SELECT custid FROM orders WHERE id = '" .$oID . "'";
					$statement = $connect->prepare($query);
					$statement->execute();
					$result = $statement->fetchAll();
					$uID = $result[0]['custid'];
					// Find current user's cart
					$query = "SELECT id FROM orders WHERE custid = '" .$uID;
					$query = $query . "'";
					$query = $query . " AND status = 'Incomplete'";

					$statement = $connect->prepare($query);
					$statement->execute();
					$result = $statement->fetchAll();
					$total_row = $statement->rowCount();

					// If cart found, run insert cart function
					if($total_row == 1){
					insertCart($result,$pID,$q);
					}

					// If user does not have a cart
					else if($total_row == 0){
						// create a cart
						$query = "INSERT INTO orders(status,custid) values ('Incomplete'," . $uID . ");";
							$statement = $connect->prepare($query);
							$statement->execute();

						// find the id of that created cart and add item
						$query = "SELECT id FROM orders WHERE custid = '" .$uID;
						$query = $query . "'";
						$query = $query . " AND status = 'Incomplete'";
						$statement = $connect->prepare($query);
						$statement->execute();
						$result = $statement->fetchAll();

						insertCart($result,$pID,$q);
					}
					else  	// this should never happen, but if multiple carts exist, acknowledge the issue
						echo "Well, there are multiple carts, everything is on fire apparently.";
					}
			}
		};
		if($type = 'remove' OR $type == 'tocart'){/*For either option we return to the wishlist page*/
			header('Location: ./wishlist.php');
			die();
		}else{/*something went wrong*/
			echo "How did you get here?";
		}

		function insertCart($result,$pID,$quantity){
			global $connect, $inv;
			$inv = 1;
			if($quantity == 0){
				echo "You can't order 0 items.";
				return;
			}
			// Validate that there is enough stock for the order
			if($inv < $quantity){
				echo "Not enough stock for that order";
				return;
			}
			$oID =  $result[0]['id'];
		
			// Check if there is already some quantity of this product already in cart
			$query = "SELECT * FROM order_products WHERE oid = " . $oID . " AND pid = " . $pID . ";";
			$statement = $connect->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();
			$total_row = $statement->rowCount();
		
			// If no matching products, safe to insert
			if($total_row == 0){
					$query = "INSERT INTO order_products VALUES(" . $oID . ", " . $pID . ", " . $quantity  . ");";
					$statement = $connect->prepare($query);
					if($statement->execute())
					echo "Successfully added the item to the cart.";
					else{
						echo "Item was not added to the cart.";
					}
			}
			// If there is matching product, update quantity
			else{
				$query = "UPDATE order_products SET quantity=quantity+" . $quantity . " WHERE  oid = " . $oID . " AND pid = " . $pID . ";";
				$statement = $connect->prepare($query);
					if($statement->execute())
						echo "Successfully updated the quantity of the product in cart.";
					else
						echo "Item quantity was not successfully updated.";
			}
		}
	?>
