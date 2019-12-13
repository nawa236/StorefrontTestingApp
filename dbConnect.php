<?php
// Centralized database connection setup, most other files include this file.

$servername = 'localhost';
$username = 'root';
$password = 'TriBugApp';
$dbversion = 0.1;
$dbname = 'EmployeeTraining';

$connection = new mysqli($servername, $username, $password, $dbname);
$connect = new PDO("mysql:host=$servername;dbname=$dbname", $username , $password);
?>
