<?php
    include("database.php");
    $db = new Database();

    $name = addslashes($_POST["name"]);
    $area = addslashes($_POST["functional_area"]);
    $desc = addslashes($_POST["description"]);
    $code = addslashes($_POST["codeblock"]);
    $success = -1;
    $result = array();

    $select = "SELECT * FROM bug WHERE name=$name AND functional_area=$area";
    $count = $db->runQuery($select);
    if($count > 0) {
        echo "Bug $name already exists for area $area.";
    }else{
        $insert = "INSERT INTO bug (name, functional_area, description, codeblock) VALUES ('$name', '$area', '$desc', '$code')";
        $success = $db->runInsert($insert);
        $select = "SELECT id, name FROM bug WHERE name='" . $_POST['name'] . "' AND functional_area='" . $_POST['functional_area'] . "';";
        $result = $db->runDataQuery($select);
    }

    if($success > 0){
        echo json_encode($result);
    }else{
        echo $success . " - " . $insert . "\r\n";
        echo "Error in saving bug to database.";
    }
?>