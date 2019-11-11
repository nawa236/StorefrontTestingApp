<!DOCTYPE html>

<head>
<link rel="stylesheet" href="styles.css">
</head>

<body>
<?php

// set error handling
ini_set('display_errors',1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$servername = 'localhost';
$username = 'root';
$password = ''; // use your own username and password for the server.

$dbversion = 0.1;
$dbname = 'EmployeeTraining';
$connection = new mysqli($servername, $username, $password, $dbname);

$userid = 0;
$loggedin = 0;
$pageinfo = "";

$emailError = "";
$passwordError = "";
$accountError = "";
$cookiecheck = "";

$fnameError = "";
$mnameError = "";
$lnameError = "";
$address1Error = "";
$cityError = "";
$state_provinceError = "";
$postalcodeError = "";
$accountMessage = "test";




if($connection -> connect_error){
	echo "Error connecting to database - " + $connection->connect_error;
}


if($_SERVER["REQUEST_METHOD"] == "POST") {

    // If POST is related to login form
    if (isset($_POST['email']) || isset ($_POST['password'])) {
        if (empty($_POST['email']) && empty($_POST['password'])) {
            $emailError = "Email is required";
            $emailError = "password is required";

        }
        else if (empty($_POST['email'])) {
            $emailError = "Email is required";
        }
        else if (empty($_POST['password'])) {
            $passwordError = "password is required";
        }
        else {

            $username = trim($_POST['email']);
            $usernamecheck = "SELECT * FROM  authentication WHERE email = '" . $username . "'"; 
            $usernamecheckquery = mysqli_query($connection, $usernamecheck);
            $usernamecheckcount = mysqli_num_rows($usernamecheckquery);

            if ($usernamecheckcount == 0) {

                $accountError = "No user found with that email.";
            }
            else
            {
                $password = trim($_POST['password']);
                $fetchedquery = mysqli_fetch_assoc($usernamecheckquery);
                if (password_verify($password, $fetchedquery["password"])) {
                    $idnum = $fetchedquery["id"];
                    setcookie("TriStorefrontUser", $idnum, "/");
                    $cookiecheck = "You are now logged in";
                    //echo "<script type='text/javascript'>alert('You are now logged in'); window.location = 'ProductList.php';</script>";
                    //header("Location: ProductList.php");
                }
                else {
                    $accountError = "Incorrect password";
                }



            }
        }
    }
    // Else post must be related to account info
    else {
        if (empty($_POST['fname']) || empty($_POST['mname']) || empty($_POST['lname']) || empty($_POST['address1']) || empty($_POST['city']) || empty($_POST['state_province']) || empty($_POST['postalcode'])) {

            if (empty($_POST['fname'])) {
                $fnameError = "Please enter a first name";
            }
            if (empty($_POST['mname'])) {
                $mnameError = "Please enter a middle name";
            }
            if (empty($_POST['lname'])) {
                $lnameError = "Please enter a last name";
            }
            if (empty($_POST['address1'])) {
                $address1Error = "Please enter your address info";
            }
            if (empty($_POST['city'])) {
                $cityError = "Please enter a city";
            }
            if (empty($_POST['state_province'])) {
                $state_provinceError = "Please enter a first name";
            }
            if (empty($_POST['postalcode'])) {
                $postalcodeError = "Please enter a postal code";
            }
        }
        else {

            $fname = trim($_POST['fname']);
            $mname = trim($_POST['mname']);
            $lname = trim($_POST['lname']);
            $address1 = trim($_POST['address1']);
            if (!empty($_POST['address2'])) {
                $address2 = trim($_POST['address2']);
            }
            else {
                $address2 = "";
            }
            $city = trim($_POST['city']);
            $state_province = trim($_POST['state_province']);
            $postalcode = trim($_POST['postalcode']);

            $userid = $_COOKIE["TriStorefrontUser"];
            $custinfoquery = "UPDATE customer SET fname = '$fname', mname = '$mname', lname = '$lname', address1 = '$address1', address2 = '$address2', city = '$city', state_province = '$state_province', postalcode = '$postalcode' WHERE id = $userid";
            $custinforesult = mysqli_query($connection, $custinfoquery);
            if ($custinforesult == 1) {
                $accountMessage = "Account Information Successfully Added";
                echo "<script type='text/javascript'>alert('Account Information Successfully Added!'); window.location = 'ProductList.php';</script>";
            }
            else {
            $accountMessage = "There was a problem adding your account information. Please try again.";
            }


        }
    }
}




if(isset($_COOKIE["TriStorefrontUser"])) {
    $loggedin = 1;
    $userid = $_COOKIE["TriStorefrontUser"];
    $idcheck = "SELECT * FROM  authentication WHERE id = '" . $userid . "'"; 
    $idcheckresult = mysqli_query($connection, $idcheck);
    $idcheckcount = mysqli_num_rows($idcheckresult);

    // This should never happen, but this will occur if there isn't actually account authentication info in the DB even though a cookie is set.
    if ($idcheckcount == 0) {
            $pageinfo = "An error has occured. Please attempt to log in below. Contact an adminstrator if this issue continues to persist";
            $loggedin = 0;
    }
    else {

        $usercheckquery = "SELECT * FROM customer WHERE id = '" . $userid . "'";
        $usercheckresult = mysqli_query($connection, $usercheckquery);
        $usercheckcount = mysqli_num_rows($idcheckresult);

        if ($usercheckcount == 0) {
            $userfetchedresult = mysqli_fetch_assoc($idcheckresult);
            $useremail = $userfetchedresult["email"];
            $custinitquery = "INSERT INTO customer(id, email) VALUES ('$userid', '$useremail')";
            $custinitresult = mysqli_query($connection, $custinitquery);
            $pageinfo = "Please enter your account information below to complete your account creation.";
        }
    }
}






require('header.php');
?>

<h3 id="formhead"> <?php echo $pageinfo ?> </h3>

<?php 
if ($loggedin = 1) { 
echo '
<form id="info_form" action="firsttime.php" method="post">
<fieldset>
<legend>Account Information</legend>
<div class="form-group">	
<label for="fname">First Name: </label>
<input class="form-control" type="text" name="fname" id="fname" maxlength="40" />
<span class="error"> <?php echo $fnameError;?> </span> </div>

<div class="form-group">
<label for="mname">Middle Name: </label>
<input class="form-control" type="text" name="mname" id="mname" maxlength="40" />
<span class="error"> <?php echo $mnameError;?> </span> </div>

<div class="form-group">
<label for="lname">Last Name: </label>
<input class="form-control" type="text" name="lname" id="lname" maxlength="40" />
<span class="error"> <?php echo $lnameError;?> </span> </div>

<div class="form-group">
<label for="address1">Address 1: </label>
<input class="form-control" type="text" name="address1" id="address1" maxlength="50" />
<span class="error"> <?php echo $address1Error;?> </span> </div>

<div class="form-group">
<label for="address2">Address 2: </label>
<input class="form-control" type="text" name="address2" id="address2" maxlength="50" />
<span class="error"> <?php echo $address2Error;?> </span> </div>

<div class="form-group">
<label for="city">City: </label>
<input class="form-control" type="text" name="city" id="city" maxlength="30" />
<span class="error"> <?php echo $cityError;?> </span> </div>

<div class="form-group">
<label for="state_province">State or Province: </label>
<input class="form-control" type="text" name="state_province" id="state_province" maxlength="20" />
<span class="error"> <?php echo $state_provinceError;?> </span> </div>

<div class="form-group">
<label for="postalcode">Postal Code: </label>
<input class="form-control" type="text" name="postalcode" id="postalcode" maxlength="10" />
<span class="error"> <?php echo $postalcodeError;?> </span> </div>

<input type="hidden" name="utype" value="2" />
<input class="btn btn-default" type="submit" name="submit" value="Submit" />
</fieldset>
</form>

<span class="message"> <?php echo $accountMessage; ?> </span>
<span class="error"> <?php echo $duplicateError; ?> </span>
';} // if
else {
echo '
<form id="login_form" action="login.php" method="post">
<fieldset>
<legend>Login</legend>
<div class="form-group">	
<label for="email">Email: </label>
<input class="form-control" type="text" name="email" id="email" maxlength="50" />
<span class="error"> <?php echo $emailError;?> </span> </div>

<div class="form-group">
<label for="password">Password: </label>
<input class="form-control" type="password" name="password" id="password" maxlength="10" />
<span class="error"> <?php echo $passwordError;?> </span> </div>

<input type="hidden" name="utype" value="2" />
<input class="btn btn-default" type="submit" name="submit" value="Login" />
</fieldset>
</form>
';} // else
?>
<span class="message"> <?php echo $accountMessage; ?> </span>

</body>