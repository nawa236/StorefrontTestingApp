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

$emailError = "";
$passwordError = "";
$password2Error = "";
$duplicateError = "";
$regMessage = "";

if($connection -> connect_error){
	echo "Error connecting to database - " + $connection->connect_error;
}

if($_SERVER["REQUEST_METHOD"] == "POST") {

    
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

        $username = trim($_POST['email']);
        // Regular expression from https://code.tutsplus.com/tutorials/how-to-implement-email-verification-for-new-members--net-3824
        if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $username)) {
            $emailError = "Email must be in a valid format such as xxxx@yyyy.com";
        }
        else {
            $usernamecheck = "SELECT * FROM  authentication WHERE email = '" . $username . "'"; 
            $usernamecheckquery = mysqli_query($connection, $usernamecheck);
            $usernamecheckcount = mysqli_num_rows($usernamecheckquery);

            if ($usernamecheckcount == 0) {

                $password = trim($_POST['password']);
                $hashpassword = password_hash($password, PASSWORD_DEFAULT);
                $currentusers = mysqli_query($connection, "SELECT COUNT(*) FROM authentication");
                $testid = mysqli_fetch_assoc($currentusers)["COUNT(*)"] + 1;
                $regHash = md5(rand (0,1000));
                $query = "INSERT INTO authentication(id, password, email, type, hash, status) values ('$testid', '$hashpassword', '$username', '0', '$regHash', '0')";
                $result = mysqli_query($connection, $query);

                if ($result == 1) {

                    $regMessage = "Registration successful! Please verify your account by clicking the activation link that has been sent to your email.";
                    $to = $username;
                    $subject = 'Trissential Bug Website Account Verification';
                    $message = '
                    
                    <html>

                    <body>

                    <p>Please click the link below to verify your email to finish your account creation on the Trissential bug website.</p> <br>
                    <a href="localhost/storefront/verify.php?email='.$username.'&hash='.$regHash.'">Verify your account</a>

                    </body>

                    </html>

                    ';

                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= 'from:trissentialbugsite@gmail.com' . "\r\n";
                    mail($to, $subject, $message, $headers);
                }
                else {
                    echo "<script type='text/javascript'>alert('There was an error with your registration. Please contact the administrator if these issues persist.'); </script>";
                    $regMessage = "There was an error with your registration. Please contact the administrator if these issues persist.";
                }
            }
            else
            {
                $duplicateError = "There is already an account with that email. Forgot password? Well, too bad. I haven't implemented that page yet.";
            }
        }
    }
}


?>

<form id="register_form" action="register.php" method="post">
<fieldset>
<legend>Sign Up</legend>
<div class="form-group">	
<label for="email">Email: </label>
<input class="form-control" type="text" name="email" id="email" maxlength="50" />
<span class="error"> <?php echo $emailError;?> </span> </div>

<div class="form-group">
<label for="password">Password: </label>
<input class="form-control" type="password" name="password" id="password" maxlength="10" />
<span class="error"> <?php echo $passwordError;?> </span> </div>

<div class="form-group">
<label for="password">Retype Password: </label>
<input class="form-control" type="password" name="password2" id="password2" maxlength="10" />
<span class="error"> <?php echo $password2Error;?> </span> </div>

<input type="hidden" name="utype" value="2" />
<input class="btn btn-default" type="submit" name="submit" value="Sign me up!" />
</fieldset>
</form>

<span class="message"> <?php echo $regMessage ?> </span>
<span class="error"> <?php echo $duplicateError ?> </span>


</body>

</html>