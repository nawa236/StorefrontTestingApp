<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

  <?php
  function makeCard($image,$name,$price,$description) {
      echo "<div class=\"card\">";
      echo "<img src=$image alt=$name style=\"width:100%\">";
      echo "<h1>$name</h1>";
      echo "<p class=\"price\">$price</p>";
      echo "<p>$description</p>";
      $id = "button" . $name;
      echo "<p><button id=$id>Configure</button></p>";
      echo "</div>";   }
  ?>
  
<div class="container">
  <div class="flex-grid">
    <aside class="col sidebar">
      <h2>Sidebar</h2>      
      
      <div class="dropdown">
      <button class="dropbtn">Prices</button>
      <div class="dropdown-content">
        <a href="#">Below $3</a>
        <a href="#">$3 - $6</a>
        <a href="#">Above $6</a>
  </div>
</div>
<br>
<br>

<h3>Food Type:</h3>
  <input type="radio" name="food"> Entree<br>
  <input type="radio" name="food"> Dessert<br>
  <input type="radio" name="food"> Breakfast<br>  

    </aside>
    <section class="col main">
      <h2>The Product List</h2>
          <div id="wrapper">
            <?php
     	 	makeCard("https://cdn.discordapp.com/attachments/539230457070354461/630130582848012319/burger.jpg","Hamburger","$5.99","Best burger in the buisness. Probably.");
            makeCard("https://cdn.discordapp.com/attachments/539230457070354461/630130585561595923/cake.jpg","Chocolate Cake","$12.99","This cake is not a lie.");
            makeCard("https://cdn.discordapp.com/attachments/539230457070354461/630130586585006080/cereal.jpg","Cereal","$1.50","It's ok to be a cereal killer. I wont tell.");
            makeCard("https://cdn.discordapp.com/attachments/539230457070354461/630130588086566912/chicken.jpg","Fried Chicken","$6.32","At most 25% cardboard.");
            makeCard("https://cdn.discordapp.com/attachments/539230457070354461/630130589155983370/pizza.jpg","Pizza","$103.99","Bet you're curious why it's so expensive.");
            makeCard("https://cdn.discordapp.com/attachments/539230457070354461/630130590879842357/spaghetti.jpg","Spaghetti","$6.29","Fork not included.");
            makeCard("https://cdn.discordapp.com/attachments/539230457070354461/630130591966298134/taco.jpg","Tacos","$-5.00","I fired the person who set this price.");
            makeCard("https://cdn.discordapp.com/attachments/539230457070354461/630130594466103306/waffle.jpg","Not Pancakes","$3.99","Deformed pancakes need love too.");
            ?>
      </div>  
    </div>
    </section>
  </div>
</div>

</body>
</html>
