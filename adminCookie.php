<?php
  include('dbConnect.php');
  $accountName = "";
  $cookie_name1 = "TriStorefrontUser";
  $cookie_name2 = "TriStorefrontName";
  if(!isset($_COOKIE[$cookie_name1]))
        header("Location: ./login.php");

  // If name is not stored in a cookie, query the name of the active user ID
  if(!isset($_COOKIE[$cookie_name2])){
        $query = "SELECT fname, type FROM customer WHERE id=" . $_COOKIE[$cookie_name1] . ";";
        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $newName = $result[0]['fname'];
        if($result[0]['type'] != 1){
            header("Location: ./login.php");
        }
        setcookie("TriStorefrontName", $newName, time()+3600, '/');
        $accountName = $newName;
  }
  else
        $accountName = $_COOKIE[$cookie_name2];

  $id = $_COOKIE[$cookie_name1];
  $query = "SELECT * FROM authentication WHERE id=$id;";
  $statement = $connect->prepare($query);
  $statement->execute();
  $result = $statement->fetchAll();
  $accounttype = 0;
  $accounttype = $result[0]["type"];
  ?>