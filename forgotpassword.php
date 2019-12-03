<!DOCTYPE html>

<?php


// set error handling
ini_set('display_errors',1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Database connection
require('dbConnect.php');
// Initialize variables used for dynamic text/information
$forgotMessage = "";
$emailError = "";

// If the user has form input/there is a POST request
if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if email is empty, inform user if so
    if (empty($_POST['email'])) {
        $emailError = "Email is required";
    }
    else {
        // Put email into a variable and check if that email has an account
        $username = trim($_POST['email']);
        $usernamecheck = "SELECT * FROM  authentication WHERE email = '" . $username . "'"; 
        $usernamecheckquery = mysqli_query($connection, $usernamecheck);
        $usernamecheckcount = mysqli_num_rows($usernamecheckquery);
        if ($usernamecheckcount == 0) {

            $accountError = "No user found with that email.";
        }
        // If that email has an account, create a hash and send a forgot password email to the user
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
                <a href="40.71.228.49/StorefrontTestingApp/verifyforgotpassword.php?email='.$username.'&hash='.$forgotHash.'">Reset Password</a>

                </body>

                </html>

                ';

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'from:trissentialbugsite@gmail.com' . "\r\n";   // WILL NEED TO BE CHANGED DEPENDING ON DESIRED USAGE
                mail($to, $subject, $message, $headers);
            } 
        }
    }
    


}



?>


<head>
<link rel="stylesheet" href="loginregstyles.css">
</head>

<body>
<h2 class="form-header">Forgot Password</h2>
<form class="form-style-9" id="forgot_password_form" action="forgotpassword.php" method="post">
<ul>
<li>
<div class="form-group">	
<label for="email">Email: </label>
<input class="field-style field-full align-none" type="text" name="email" id="email" maxlength="50" />
<span class="error"> <?php echo $emailError;?> </span> </div>
</li>
<li>
<input type="submit" name="submit" value="Send Verification Email" />
</li>
</ul>
</form>

<p class="under-form"> <?php echo $forgotMessage ?> </p>

</body>