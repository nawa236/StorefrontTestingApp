<?php
    include("database.php");
    $db = new Database();
    $id = $_POST["id"];
    $success = -1;
    $query = "";

    $select = "SELECT * FROM assignment WHERE id=$id";
    $count = $db->runQuery($select);
    if($count > 0) {
            $query = "DELETE FROM assignment WHERE id=$id";
            $success = $db->runUpdate($query);
    }else{
        echo "Bug Assignment $id was not found in database.";
    }

    if($success > 0){
        echo "Save to database was successful.";
    }else{
        echo $success . " - " . $query . "\r\n";
        echo "Error in saving assignment to database.";
    }
?>