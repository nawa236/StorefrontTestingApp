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
include('dbconnect.php');

$id = $_COOKIE["TriStorefrontUser"];
$servername = 'localhost';
$username = 'root';
$password = '';

$dbversion = 0.1;
$dbname = 'EmployeeTraining';
$connection = new mysqli($servername, $username, $password, $dbname);

//ensure connection established
if($connection -> connect_error){
        echo "Error connecting to database - " + $connection->connect_error;
}

//if user intention is to change account information
if($_SERVER["REQUEST_METHOD"] == "POST"){

	//if conditionals to determine if account information is being changed/applies changes
	if(isset($_POST['fname'])){
		$name = trim($_POST['fname']);
		$sesh_id = $_SESSION['id'];
		$sql = "UPDATE customer SET fname = '$name' WHERE id = $sesh_id";
		$res = mysql_query($connection, $sql) or die("Could not update".mysql_error());
	}

	if(isset($_POST['mname'])){
		$name = trim($_POST['mname']);
		$sesh_id = $_SESSION['id'];
		$sql = "UPDATE customer SET mname = '$name' WHERE id = $sesh_id";
		$res = mysql_query($connection, $sql) or die("Could not update".mysql_error());
	}

	if(isset($_POST['lname'])){
		$name = trim($_POST['lname']);
		$sesh_id = $_SESSION['id'];
		$sql = "UPDATE customer SET lname = '$name' WHERE id = $sesh_id";
		$res = mysql_query($connection, $sql) or die("Could not update".mysql_error());
	}

	if(isset($_POST['address1'])){
		$name = trim($_POST['address1']);
		$sesh_id = $_SESSION['id'];
		$sql = "UPDATE customer SET address1 = '$name' WHERE id = $sesh_id";
		$res = mysql_query($connection, $sql) or die("Could not update".mysql_error());
	}

	if(isset($_POST['address2'])){
		$name = trim($_POST['address2']);
		$sesh_id = $_SESSION['id'];
		$sql = "UPDATE customer SET address2 = '$name' WHERE id = $sesh_id";
		$res = mysql_query($connection, $sql) or die("Could not update".mysql_error());
	}

	if(isset($_POST['city'])){
		$name = trim($_POST['city']);
		$sesh_id = $_SESSION['id'];
		$sql = "UPDATE customer SET city = '$name' WHERE id = $sesh_id";
		$res = mysql_query($connection, $sql) or die("Could not update".mysql_error());
	}

	if(isset($_POST['state_province'])){
		$name = trim($_POST['state_province']);
		$sesh_id = $_SESSION['id'];
		$sql = "UPDATE customer SET state_province = '$name' WHERE id = $sesh_id";
		$res = mysql_query($connection, $sql) or die("Could not update".mysql_error());
	}

	if(isset($_POST['postalcode'])){
		$name = trim($_POST['postalcode']);
		$sesh_id = $_SESSION['id'];
		$sql = "UPDATE customer SET postalcode = '$name' WHERE id = $sesh_id";
		$res = mysql_query($connection, $sql) or die("Could not update".mysql_error());
	}

	if(isset($_POST['email'])){
		$name = trim($_POST['email']);
		if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $name)){
        	$emailError = "Email must be in a valid format such as xxxx@yyyy.com";
        }
		$sesh_id = $_SESSION['id'];
		$sql = "UPDATE customer SET email = '$name' WHERE id = $sesh_id";
		$res = mysql_query($connection, $sql) or die("Could not update".mysql_error());
	}
}

//query for user account information output
$sql = "SELECT id, fname, mname, lname, address1, address2, city, state_province, postalcode, email FROM customer";
$res = mysqli_query($connection, $sql);

//cycles through results outputting desired information
if(mysqli_num_rows($res) > 0){
	while($row = mysqli_fetch_assoc($res)){
		echo "id: " . $row["id"]. "<br/>" . "Name: " . $row["fname"]. " " . $row["mname"]. " " . $row["lname"]. "<br/>" . "Address: " . $row["address1"]. " " . $row["city"]. " " . $row["state_province"]. " " . $row["postalcode"]. "<br/>" . "Email: " . $row["email"]. "<br/>";
	}
}

?>

<form id = "Account Page" action = "accountPage.php" method = "post">
<fieldset>
<legend>Account Information</legend>
<p>
	<label>First Name</label>
	<input type = "text" name = "fname" value = "fname"/>
</p>
<p>
	<label>Middle Name</label>
	<input type = "text" name = "mname" value = "mname"/>
</p>
<p>
	<label>Last Name</label>
	<input type = "text" name = "lname" value = "lname"/>
</p>
<p>
	<label>Address</label>
	<input type = "text" name = "address1" value = "address1"/>
</p>
<p>
	<label>City</label>
	<input type = "text" name = "city" value = "city"/>
</p>
<p>
	<label>State-Province</label>
	<input type = "text" name = "state_province" value = "state_province"/>
</p>
<p>
	<label>Postal-Code</label>
	<input type = "number" name = "postalcode" value = "postalcode"/>
</p>
<p>
	<label>Email</label>
	<input type = "email" name = "email" value = "email"/>
</p>
<p>
	<input type = "submit" value = "Submit"/>
</p>
</fieldset>
</form>


</body>
</html>
