<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<?php
  include('bugCheck.php');
  include('dbConnect.php');
  $accountName = "";
  $cookie_name1 = "TriStorefrontUser";
  $cookie_name2 = "TriStorefrontName";
  if(!isset($_COOKIE[$cookie_name1]))
        setcookie("TriStorefrontUser", 1 , time()+3600, '/');
  if(!isset($_COOKIE[$cookie_name2])){
        $query = "SELECT fname FROM customer WHERE id=" . $_COOKIE[$cookie_name1] . ";";
        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $newName = $result[0]['fname'];
        setcookie("TriStorefrontName", $newName, time()+3600, '/');
        $accountName = $newName;
  }
  else
        $accountName = $_COOKIE[$cookie_name2];


//*****  Bug 6 Start ****//
$bugCode = bug_check(6);
if(!is_null($bugCode)) 
  eval($bugCode);
//*****   Bug 6 End  ****//
?>

<div class="topnav">
  <a class="active" id="buttonHome" href="./productList.php">Home</a>
  <a id="buttonCart" href="./cart.php">Cart</a>
  <a id="buttonOrderHistory" href="./orderHistory.php">Orders</a>
  <div class="drop">
    <a id="buttonAccount-Head" class="dropbtn"><?php echo "Hello, $accountName"?></a>
    <div class="drop-content">
      <a id="buttonAccount-Sub" href="#account">View Account</a>
      <a id="buttonLogout" href="./login.php" onclick="logout()" > Logout </a>
    </div>
  </div>

  <div class="search-container">
      <input type="text" class="textbox" placeholder="Search.." name="search" id="searchBox">
      <button id="buttonSearchBox"><i class="fa fa-search"></i></button>
  </div>
</div>

<script>
    $("#buttonSearchBox").click(function(){
	searchSubmit();
    });

    $("#searchBox").keypress(function(){
  	if ( event.which == 13 ) {
	    //*****  Bug 3 Start ****//
	    var bug3Code = "<?php echo bug_check(3);?>";
	    if(bug3Code == "")
 	        searchSubmit();
	    //*****   Bug 3 End  ****//
        }
    });

    function searchSubmit(){
   	searchInput = document.getElementById("searchBox").value;
	window.location.href='./productList.php?search=' + searchInput;
    }

    function logout(){
	document.cookie = "TriStorefrontUser=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "TriStorefrontName=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    }
</script>
</body>
</html>
