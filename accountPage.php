<!DOCTYPE html>
<html>
<head>
<title>Account</title>
<link rel = "stylesheet" href = "styles.css"> 
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
?>

<form id = "Account Page" action = "accountPage.php" method = "post">
<fieldset>
<legend>Account Information</legend>
<p>
	<label>First Name</label>
	<input type = "text" id="fnameInput" name = "fname" value = "<?php echo $fname;?>"/>
</p>
<p>
	<label>Middle Name</label>
	<input type = "text" id="mnameInput" name = "mname" value = "<?php echo $mname;?>"/>
</p>
<p>
	<label>Last Name</label>
	<input type = "text" id="lnameInput" name = "lname" value = "<?php echo $lname;?>"/>
</p>
<p>
	<label>Address 1</label>
	<input type = "text" id="address1Input" name = "address1" value = "<?php echo $ad1;?>"/>
</p>
<p>
        <label>Address 2</label>
        <input type = "text" id="address2Input" name = "address2" value = "<?php echo $ad2;?>"/>
</p>
<p>
	<label>City</label>
	<input type = "text" id="cityInput" name = "city" value = "<?php echo $city;?>"/>
</p>
<p>
	<label>State-Province</label>
	<input type = "text" id="stateInput" name = "state_province" value = "<?php echo $state;?>"/>
</p>
<p>
	<label>Postal-Code</label>
	<input type = "number" id="zipInput" name = "postalcode" value = "<?php echo $zip;?>"/>
</p>
<p>
	<label>Email</label>
	<input type = "email" id="emailInput" name = "email" value = "<?php echo $email;?>"/>
</p>
<p>
	<input type = "submit" value = "Update"/>
</p>
</fieldset>
</form>

</body>
</html>
