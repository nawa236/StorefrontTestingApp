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

require('dbConnect.php');

$forgotpasswordverified = 0;
$message = "";
$loginmessage = "";
$passwordError = "";
$password2Error = "";

if($_SERVER["REQUEST_METHOD"] == "GET") {

    $email = $_GET["email"];
    $hash = $_GET["hash"];

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

if($_SERVER["REQUEST_METHOD"] == "POST") {


    if (empty($_POST['password'])) {
        $passwordError = "Password is required";
    }
    else if ($_POST['password'] != $_POST['password2']) {
        $password2Error = "Passwords must match";
    }
    else {

        $email = $_POST["email"];
        $newpassword = $_POST["password"];
        $hashnewpassword = password_hash($newpassword, PASSWORD_DEFAULT);

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

<?php if($forgotpasswordverified == 1) : ?>

    <form id="forgot_password_form" action="verifyforgotpassword.php" method="post">
    <fieldset>
    <legend>Enter your new password</legend>

    <div class="form-group">
    <label for="password">Password: </label>
    <input class="form-control" type="password" name="password" id="password" maxlength="10" />
    <span class="error"> <?php echo $passwordError;?> </span> </div>

    <div class="form-group">
    <label for="password">Retype Password: </label>
    <input class="form-control" type="password" name="password2" id="password2" maxlength="10" />
    <span class="error"> <?php echo $password2Error;?> </span> </div>

    <input type="hidden" name="email" value="<?php echo $email ?>">

    <input type="hidden" name="utype" value="2" />
    <input class="btn btn-default" type="submit" name="submit" value="Reset Password" />
    </fieldset>
    </form>

    
    <span class="message"> <?php echo $message ?> </span>

<?php else: ?>

    <span class="message"> <?php echo $message ?> </span>
    <a href="login.php"> <?php echo $loginmessage ?> </a>

<?php endif; ?>



</body>