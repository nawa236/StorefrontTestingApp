kltttttttt'<!DOCTYPE html>

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
$accountError = "";
$cookiecheck = "";

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
                echo "<script type='text/javascript'>alert('You are now logged in'); window.location = 'ProductList.php';</script>";
                //header("Location: ProductList.php");
            }
            else {
                $accountError = "Incorrect password";
            }



        }
    }
}
?>

<head>
<link rel="stylesheet" href="styles.css">
</head>

<body>

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

<span class="error"> <?php echo $accountError; ?> </span>
<span class="error"> <?php echo $cookiecheck; ?> </span>




</body>