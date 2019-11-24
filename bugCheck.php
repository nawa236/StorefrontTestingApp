<?php
// Given a bug id, check to see if that bug is set to active for the current user
// Return codeblock (Null if not active)
function bug_check($bug_id){
  include('dbConnect.php');
  $user_id = $_COOKIE["TriStorefrontUser"];
  $query = "SELECT * FROM bug,assignment WHERE bug.id=bugid  AND (edate > now() OR edate is Null) AND bugid = $bug_id AND userid = $user_id;";
  $statement = $connect->prepare($query);
  $statement->execute();
  $result = $statement->fetchAll();
  return $result[0]['codeblock'];
}
?>
