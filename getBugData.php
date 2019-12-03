<?php
    include("database.php");
    $db = new Database();

    $id = $_POST["id"];
    $select = "SELECT * FROM bug WHERE id=$id";
    $results = $db->runDataQuery($select);
    if($results != ""){
        echo json_encode($results);
    }else{
        echo "Error in retrieving bug $id from database.";
    }
?>