<?php

include("database.php");
$db = new Database();
$db->buildDatabase();
echo "<h2>Welcome to the Employee Store.</h2>";
sleep(5);
header("Location: ./login.php");

?>
