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

	<?php
		/*basic includes*/
		require('header.php');
		include('dbConnect.php');
		/*grab user id from cookie*/
		$id = $_COOKIE["TriStorefrontUser"];
		
		/*query sql database to get incomplete order id*/
		$query = "SELECT * FROM orders WHERE custid = $id AND status = 'Incomplete';";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$total_row = $statement->rowCount();
		/*no incomplete orders found so no cart*/
		if ($total_row == 0){
			echo "<p> There is nothing in your cart </p>";
		} else if ($total_row >= 2) { /*multiple incomplete orders found so something went wrong and needs server admin to fix*/
			echo "<p> Error: Multiple incomplete orders detected </p>";
		} else {
			$oID = $result[0]['id'];
			/*query sql database to get all the order items*/
			$query = "SELECT * FROM order_products, product WHERE pid=id AND oid=$oID";
			$statement = $connect->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();
			/*calculate order subtotal, tax and total*/
			$subtotal = 0;
			foreach($result as $row){
				$price = $row['price'];
				$quantity = $row['quantity'];
				$subtotal += ($price * $quantity);
			}
			$tax = $subtotal * .06;
			$total = $subtotal + $tax;
			
			/*checkout form start*/
			echo '<form action= "./reciept.php" id="checkoutform">';
			/*shipping infomation*/
			echo '<div style="float:right; margin:10px; border-style:double; padding:10px; margin-right:50px;">';
			echo '<input type="radio" name="ship" value="0" checked id="checkoutstandardship"> Standard (Free)<br>';
			echo '<input type="radio" name="ship" value="10.99" id="checkout2dayship"> 2-Day (+$10.99)<br>';
			echo '<input type="radio" name="ship" value="99.99" id="checkoutovernightship"> Over-Night (+$99.99)<br>';
			/*display taxes and totals*/
			$f_subtotal = number_format($subtotal,2);
			$f_tax =number_format($tax,2);
			$f_total = number_format($total,2);
			echo "<p id='checkoutsubtotal'>Subtotal = $$f_subtotal </p>";
			echo "<p id='checkouttax'>Tax = $$f_tax </p>";
			echo "<p id='checkouttotal'>Total = $$f_total </p>";
			echo '<input type="submit" value="Checkout" id="checkoutcheckout">';
			echo '</div></div>';
		};
	?>
		<!-- billing infomation form -->
		<div style="margin-left:10px;"><h3 style="width:20%;">Billing Information:</h3>
		<div style="height:5px;">First Name:
		<input type="text" name="creditcard" id="checkoutbillcard" style="position:absolute;margin-left:36px;width:150px;"></div><br>
		<div style="height:5px;">Last Name:
		<input type="text" name="creditcard" id="checkoutbillcard" style="position:absolute;margin-left:37px;width:150px;"></div><br>
		<div style="height:5px;">Address:
		<input type="text" name="creditcard" id="checkoutbillcard" style="position:absolute;margin-left:56px;width:150px;"></div><br>
		<div style="height:5px;">City:
		<input type="text" name="creditcard" id="checkoutbillcard" style="position:absolute;margin-left:87px;width:150px;"></div><br>
		<div style="height:5px;">State:
		<select name="shipstate" id="checkoutshipstate" style="position:absolute;margin-left:77px;width:154px;">
			<option value="AL">Alabama</option>
			<option value="AK">Alaska</option>
			<option value="AZ">Arizona</option>
			<option value="AR">Arkansas</option>
			<option value="CA">California</option>
			<option value="CO">Colorado</option>
			<option value="CT">Connecticut</option>
			<option value="DE">Delaware</option>
			<option value="DC">District Of Columbia</option>
			<option value="FL">Florida</option>
			<option value="GA">Georgia</option>
			<option value="HI">Hawaii</option>
			<option value="ID">Idaho</option>
			<option value="IL">Illinois</option>
			<option value="IN">Indiana</option>
			<option value="IA">Iowa</option>
			<option value="KS">Kansas</option>
			<option value="KY">Kentucky</option>
			<option value="LA">Louisiana</option>
			<option value="ME">Maine</option>
			<option value="MD">Maryland</option>
			<option value="MA">Massachusetts</option>
			<option value="MI">Michigan</option>
			<option value="MN">Minnesota</option>
			<option value="MS">Mississippi</option>
			<option value="MO">Missouri</option>
			<option value="MT">Montana</option>
			<option value="NE">Nebraska</option>
			<option value="NV">Nevada</option>
			<option value="NH">New Hampshire</option>
			<option value="NJ">New Jersey</option>
			<option value="NM">New Mexico</option>
			<option value="NY">New York</option>
			<option value="NC">North Carolina</option>
			<option value="ND">North Dakota</option>
			<option value="OH">Ohio</option>
			<option value="OK">Oklahoma</option>
			<option value="OR">Oregon</option>
			<option value="PA">Pennsylvania</option>
			<option value="RI">Rhode Island</option>
			<option value="SC">South Carolina</option>
			<option value="SD">South Dakota</option>
			<option value="TN">Tennessee</option>
			<option value="TX">Texas</option>
			<option value="UT">Utah</option>
			<option value="VT">Vermont</option>
			<option value="VA">Virginia</option>
			<option value="WA">Washington</option>
			<option value="WV">West Virginia</option>
			<option value="WI">Wisconsin</option>
			<option value="WY">Wyoming</option>
		</select></div><br>
		<div style="height:5px;">Zip:
		<input type="text" name="creditcard" id="checkoutbillcard" style="position:absolute;margin-left:92px;width:150px;"></div><br><br>
		<!-- payment infomation form -->
		<h3 style="width:20%;">Payment type:</h3>
		<input type="radio" name="payment" value="credit" checked id="checkoutbillcredit"> Credit 
		<input type="radio" name="payment" value="debit" id="checkoutbilldebit"> Debit<br>
		<div style="height:5px;">Card Number:
		<input type="text" name="creditcard" id="checkoutbillcard" style="position:absolute;margin-left:19px;width:150px;"></div><br>
		<div style="height:5px;">Expiration Date:
		<span class="expiration" id="checkoutbillexpiration">
			<input type="text" name="month" placeholder="MM" maxlength="2" size="2" id="checkoutbillmonth" style="position:absolute;margin-left:5px;width:65px;">
			<span style="position:absolute;margin-left:80px;">/</span>
			<input type="text" name="year" placeholder="YY" maxlength="2" size="2" id="checkoutbillyear" style="position:absolute;margin-left:90px;width:65px;">
		</span></div><br>

		<div style="height:5px;">Security Code:
		<input type="text" name="security" maxlength="3" id="checkoutbillseccode" style="position:absolute;margin-left:14px;width:150px;"></div><br><br>
		
		<!-- shipping infomation form -->
		<h3 style="width:20%;">Shipping Information:</h3>
		<div style="height:5px;">First Name:
		<input type="text" name="creditcard" id="checkoutbillcard" style="position:absolute;margin-left:36px;width:150px;"></div><br>
		<div style="height:5px;">Last Name:
		<input type="text" name="creditcard" id="checkoutbillcard" style="position:absolute;margin-left:37px;width:150px;"></div><br>
		<div style="height:5px;">Address:
		<input type="text" name="creditcard" id="checkoutbillcard" style="position:absolute;margin-left:56px;width:150px;"></div><br>
		<div style="height:5px;">City:
		<input type="text" name="creditcard" id="checkoutbillcard" style="position:absolute;margin-left:87px;width:150px;"></div><br>
		<div style="height:5px;">State:
		<select name="shipstate" id="checkoutshipstate" style="position:absolute;margin-left:77px;width:154px;">
			<option value="AL">Alabama</option>
			<option value="AK">Alaska</option>
			<option value="AZ">Arizona</option>
			<option value="AR">Arkansas</option>
			<option value="CA">California</option>
			<option value="CO">Colorado</option>
			<option value="CT">Connecticut</option>
			<option value="DE">Delaware</option>
			<option value="DC">District Of Columbia</option>
			<option value="FL">Florida</option>
			<option value="GA">Georgia</option>
			<option value="HI">Hawaii</option>
			<option value="ID">Idaho</option>
			<option value="IL">Illinois</option>
			<option value="IN">Indiana</option>
			<option value="IA">Iowa</option>
			<option value="KS">Kansas</option>
			<option value="KY">Kentucky</option>
			<option value="LA">Louisiana</option>
			<option value="ME">Maine</option>
			<option value="MD">Maryland</option>
			<option value="MA">Massachusetts</option>
			<option value="MI">Michigan</option>
			<option value="MN">Minnesota</option>
			<option value="MS">Mississippi</option>
			<option value="MO">Missouri</option>
			<option value="MT">Montana</option>
			<option value="NE">Nebraska</option>
			<option value="NV">Nevada</option>
			<option value="NH">New Hampshire</option>
			<option value="NJ">New Jersey</option>
			<option value="NM">New Mexico</option>
			<option value="NY">New York</option>
			<option value="NC">North Carolina</option>
			<option value="ND">North Dakota</option>
			<option value="OH">Ohio</option>
			<option value="OK">Oklahoma</option>
			<option value="OR">Oregon</option>
			<option value="PA">Pennsylvania</option>
			<option value="RI">Rhode Island</option>
			<option value="SC">South Carolina</option>
			<option value="SD">South Dakota</option>
			<option value="TN">Tennessee</option>
			<option value="TX">Texas</option>
			<option value="UT">Utah</option>
			<option value="VT">Vermont</option>
			<option value="VA">Virginia</option>
			<option value="WA">Washington</option>
			<option value="WV">West Virginia</option>
			<option value="WI">Wisconsin</option>
			<option value="WY">Wyoming</option>
		</select></div><br>
		<div style="height:5px;">Zip:
		<input type="text" name="creditcard" id="checkoutbillcard" style="position:absolute;margin-left:92px;width:150px;"></div><br>
		
	</form>
</body>
