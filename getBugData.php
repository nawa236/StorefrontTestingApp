<?php
    include("database.php");
    $db = new Database();

    $id = $_POST["id"];
    $select = "SELECT * FROM bug WHERE id=$id";
    $result = $db->runDataQuery($select);
    $datalist = "";
    if($result != ""){
        foreach($result as $r){
            $datalist .= $r . "|";
        }
        echo $datalist;
    }else{
        echo "Error in retrieving bug $id from database.";
    }
?>