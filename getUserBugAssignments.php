<?php
    include("database.php");
    $db = new Database();

    $id = $_POST["userid"];

    $select = "select a.id, b.name, a.sdate, a.edate from assignment a, bug b where a.bugid = b.id and a.userid = $id order by sdate;";
    $results = $db->runDataQuery($select);
    if($results != ""){
        echo json_encode($results);
    }else{
        echo "Error in retrieving bugs for user $id from database.";
    }

?>