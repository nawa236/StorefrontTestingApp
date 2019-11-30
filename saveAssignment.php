<?php
    include("database.php");
    $db = new Database();

    $bugid = $_POST["bugid"];
    $userid = $_POST["userid"];
    $sdate = $_POST["sdate"];
    $insert = "";
    $success = -1;

    if(isset($_POST["edate"]) === false){
        $select = "SELECT * FROM assignment WHERE bugid=$bugid AND userid=$userid";
        $count = $db->runQuery($select);
        if($count > 0) {
            echo "Bug $bugid already assigned to userid $userid.";
        }else{
            $insert = "INSERT INTO assignment (bugid, userid, sdate) VALUES ($bugid, $userid, '$sdate')";
            $success = $db->runInsert($insert);
        }
    }else{
        $edate = $_POST["edate"];
        $select = "SELECT * FROM assignment WHERE bugid=$bugid AND userid=$userid";
        $count = $db->runQuery($select);
        if($count > 0) {
            echo "Bug $bugid already assigned to userid $userid.";
        }else{
            $insert = "INSERT INTO assignment (bugid, userid, sdate, edate) VALUES ($bugid, $userid, '$sdate', '$edate')";
            $success = $db->runInsert($insert);
        }
    }

    if($success > 0){
        echo "Save to database was successful.";
    }else{
        echo $success . " - " . $insert . "\r\n";
        echo "Error in saving assignment to database.";
    }
?>