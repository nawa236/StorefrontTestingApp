<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <title>Product List</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class="topnav">
  <a class="active" href="./productList.php">Home</a>
  <a href="#cart">Cart</a>
  <a href="./orderHistory.php">Orders</a>
  <a class="account" href="#account">Account</a>

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
    	    searchSubmit(); 
        }
    });

    function searchSubmit(){
   	searchInput = document.getElementById("searchBox").value;
	window.location.href='./productList.php?search=' + searchInput;
    }


</script>
</body>
</html>
