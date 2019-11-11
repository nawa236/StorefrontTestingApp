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

$verified = 0;


if($connection -> connect_error){
	echo "Error connecting to database - " + $connection->connect_error;
}

if($_SERVER["REQUEST_METHOD"] == "GET") {

    $email = $_GET["email"];
    $hash = $_GET["hash"];

    $emailcheck = "SELECT * FROM  authentication WHERE email = '" . $email . "'";
    $emailcheckquery = mysqli_query($connection, $emailcheck);
    $emailcheckcount = mysqli_num_rows($emailcheckquery);
    if ($emailcheckcount != 0) {
        $fetchedquery = mysqli_fetch_assoc($emailcheckquery);
        if ($hash == $fetchedquery["hash"]) {
            $query = "UPDATE authentication SET status = 1 WHERE email = '$email'";
            $result = mysqli_query($connection, $query);
            $idnum = $fetchedquery["id"];
            setcookie("TriStorefrontUser", $idnum, "/");
            $message = "Account verified successfully. Please click on the link below to go to the login page and login!";
            $verified = 1;
            echo "<script type='text/javascript'>alert('Please enter your information on the next page'); window.location = 'firsttime.php';</script>";
        }
        else {
            $message = "There has been an error verifying your account, please make sure you have copied the URL correctly and try again.\nIf you continue to have problems please contact an administrator";
        }
    }
    else {
        $message = "There has been an error verifying your account, please make sure you have copied the URL correctly and try again.\nIf you continue to have problems please contact an administrator";
    }


        

}
else {
    $message = "You aren't supposed to be here...";

}

?>

<h3> <?php echo $message ?> </h3>
<?php if ($verified == 1) { echo '<a href="login.php"> Click here to login </a>'; } ?>

</body>