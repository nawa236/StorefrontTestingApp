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

<h3> Sort By </h3>
<select class="dropdown select sort" id="optionSort">
     <option value="name Asc">Name: A-Z</option>
     <option value="name Desc">Name: Z-A</option>
     <option value="price Asc">Price: Asc</option>
     <option value="price Desc">Price: Desc</option>
   </select>
<br><br>

<h3> Filters </h3>
<fieldset>
<legend><b>Price</b></legend>
<div class="slidecontainer">
  <p>Min: $<input type="number" id="minBox" value="0" min="0" max="150" onkeypress="return event.charCode >=48" style="width: 45px"></p>
  <input type="range" min="0" max="150" value="0" class="slider" id="minPriceSlider" oninput="updateMin(this.value)" onchange="finalizeMin(this.value)">

  <p>Max: $<input type="number" id="maxBox" value="150" min="0" max="150" onkeypress="return event.charCode >=48" style="width: 45px"></p>
  <input type="range" min="0" max="150" value="150" class="slider" id="maxPriceSlider" oninput="updateMax(this.value)" onchange="finalizeMax(this.value)">
</div>

</fieldset>
<br>

<fieldset>
<legend><b>Category</b></legend>
  <input type="radio" name="cat" class="select category" id="radioAllCategories" value="All"  checked > All <br>
  <input type="radio" name="cat" class="select category" id="radioPaper" value="Paper" > Paper<br>
  <input type="radio" name="cat" class="select category" id="radioEnvelopes" value="Envelopes"> Envelopes<br>
  <input type="radio" name="cat" class="select category" id="radioBoxes" value="Boxes"> Boxes<br>
  <input type="radio" name="cat" class="select category" id="radioLables" value="Labels"> Labels<br>
  <input type="radio" name="cat" class="select category" id="radioClothing" value="Clothing"> Clothing<br>
  <input type="radio" name="cat" class="select category" id="radioMisc" value="Misc"> Misc<br>
</fieldset>
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
var minPrice = 0;
var maxPrice = 150;
var searchInput = "";
var sort = "name Asc";

document.getElementById("minBox").onchange = function() {moveSliderMin(document.getElementById('minBox').value)};
document.getElementById("maxBox").onchange = function() {moveSliderMax(document.getElementById('maxBox').value)};

function moveSliderMin(newVal){ 
    if(newVal == minPrice)
	return;
    if(newVal > 150){
	newVal = 150;
	document.getElementById('minBox').value = 150;
    }
    document.getElementById('minPriceSlider').value = newVal;
    finalizeMin(newVal);
}

function moveSliderMax(newVal){
    if(newVal == maxPrice)
        return;
    if(newVal > 150){
        newVal = 150;
        document.getElementById('maxBox').value = 150;
    }
    document.getElementById('maxPriceSlider').value = newVal;
    finalizeMax(newVal);
}

function finalizeMin(newVal){
    minPrice = newVal;
    updateMin(newVal);
    maxPrice = document.getElementById('maxPriceSlider').value;
    filter();
}

function finalizeMax(newVal){
    maxPrice = newVal;
    updateMax(newVal);
    minPrice = document.getElementById('minPriceSlider').value;
    filter();
}

function updateMin(newVal){
    document.getElementById('minBox').value=newVal;
    if(newVal >= Number(document.getElementById('maxPriceSlider').value)){
	document.getElementById('maxPriceSlider').value = Number(newVal);
        document.getElementById('maxBox').value=Number(newVal);
     }
}
function updateMax(newVal){
    document.getElementById('maxBox').value=newVal;
    if(newVal <= Number(document.getElementById('minPriceSlider').value)){
    	document.getElementById('minPriceSlider').value = Number(newVal);
    	document.getElementById('minBox').value=Number(newVal);
    }
}

function filter(){
     $.ajax({
            url:"filterProducts.php",
            method:"POST",
            data:{category:category, minPrice:minPrice, maxPrice:maxPrice, searchInput:searchInput, sort:sort},
            success:function(data){
                $('.filter').html(data);
            }
        });
    }

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

    $('.select').click(function(){
	var changed = false;
	var tempCat = $("input[name=cat]:checked").val()
	if(tempCat != category){
		category=tempCat;
		changed = true;
	}

        var tempSort = $("#optionSort :selected").val()
        if(tempSort != sort){
                sort=tempSort;
                changed = true;
        }

	if(changed){
		filter();
	}
    });

    $("#minBox").keypress(function(){
  	if ( event.which == 13 ) {
    	    moveSliderMin(document.getElementById('minBox').value);
        }
    });

     $("#maxBox").keypress(function(){
        if ( event.which == 13 ) {
            moveSliderMax(document.getElementById('maxBox').value);
        }
    });

});
</script>
</body>
</html>
