<?php
$priceFlag;
if($_POST["price"] == "All")
	$priceFlag = 0;
if($_POST["price"] == "Low")
        $priceFlag = 1;
if($_POST["price"] == "Med")
        $priceFlag = 2;
if($_POST["price"] == "High")
        $priceFlag = 3;
$foodFlag = $_POST["food"];



function makeCard($image,$name,$price,$description,$foodType) {
      if($GLOBALS['priceFlag'] == 1 && $price >= 4)
	return;
      if($GLOBALS['priceFlag'] == 2 && ($price < 4 || $price >8))
        return;
      if($GLOBALS['priceFlag'] == 3 && $price <= 8)
        return;
      if($GLOBALS['foodFlag'] != "All" && $GLOBALS['foodFlag'] != $foodType)
	return;

      echo "<div class=\"card\">";
      echo "<img src=$image alt=$name style=\"width:100%\">";
      echo "<h1>$name</h1>";
      $formattedPrice = number_format ($price,2); 
      echo "<p class=\"price\">\$$formattedPrice</p>";
      echo "<p>$description</p>";
      $id = "button" . $name;
      echo "<p><button id=$id>Configure</button></p>";
      echo "</div>";
 }

            makeCard("https://cdn.discordapp.com/attachments/539230457070354461/630130582848012319/burger.jpg","Hamburger",5.99,"Best burger in the buisness. Probably.","Entree");
            makeCard("https://cdn.discordapp.com/attachments/539230457070354461/630130585561595923/cake.jpg","Chocolate Cake",12.99,"This cake is not a lie.","Dessert");
            makeCard("https://cdn.discordapp.com/attachments/539230457070354461/630130586585006080/cereal.jpg","Cereal",1.50,"It's ok to be a cereal killer. I wont tell.","Breakfast");
            makeCard("https://cdn.discordapp.com/attachments/539230457070354461/630130588086566912/chicken.jpg","Fried Chicken",6.32,"At most 25% cardboard.","Entree");
            makeCard("https://cdn.discordapp.com/attachments/539230457070354461/630130589155983370/pizza.jpg","Pizza",103.99,"Bet you're curious why it's so expensive.","Entree");
            makeCard("https://cdn.discordapp.com/attachments/539230457070354461/630130590879842357/spaghetti.jpg","Spaghetti",6.29,"Fork not included.","Entree");
            makeCard("https://cdn.discordapp.com/attachments/539230457070354461/630130591966298134/taco.jpg","Tacos",-5.00,"I fired the person who set this price.","Entree");
            makeCard("https://cdn.discordapp.com/attachments/539230457070354461/630130594466103306/waffle.jpg","Not Pancakes",3.99,"Deformed pancakes need love too.","Breakfast");


?>
