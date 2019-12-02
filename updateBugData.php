<?php
    include("database.php");
    $db = new Database();

    $id = $_POST["id"];
    $name = $_POST["name"];
    $area = $_POST["functional_area"];
    $desc = $_POST["description"];
    $code = $_POST["codeblock"];
    $success = -1;

    $select = "SELECT * FROM bug WHERE id=$id";
    $count = $db->runQuery($select);
    if($count == 1) {
        $update = "UPDATE bug SET name='$name', functional_area='$area', description='$desc', codeblock='$code' WHERE id=$id;";
        $success = $db->runUpdate($update);
    }else{
        echo "Bug $name could not be uniquely found by ID. Please consider saving as a new bug.";
    }

    if($success > 0){
        echo "Update was successful.";
    }else{
        echo $success . " - " . $update . "\r\n";
        echo "Error in updating bug in database.";
    }
?>