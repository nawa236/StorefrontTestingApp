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
		
	?>
	<form>
		Billing Information:
		First name:<br>
		<input type="text" name="firstname"><br>
		Last name:<br>
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
		<input type="date" name="expiration" min="2019-11" max="2023-11"><br>
		<script>
			var min = new Date();
			var max = new Date();
			var mm = today.getMonth()+1; //January is 0!
			var yyyy = today.getFullYear();
			if(mm<10){
				mm='0'+mm
			} 
			min = yyyy+'-'+mm;
			document.getElementById("datefield").setAttribute("min", min);
			yyyy = today.getFullYear()+4;
			max = yyyy+'-'+mm;
			document.getElementById("datefield").setAttribute("min", max);
		</script>
		Security Code:
		<input type="text" name="security" maxlength="3"><br>
		<input type="submit" value="Checkout"><br>
	</form>
</body>