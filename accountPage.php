<!DOCTYPE html>
<html>
<head>
<title>Account</title>
<link rel = "stylesheet" href = "styles.css"> 
<link rel = "stylesheet" href = "loginregstyles.css">

</head>
<body>

<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require('header.php');
include('dbConnect.php');
$id = $_COOKIE["TriStorefrontUser"];

//ensure connection established
if($connection -> connect_error){
        echo "Error connecting to database - " + $connection->connect_error;
}
//if user intention is to change account information
if($_SERVER["REQUEST_METHOD"] == "POST"){
	//if conditionals to determine if account information is being changed/applies changes
	if(isset($_POST['fname'])){
		$name = trim($_POST['fname']);
		$sql = "UPDATE customer SET fname = '$name' WHERE id = $id";
		$res = mysqli_query($connection, $sql) or die("Could not update".mysql_error());
	}
	if(isset($_POST['mname'])){
		$name = trim($_POST['mname']);
		$sql = "UPDATE customer SET mname = '$name' WHERE id = $id";
		$res = mysqli_query($connection, $sql) or die("Could not update".mysql_error());
	}
	if(isset($_POST['lname'])){
		$name = trim($_POST['lname']);
		$sql = "UPDATE customer SET lname = '$name' WHERE id = $id";
		$res = mysqli_query($connection, $sql) or die("Could not update".mysql_error());
	}
	if(isset($_POST['address1'])){
		$name = trim($_POST['address1']);
		$sql = "UPDATE customer SET address1 = '$name' WHERE id = $id";
		$res = mysqli_query($connection, $sql) or die("Could not update".mysql_error());
	}
	if(isset($_POST['address2'])){
		$name = trim($_POST['address2']);
		$sql = "UPDATE customer SET address2 = '$name' WHERE id = $id";
		$res = mysqli_query($connection, $sql) or die("Could not update".mysql_error());
	}
	if(isset($_POST['city'])){
		$name = trim($_POST['city']);
		$sql = "UPDATE customer SET city = '$name' WHERE id = $id";
		$res = mysqli_query($connection, $sql) or die("Could not update".mysql_error());
	}
	if(isset($_POST['state_province'])){
		$name = trim($_POST['state_province']);
		$sql = "UPDATE customer SET state_province = '$name' WHERE id = $id";
		$res = mysqli_query($connection, $sql) or die("Could not update".mysql_error());
	}
	if(isset($_POST['postalcode'])){
		$name = trim($_POST['postalcode']);
		$sql = "UPDATE customer SET postalcode = '$name' WHERE id = $id";
		$res = mysqli_query($connection, $sql) or die("Could not update".mysql_error());
	}
	if(isset($_POST['email'])){
		$name = trim($_POST['email']);
		if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $name)){
        		$emailError = "Email must be in a valid format such as xxxx@yyyy.com";
	        }
		$sql = "UPDATE customer SET email = '$name' WHERE id = $id";
		$res = mysqli_query($connection, $sql) or die("Could not update".mysql_error());
                $sql = "UPDATE authentication SET email = '$name' WHERE id = $id";
                $res = mysqli_query($connection, $sql) or die("Could not update".mysql_error());

	}
}
//query for user account information output
$sql = "SELECT * FROM customer WHERE id = $id";
$res = mysqli_query($connection, $sql);
//cycles through results outputting desired information
if(mysqli_num_rows($res) > 0){
	while($row = mysqli_fetch_assoc($res)){
		$fname = $row["fname"];
		$mname = $row["mname"];
		$lname = $row["lname"];
		$ad1   = $row["address1"];
		$ad2   = $row["address2"];
		$city  = $row["city"];
		$state = $row["state_province"];
		$zip   = $row["postalcode"];
		$email = $row["email"];
	}
}
?> <h1 class="form-header"> Account Information </h1> 
<form class= "form-style-9" id = "Account Page" action = "accountPage.php" method = "post">
<ul>
<li>
<div class="form-group">
	<label>First Name</label><br>
	<input class="field-style field-full align-none" type = "text" id="fnameInput" name = "fname" value = "<?php echo $fname;?>"/>
</li>

<li>
	<div class="form-group">
	<label>Middle Name</label><br>
	<input class="field-style field-full align-none" type = "text" id="mnameInput" name = "mname" value = "<?php echo $mname;?>"/>
</li>

<li>
	<div class="form-group">
	<label>Last Name</label><br>
	<input class="field-style field-full align-none" type = "text" id="lnameInput" name = "lname" value = "<?php echo $lname;?>"/>
</li>

<li>
	<div class="form-group">
	<label>Address 1</label><br>
	<input class="field-style field-full align-none" type = "text" id="address1Input" name = "address1" value = "<?php echo $ad1;?>"/>
</li>

<li>
	<div class="form-group">
        <label>Address 2</label><br>
        <input class="field-style field-full align-none" type = "text" id="address2Input" name = "address2" value = "<?php echo $ad2;?>"/>
</li>

<li>
	<div class="form-group">
	<label>City</label><br>
	<input class="field-style field-full align-none" type = "text" id="cityInput" name = "city" value = "<?php echo $city;?>"/>
</li>

<li>
	<div class="form-group">
	<label>State-Province</label><br>
	<input class="field-style field-full align-none" type = "text" id="stateInput" name = "state_province" value = "<?php echo $state;?>"/>
</li>

<li>
	<div class="form-group">
	<label>Postal-Code</label><br>
	<input class="field-style field-full align-none" type = "number" id="zipInput" name = "postalcode" value = "<?php echo $zip;?>"/>
</li>

<li>
	<div class="form-group">
	<label>Email</label><br>
	<input class="field-style field-full align-none" type = "email" id="emailInput" name = "email" value = "<?php echo $email;?>"/>
</li>

<li>
<br>
	<input type = "submit" value = "Update"/>
</li>
</ul>
</form>

</body>
</html>
