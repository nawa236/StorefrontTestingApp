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
$forgotpasswordverified = 0;
$message = "";
$loginmessage = "";
$passwordError = "";
$password2Error = "";


// If there is a GET request (user clicked the verification link from their email)
if($_SERVER["REQUEST_METHOD"] == "GET") {
    // Get input as variables
    $email = $_GET["email"];
    $hash = $_GET["hash"];
    // Check if email is a correct email in the DB, then get hash from DB and check it against the received hash
    $emailcheck = "SELECT * FROM  authentication WHERE email = '" . $email . "'";
    $emailcheckquery = mysqli_query($connection, $emailcheck);
    $emailcheckcount = mysqli_num_rows($emailcheckquery);
    if ($emailcheckcount != 0) {
        $fetchedquery = mysqli_fetch_assoc($emailcheckquery);
        if ($hash == $fetchedquery["hash"]) {
            $forgotpasswordverified = 1;
        }
        else {
            $message = "There has been an error verifying your request, please make sure you have copied the URL correctly and try again.\nIf you continue to have problems please contact an administrator";
        }
    }
    else {
        $message = "There has been an error verifying your request, please make sure you have copied the URL correctly and try again.\nIf you continue to have problems please contact an administrator";
    }    

}

// If there was a POST request (user sent input from form trying to change password)
if($_SERVER["REQUEST_METHOD"] == "POST") {

    $forgotpasswordverified = 1;

    // If the password prompt is empty, inform user.
    if (empty($_POST['password'])) {
        $passwordError = "Password is required";
    }
    // If passwords don't match, inform user
    else if ($_POST['password'] != $_POST['password2']) {
        $password2Error = "Passwords must match";
    }
    // Else, reset password
    else {

        // Get input from POST, has new password
        $email = $_POST["email"];
        $newpassword = $_POST["password"];
        $hashnewpassword = password_hash($newpassword, PASSWORD_DEFAULT);
        // Check for auth info in DB and set new password
        $emailcheckquery = "SELECT * FROM  authentication WHERE email = '" . $email . "'"; 
        $emailcheckresult = mysqli_query($connection, $emailcheckquery);
        $emailcheckcount = mysqli_num_rows($emailcheckresult);
        if ($emailcheckcount == 1) {
            $passwordquery = "UPDATE authentication SET password = '$hashnewpassword' WHERE email = '$email'";
            $passwordresult = mysqli_query($connection, $passwordquery);
            if ($passwordresult == 1) {
                $message = "Password Reset Successfully. Please log in.";
                $loginmessage = "Click here to log in";

            }
            else {
                $message = "Password could not be reset. Please try again and contact your administrator if the issue persists.";
            }
        }


    }
}

?>
<!-- Only display form if received hash matched hash found in table for that email -->
<?php if($forgotpasswordverified == 1) : ?>

    <h2 class="form-header"> Enter your new password </h2>
    <form class="form-style-9" id="forgot_password_form" action="verifyforgotpassword.php" method="post">
    <ul>
    <li>
    <div class="form-group">
    <label for="password">Password </label>
    <input class="field-style field-full align-none" type="password" name="password" id="password" maxlength="10" />
    <span class="error"> <?php echo $passwordError;?> </span> </div>
    </li>
    <li>
    <div class="form-group">
    <label for="password">Retype Password </label>
    <input class="field-style field-full align-none" type="password" name="password2" id="password2" maxlength="10" />
    <span class="error"> <?php echo $password2Error;?> </span> </div>
    </li>
    <input type="hidden" name="email" value="<?php echo $email ?>">

    <li>
    <input class="btn btn-default" type="submit" name="submit" value="Reset Password" />
    </li>
    </form>

    
    <p class="under-form"> <?php echo $message ?> </p>

<?php else: ?>

    <p class="under-form"> <?php echo $message ?> </p>
    <p class="under-form"> <a href="login.php"> <?php echo $loginmessage ?> </a> </p>

<?php endif; ?>



</body>