<?php
    include("database.php");
    $db = new Database();

    $name = $_POST["name"];
    $area = $_POST["functional_area"];
    $desc = $_POST["description"];
    $code = $_POST["codeblock"];
    $success = -1;

    $select = "SELECT * FROM bug WHERE name=$name AND functional_area=$area";
    $count = $db->runQuery($select);
    if($count > 0) {
        echo "Bug $name already exists for area $area.";
    }else{
        $insert = "INSERT INTO bug (name, functional_area, description, codeblock) VALUES ('$name', '$area', '$desc', '$code')";
        $success = $db->runInsert($insert);
    }

    if($success > 0){
        echo "Save to database was successful.";
    }else{
        echo $success . " - " . $insert . "\r\n";
        echo "Error in saving bug to database.";
    }
?>