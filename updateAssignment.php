<?php
    include("database.php");
    $db = new Database();

    $id = $_POST["id"];
    $sdate = $_POST["sdate"];
    $query = "";
    $success = -1;

    if(isset($_POST["edate"]) === false){
        $query = "UPDATE assignment SET sdate='$sdate' WHERE id=$id";
        $success = $db->runUpdate($query);
    }else{
        $edate = $_POST["edate"];
        $query = "UPDATE assignment SET sdate='$sdate', edate='$edate' WHERE id=$id";
        $success = $db->runUpdate($query);
    }

    if($success > 0){
        echo "Save to database was successful.";
    }else{
        echo $success . " - " . $query . "\r\n";
        echo "Error in saving assignment to database.";
    }
?>