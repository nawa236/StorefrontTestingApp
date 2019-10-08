<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


    <title>Product List</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
  <div class="flex-grid">
    <aside class="col sidebar">
      <h2>Sidebar</h2>


  <h3> Price:</h3>
  <select class="dropdown select price" id="optionPrice">
     <option value="All">All</option>
     <option value="Low">Below $4</option>
     <option value="Med">$4 - $8</option>
     <option value="High">Above $8</option>
   </select>

<br>
<br>

<h3>Food Type:</h3>
  <input type="radio" name="food" class="select foodType" id="radioAllFood" value="All"  checked > All <br>
  <input type="radio" name="food" class="select foodType" id="radioEntree" value="Entree" > Entree<br>
  <input type="radio" name="food" class="select foodType" id="radioDessert" value="Dessert"> Dessert<br>
  <input type="radio" name="food" class="select foodType" id="radioBreakfast" value="Breakfast"> Breakfast<br>

    </aside>
    <section class="col main">
      <h2>The Product List</h2>
          <div id="wrapper" class="filter">
      </div>
    </div>
    </section>
  </div>
</div>

<script>
var food = "All";
var price = "All";
$(document).ready(function(){
	filter();

	function filter(){
	$.ajax({
            url:"ProductCards.php",
            method:"POST",
            data:{food:food, price:price},
            success:function(data){
                $('.filter').html(data);
            }
        });
	}

    $('.select').click(function(){
	var changed = false;
	var tempPrice = $("#optionPrice :selected").val();
	if(tempPrice != price){
		price = tempPrice;
		changed = true;
	}
	var tempFood = $("input[name=food]:checked").val()

	if(tempFood != food){
		food=tempFood;
		changed = true;
	}
	
	if(changed)
		filter();

    });
});
</script>
</body>
</html>
