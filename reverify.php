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
$errmessage = "";
$emailError = "";


// If there was a POST request (user sent input from form trying to change password)
if($_SERVER["REQUEST_METHOD"] == "POST") {

    // If the password prompt is empty, inform user.
    if (empty($_POST['email'])) {
        $emailError = "Email is required";
    }
    // Else, reset password
    else {

        // Get input from POST
        $email = $_POST["email"];
        // Check for auth info in DB and set new hash and email user with new hash
        $emailcheckquery = "SELECT * FROM  authentication WHERE email = '" . $email . "'"; 
        $emailcheckresult = mysqli_query($connection, $emailcheckquery);
        $emailcheckcount = mysqli_num_rows($emailcheckresult);
        if ($emailcheckcount == 1) {
            $regHash = md5(rand (0,1000));
            $hashquery = "UPDATE authentication SET hash = '$regHash' WHERE email = '$email'";
            $hashresult = mysqli_query($connection, $hashquery);
            if ($hashresult == 1) {
                
                $message = "Verification link has been resent! Please verify your account by clicking the activation link that has been sent to your email.";
                $to = $email;
                $subject = 'Trissential Bug Website Account Verification';
                $message = '
                    
                <html>

                <body>

                <p>Please click the link below to verify your email to finish your account creation on the Trissential bug website.</p> <br>
                <a href="40.71.228.49/storefront/verify.php?email='.$email.'&hash='.$regHash.'">Verify your account</a>

                </body>

                </html>

                ';
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'from:trissentialbugsite@gmail.com' . "\r\n"; // WILL NEED TO BE CHANGED DEPENDING ON DESIRED USAGE
                mail($to, $subject, $message, $headers);


            }
            else {
                $errmessage = "Reverification could not be sent. Please try again and contact your administrator if the issue persists.";
            }
        }


    }
}

?>
<h2 class="form-header"> Please enter email to resend verification </h2>
<form class= "form-style-9" id="reverify_form" action="reverify.php" method="post">
<ul>
<li>
<div class="form-group">	
<label for="email">Email </label> <br>
<input class="field-style field-full align-none" type="text" name="email" id="email" maxlength="50" placeholder="Email"/>
<span class="error"> <?php echo $emailError;?> </span> </div>
</li>

<li>
<input type="submit" name="submit" value="Resend verification email" />
</li>
</ul>
</form>

<p class="under-form-error"> <?php echo $errmessage ?> </p>





</body>