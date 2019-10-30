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
<?php require('header.php'); ?>

<div class="container">
  <div class="flex-grid">
    <aside class="col sidebar">
      <h2>Sidebar</h2>
<div class="sidebarContents">

  <h3> Price:</h3>
  <select class="dropdown select price" id="optionPrice">
     <option value="All">All</option>
     <option value="Low">Below $4</option>
     <option value="Med">$4 - $8</option>
     <option value="High">Above $8</option>
   </select>

<br>
<br>

<h3>Catagory:</h3>
  <input type="radio" name="cat" class="select category" id="radioAllCategories" value="All"  checked > All <br>
  <input type="radio" name="cat" class="select category" id="radioPaper" value="Paper" > Paper<br>
  <input type="radio" name="cat" class="select category" id="radioEnvelopes" value="Envelopes"> Envelops<br>
  <input type="radio" name="cat" class="select category" id="radioBoxes" value="Boxes"> Boxes<br>
  <input type="radio" name="cat" class="select category" id="radioLables" value="Labels"> Labels<br>
  <input type="radio" name="cat" class="select category" id="radioClothing" value="Clothing"> Clothing<br>
  <input type="radio" name="cat" class="select category" id="radioMisc" value="Misc"> Misc<br>

</div>

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
var category = "All";
var price = "All";
var searchInput = "";
$(document).ready(function(){
let searchParams = new URLSearchParams(window.location.search)
	if( searchParams.has('search')){
	let param = searchParams.get('search')
		if( document.getElementById("searchBox").value != param){
			searchInput = param;
			document.getElementById("searchBox").value = searchInput;
		}
        searchInput = document.getElementById("searchBox").value;
	}
	else
		document.getElementById("searchBox").value = "";
	filter();
	function filter(){
	$.ajax({
            url:"filterProducts.php",
            method:"POST",
            data:{category:category, price:price,searchInput:searchInput},
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
	var tempCat = $("input[name=cat]:checked").val()

	if(tempCat != category){
		category=tempCat;
		changed = true;
	}

	if(changed){
		filter();
	}

    });


});
</script>
</body>
</html>
