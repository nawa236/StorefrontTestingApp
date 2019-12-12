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

// Initialize dynamic text variables
$emailError = "";
$passwordError = "";
$password2Error = "";
$duplicateError = "";
$regMessage = "";

// Check if connection to DB was successfull
if($connection -> connect_error){
	echo "Error connecting to database - " + $connection->connect_error;
}

// If the user send in form data/there was a POST request
if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if information is present
    if (empty($_POST['email']) && empty($_POST['password'])) {
        $emailError = "Email is required";
        $emailError = "password is required";

    }
    else if (empty($_POST['email'])) {
        $emailError = "Email is required";
    }
    else if (empty($_POST['password'])) {
        $passwordError = "Password is required";
    }
    else if ($_POST['password'] != $_POST['password2']) {
        $password2Error = "Passwords must match";
    }
    else {
        // Get email from request
        $username = trim($_POST['email']);
        // Check if email is in a correct format
        // Regular expression from https://code.tutsplus.com/tutorials/how-to-implement-email-verification-for-new-members--net-3824
        if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $username)) {
            $emailError = "Email must be in a valid format such as xxxx@yyyy.com";
        }
        // If email is in correct format
        else {
            // Check if there is an account already in the DB with that email
            $usernamecheck = "SELECT * FROM  authentication WHERE email = '" . $username . "'"; 
            $usernamecheckquery = mysqli_query($connection, $usernamecheck);
            $usernamecheckcount = mysqli_num_rows($usernamecheckquery);
            if ($usernamecheckcount == 0) {
                // Get password from request, hash it, create a hash for the verification link, and insert into the table.
                $password = trim($_POST['password']);
                $hashpassword = password_hash($password, PASSWORD_DEFAULT);
                $regHash = md5(rand (0,1000));
                $query = "INSERT INTO authentication(password, email, type, hash, status) values ('$hashpassword', '$username', '0', '$regHash', '0')";
                $result = mysqli_query($connection, $query);
                // If DB call is successful, send user an email with the verification link.
                if ($result == 1) {

                    $regMessage = "Registration successful! Please verify your account by clicking the activation link that has been sent to your email.";
                    $to = $username;
                    $subject = 'Trissential Bug Website Account Verification';
                    $message = '
                    
                    <html>

                    <body>

                    <p>Please click the link below to verify your email to finish your account creation on the Trissential bug website.</p> <br>
                    <a href="40.71.228.49/storefront/verify.php?email='.$username.'&hash='.$regHash.'">Verify your account</a>

                    </body>

                    </html>

                    '; // EDIT URL BASED ON EVENTUAL SITE LOCATION

                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= 'from:trissentialbugsite@gmail.com' . "\r\n";   // WILL NEED TO BE CHANGED DEPENDING ON DESIRED USAGE
                    mail($to, $subject, $message, $headers);
                }
                // Else, there was an error with the DB call. Inform the user of an error.
                else {
                    echo "<script type='text/javascript'>alert('There was an error with your registration. Please contact the administrator if these issues persist.'); </script>";
                    $regMessage = "There was an error with your registration. Please contact the administrator if these issues persist.";
                }
            }
            else
            {
                $duplicateError = "There is already an account with that email. Forgot password? Please use the link below the form to reset your password!";
            }
        }
    }
}


?>
<h2 class="form-header"> Sign Up </h2>
<form class= "form-style-9" id="register_form" action="register.php" method="post">

<ul>
<li>
<div class="form-group">	
<label for="email">Email </label> <br>
<input class="field-style field-full align-none" type="text" name="email" id="email" maxlength="50" placeholder="Email" />
<span class="error"> <?php echo $emailError;?> </span> </div>
</li>

<li>
<div class="form-group">
<label for="password">Password </label> <br>
<input class="field-style field-full align-none" type="password" name="password" id="password" maxlength="10" placeholder="Password"/>
<span class="error"> <?php echo $passwordError;?> </span> </div>
</li>

<li>
<div class="form-group">
<label for="password">Retype Password </label> <br>
<input class="field-style field-full align-none" type="password" name="password2" id="password2" maxlength="10" placeholder="Retype Password"/>
<span class="error"> <?php echo $password2Error;?> </span> </div>
</li>

<li>
<input type="submit" name="submit" value="Sign me up!" />
</li>

</form>

<h4 class="under-form-error"> <?php echo $duplicateError ?> </h4>
</ul>

<p class="under-form"> <a href="forgotpassword.php"> Forgot your password? Click here! </a> </p>
<p class="under-form"> <a href="login.php"> Already have an account? Click here to login! </a> </p>

<h2 class="under-form"> <?php echo $regMessage ?> </h2>
<h4 class="under-form-error"> <?php echo $duplicateError ?> </h4>


</body>

</html>
