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
		$query = "SELECT * FROM orders WHERE custid = $id AND status = 'Incomplete';";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$total_row = $statement->rowCount();
		$oID = $result[0]['id'];
		$query = "SELECT * FROM order_products, product WHERE pid=id AND oid=$oID";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$subtotal = 0;
		echo '<div style="float:right; margin: 10px;">';
		foreach($result as $row){
			$price = $row['price'];
			$quantity = $row['quantity'];
			$subtotal += ($price * $quantity);
		}
		$tax = $subtotal * .06;
		$total = $subtotal + $tax;
		
		echo '<input type="radio" name="ship" value="standard" checked> Standard (Free)<br>';
		echo '<input type="radio" name="ship" value="2day"> 2-Day (+$10.99)<br>';
		echo '<input type="radio" name="ship" value="overnight"> Over-Night (+$99.99)<br>';
		
		echo "<p>Subtotal = $$subtotal </p>";
		echo "<p>Tax = $$tax </p>";
		echo "<p>Total = $$total </p>";
		echo '</div>';
	?>
	
		Billing Information:<br>
		First name: 
		<input type="text" name="firstname"><br>
		Last name: 
		<input type="text" name="lastname"><br>
		Address:
		<input type="text" name="address"><br>
		City:
		<input type="text" name="city"><br>
		State:
		<select name="state">
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
		</select><br>
		Zip:
		<input type="text" name="zip"><br>
		Payment type:
		<input type="radio" name="payment" value="credit" checked> Credit 
		<input type="radio" name="payment" value="debit"> Debit<br>
		Card Number:
		<input type="text" name="creditcard"><br>
		Expiration Date:
		<span class="expiration">
			<input type="text" name="month" placeholder="MM" maxlength="2" size="2">
			<span>/</span>
			<input type="text" name="year" placeholder="YY" maxlength="2" size="2">
		</span><br>

		Security Code:
		<input type="text" name="security" maxlength="3"><br>
		<input type="submit" value="Checkout"><br>
	</form>
</body>