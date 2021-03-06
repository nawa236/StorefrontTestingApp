<!DOCTYPE html>

<head>
<link rel="stylesheet" href="loginregstyles.css">
</head>

<body>
<?php

// set error handling
ini_set('display_errors',1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require('dbConnect.php');
// Initialize variables used for dynamic text/information
$userid = 0;
$pageinfo = "";
$fnameError = "";
$mnameError = "";
$lnameError = "";
$address1Error = "";
$cityError = "";
$state_provinceError = "";
$postalcodeError = "";
$accountMessage = "";



// Check DB connection
if($connection -> connect_error){
	echo "Error connecting to database - " + $connection->connect_error;
}

// If the user has submitted their form/a POST request is made
if($_SERVER["REQUEST_METHOD"] == "POST") {

    // If everything is empty, inform the user as such
    if (empty($_POST['fname']) || empty($_POST['mname']) || empty($_POST['lname']) || empty($_POST['address1']) || empty($_POST['city']) || empty($_POST['state_province']) || empty($_POST['postalcode'])) {
            // Check each input
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
    // If there is input for all required aspects of the form
    else {
        // Get all input from the user and store into variables
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

        // Insert information into the database
        $userid = $_COOKIE["TriStorefrontUser"];
        $custinfoquery = "UPDATE customer SET fname = '$fname', mname = '$mname', lname = '$lname', address1 = '$address1', address2 = '$address2', city = '$city', state_province = '$state_province', postalcode = '$postalcode' WHERE id = $userid";
        $custinforesult = mysqli_query($connection, $custinfoquery);
        // If the database call is successful, update the account authentication to show the account is fully intialized and set the name cookie
        if ($custinforesult == 1) {
            $accountstatusquery = "UPDATE authentication SET status = 2 WHERE id = $userid";
            $accountstatusresult = mysqli_query($connection, $accountstatusquery);
            if ($accountstatusresult == 1) {
                setcookie("TriStorefrontName", $fname, time()+3600, '/');
                $accountMessage = "Account Information Successfully Added";
                echo "<script type='text/javascript'>alert('Account Information Successfully Added!'); window.location = 'productList.php';</script>";
            }
            else {
                $accountMessage = "There was a problem adding your account information. Please try again.";
            }
        }
        else {
            $accountMessage = "There was a problem adding your account information. Please try again.";
        }


    }
}



// If the user is logged in and has not submitted a POST request, check the user's account id and do initial population of the customer information table if needed
if(isset($_COOKIE["TriStorefrontUser"])) {
    // Check if the user has account authentication information in the db
    $userid = $_COOKIE["TriStorefrontUser"];
    $idcheck = "SELECT * FROM  authentication WHERE id = '" . $userid . "'";
    $idcheckresult = mysqli_query($connection, $idcheck);
    $idcheckcount = mysqli_num_rows($idcheckresult);

    // This should never happen, but this will occur if there isn't actually account authentication info in the DB even though a cookie is set.
    if ($idcheckcount == 0) {
            $pageinfo = "An error has occured. Please attempt to log in below. Contact an adminstrator if this issue continues to persist";
    }
    else {
        // Check if the customer table has any information for that userid (i.e. the user has already loaded this page once but not completed entering information)
        $usercheckquery = "SELECT * FROM customer WHERE id = '" . $userid . "'";
        $usercheckresult = mysqli_query($connection, $usercheckquery);
        $usercheckcount = mysqli_num_rows($usercheckresult);
        // If customer table is empty for that userid, initialize the row with the userid and email
        if ($usercheckcount == 0) {

            $userfetchedresult = mysqli_fetch_assoc($idcheckresult);
            $useremail = $userfetchedresult["email"];
            $custinitquery = "INSERT INTO customer(id, email) VALUES ('$userid', '$useremail')";
            $custinitresult = mysqli_query($connection, $custinitquery);
            $pageinfo = "Please enter your account information below to complete your account creation.";
        }
    }
}
else {
    echo "<script type='text/javascript'>alert('Please log in to continue account creation'); window.location = 'login.php';</script>";
}






?>

<h3 id="formhead"> <?php echo $pageinfo ?> </h3>


<h2 class="form-header"> Account Information </h2>
<form class= "form-style-9" id="info_form" action="firsttime.php" method="post">
<ul>
<li>
<div class="form-group">	
<label for="fname">First Name </label> <br>
<input class="field-style field-full align-none" type="text" name="fname" id="fname" maxlength="40"/>
<span class="error"> <?php echo $fnameError;?> </span> </div>
</li>

<li>
<div class="form-group">
<label for="mname">Middle Name </label> <br>
<input class="field-style field-full align-none" type="text" name="mname" id="mname" maxlength="40" />
<span class="error"> <?php echo $mnameError;?> </span> </div>
</li>

<li>
<div class="form-group">
<label for="lname">Last Name </label> <br>
<input class="field-style field-full align-none" type="text" name="lname" id="lname" maxlength="40" />
<span class="error"> <?php echo $lnameError;?> </span> </div>
</li>

<li>
<div class="form-group">
<label for="address1">Address 1 </label> <br>
<input class="field-style field-full align-none" type="text" name="address1" id="address1" maxlength="50" />
<span class="error"> <?php echo $address1Error;?> </span> </div>
</li>

<li>
<div class="form-group">
<label for="address2">Address 2 </label> <br>
<input class="field-style field-full align-none" type="text" name="address2" id="address2" maxlength="50" />
</li>

<li>
<div class="form-group">
<label for="city">City </label> <br>
<input class="field-style field-full align-none" type="text" name="city" id="city" maxlength="30" />
<span class="error"> <?php echo $cityError;?> </span> </div>
</li>

<li>
<div class="form-group">
<label for="state_province">State or Province </label> <br>
<input class="field-style field-full align-none" type="text" name="state_province" id="state_province" maxlength="20" />
<span class="error"> <?php echo $state_provinceError;?> </span> </div>
</li>

<li>
<div class="form-group">
<label for="postalcode">Postal Code </label> <br>
<input class="field-style field-full align-none" type="text" name="postalcode" id="postalcode" maxlength="10" />
<span class="error"> <?php echo $postalcodeError;?> </span> </div>
</li>

<li>
<input type="submit" name="submit" value="Submit" />
</li>
</ul>
</form>

<span class="message"> <?php echo $accountMessage; ?> </span>

</body>
