<?php
  include('dbConnect.php');
  $cookie_name1 = "TriStorefrontUser";
    if(!isset($_COOKIE[$cookie_name1])){
        header("Location: ./login.php");
    }else{
        $query = "SELECT type FROM authentication WHERE id=" . $_COOKIE[$cookie_name1] . ";";
        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $accounttype = $result[0]['type'];
        if($result[0]['type'] != 1){
            header("Location: ./login.php");
        }
    }
?>