<!DOCTYPE html>

<?php


// set error handling
ini_set('display_errors',1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Database connection and bug check framework
require('dbConnect.php');

// Initialize variables used for dynamic text/information
$emailError = "";
$passwordError = "";
$accountError = "";
$cookiecheck = "";
$reverifyMessage = "";

// Used for user account status check
define("fullyverified", 2);
define("emailverified", 1);

if($connection -> connect_error){
	echo "Error connecting to database - " + $connection->connect_error;
}

// If the user has already submitted form data
if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if user missed any inputs
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

        // Get email from POST and check the authentication table for an account with that email
        $username = trim($_POST['email']);
        $usernamecheck = "SELECT * FROM  authentication WHERE email = '" . $username . "'"; 
        $usernamecheckquery = mysqli_query($connection, $usernamecheck);
        $usernamecheckcount = mysqli_num_rows($usernamecheckquery);
        if ($usernamecheckcount == 0) {

            $accountError = "No user found with that email.";
        }
        else
        {
            // Get password from POST and compare it to the hashed password in the authentication table
            $password = trim($_POST['password']);
            $fetchedquery = mysqli_fetch_assoc($usernamecheckquery);
            if (password_verify($password, $fetchedquery["password"])) {
                // If the user is an admin
                if ($fetchedquery["type"] == 1) {
                    $idnum = $fetchedquery["id"];
                    setcookie("TriStorefrontUser", $idnum,time()+3600, "/");
                    $cookiecheck = "You are now logged in";
                    echo "<script type='text/javascript'>alert('You are now logged in'); window.location = 'admin.php';</script>";   
                }
                // Else, user is not an admin
                else {
                    // Check if user has completed their account creation. If not, send them to the finalization page
                    if ($fetchedquery["status"] == fullyverified) {
                        // Initialize name and id cookies, then redirect user to ProductList page
                        $idnum = $fetchedquery["id"];

                        $namequery = "SELECT fname FROM customer WHERE id = $idnum";
                        $nameresult = mysqli_query($connection, $namequery);
                        $fetchedname = mysqli_fetch_assoc($nameresult);
                        $name = $fetchedname["fname"];
                        setcookie("TriStorefrontName", $name, time()+3600, "/");

                        setcookie("TriStorefrontUser", $idnum,time()+3600, "/");
                        $cookiecheck = "You are now logged in";
                        echo "<script type='text/javascript'>alert('You are now logged in'); window.location = 'productList.php';</script>";
                    }
                    else { // User has not fully completed account creation, so check if they have done the email verification and ask if they need to resend the email if not.
                        if ($fetchedquery["status"] == emailverified) {
                            $idnum = $fetchedquery["id"];
                            setcookie("TriStorefrontUser", $idnum,time()+3600, "/");
                            $cookiecheck = "You are now logged in, please enter your information in on the next page to complete account creation.";
                            echo "<script type='text/javascript'>alert('You are now logged in, please enter your information in on the next page to complete account creation'); window.location = 'firsttime.php';</script>";
                        }
                        else {
                            $accountError = "Your account has not yet been verified through email. Please verify your account to login.";
                            echo "<script type='text/javascript'>alert('Your account has not yet been verified through email. Please verify your account to login.');</script>";
                            $reverifyMessage = "Didn't receive verification email? Click here to resend!";
                        }
                    }
                }
            }
            else {
                $accountError = "Incorrect password";
            }



        }
    }
}
?>

<head>
<link rel="stylesheet" href="loginregstyles.css">
</head>

<body>
<h2 class="form-header"> Login </h2>
<form class="form-style-9" id="login_form" action="login.php" method="post">
<ul>
<li>
<div class="form-group">	
<label for="email">Email </label> <br>
<input class="field-style field-full align-none" type="text" name="email" id="email" maxlength="50" placeholder="Email"/>
<span class="error"> <?php echo $emailError;?> </span> </div>
</li>

<li>
<div class="form-group">
<label for="password">Password </label> <br>
<input class="field-style field-full align-none" type="password" name="password" id="password" maxlength="10" placeholder="Password"/>
<span class="error"> <?php echo $passwordError;?> </span> </div>
</li>
<li>
<input type="submit" name="submit" value="Login" />
</li>
</ul>
</form>

<p class="under-form"> <a href="reverify.php"> <?php echo $reverifyMessage ?> </a> </p>

<p class="under-form"> <a href="forgotpassword.php"> Forgot your password? Click here! </a> </p>
<p class="under-form"> <a href="register.php"> Don't have an account? Click here to register! </a> </p>

<p class="under-form-error"> <?php echo $accountError; ?> </p>
<p class="under-form-error"> <?php echo $cookiecheck; ?> </p>




</body>
