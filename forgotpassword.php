<!DOCTYPE html>

<?php


// set error handling
ini_set('display_errors',1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Database connection
require('dbConnect.php');

$forgotMessage = "";
$emailError = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST['email'])) {
        $emailError = "Email is required";
    }
    else {
        $username = trim($_POST['email']);
        $usernamecheck = "SELECT * FROM  authentication WHERE email = '" . $username . "'"; 
        $usernamecheckquery = mysqli_query($connection, $usernamecheck);
        $usernamecheckcount = mysqli_num_rows($usernamecheckquery);
        if ($usernamecheckcount == 0) {

            $accountError = "No user found with that email.";
        }
        else {
            $forgotHash = md5(rand (0,1000));
            $forgothashquery = "UPDATE authentication SET hash = '$forgotHash' WHERE email = '" . $username . "'";
            $forgotresult = mysqli_query($connection, $forgothashquery);
            if ($forgotresult == 1) {
                $forgotMessage = "A verification link has been sent to your email, please use it to reset your password.";
                $to = $username;
                $subject = 'Trissential Bug Website Forgot Password';
                $message = '
                
                <html>

                <body>

                <p>Please click the link below to reset your password</p> <br>
                <a href="localhost/storefront/verifyforgotpassword.php?email='.$username.'&hash='.$forgotHash.'">Reset Password</a>

                </body>

                </html>

                ';

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'from:trissentialbugsite@gmail.com' . "\r\n";
                mail($to, $subject, $message, $headers);
            } 
        }
    }
    


}



?>


<head>
<link rel="stylesheet" href="styles.css">
</head>

<body>

<form id="forgot_password_form" action="forgotpassword.php" method="post">
<fieldset>
<legend>Forgot Password</legend>
<div class="form-group">	
<label for="email">Email: </label>
<input class="form-control" type="text" name="email" id="email" maxlength="50" />
<span class="error"> <?php echo $emailError;?> </span> </div>

<input type="hidden" name="utype" value="2" />
<input class="btn btn-default" type="submit" name="submit" value="Send Verification Email" />
</fieldset>
</form>

<span class="message"> <?php echo $forgotMessage ?> </span>

</body>