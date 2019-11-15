<?php
$servername = 'localhost';
$username = 'root';
$password = 'TriBugApp'; // use your own username and password for the server.
$dbversion = 0.1;
$dbname = 'EmployeeTraining';

$connection = new mysqli($servername, $username, $password, $dbname);
$connect = new PDO("mysql:host=$servername;dbname=$dbname", $username , $password);
?>
