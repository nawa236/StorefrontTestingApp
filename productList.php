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
<div class="sidebarContents">


<!-- Sorting options -->
<h3> Sort By </h3>
<select class="dropdown select sort" id="optionSort">
     <option value="name Asc">Name: A-Z</option>
     <option value="name Desc">Name: Z-A</option>
     <option value="price Asc">Price: Asc</option>
     <option value="price Desc">Price: Desc</option>
   </select>
<br><br>

<!-- Filtering Options -->
<h3> Filters </h3>
<fieldset>
<legend><b>Price</b></legend>

<!-- Price slider bars -->
<div class="slidecontainer">
  <p>Min: $<input type="number" id="minBox" value="0" min="0" max="150" onkeypress="return event.charCode >=48" style="width: 45px"></p>
  <input type="range" min="0" max="150" value="0" class="slider" id="minPriceSlider" oninput="updateMin(this.value)" onchange="finalizeMin(this.value)">

  <p>Max: $<input type="number" id="maxBox" value="150" min="0" max="150" onkeypress="return event.charCode >=48" style="width: 45px"></p>
  <input type="range" min="0" max="150" value="150" class="slider" id="maxPriceSlider" oninput="updateMax(this.value)" onchange="finalizeMax(this.value)">
</div>

</fieldset>
<br>

<!-- Product Category Buttons -->
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
      <h2>Product List</h2>
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

// Append event triggers on text input boxes for price sliders
document.getElementById("minBox").onchange = function() {moveSliderMin(document.getElementById('minBox').value)};
document.getElementById("maxBox").onchange = function() {moveSliderMax(document.getElementById('maxBox').value)};

// Visualize changes to min slider
function moveSliderMin(newVal){
    //*****  Bug 4 Start ****//
    var bugCode = "<?php echo bug_check(4);?>";
    	if(bugCode != "")
	    eval(bugCode);
    //*****   Bug 4 End  ****//

    if(newVal == minPrice)
	return;
    if(newVal > 150){
	newVal = 150;
	document.getElementById('minBox').value = 150;
    }
    document.getElementById('minPriceSlider').value = newVal;
    finalizeMin(newVal);
}

// Visualize changes to max slider
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

// Finalizes values and calls to rebuild products on display
function finalizeMin(newVal){
    minPrice = newVal;
    updateMin(newVal);
    maxPrice = document.getElementById('maxPriceSlider').value;
    filter();
}

// Finalizes values and calls to rebuild products on display
function finalizeMax(newVal){
    //*****  Bug 8 Start ****//
    var bugCode = "<?php echo bug_check(8);?>";
    if(bugCode == "")
        maxPrice = newVal;
    //*****   Bug 8 End  ****//

    updateMax(newVal);
    minPrice = document.getElementById('minPriceSlider').value;
    filter();
}

// Controls display of max based on input of min (min should not be greater than max)
function updateMin(newVal){
    document.getElementById('minBox').value=newVal;
    if(newVal >= Number(document.getElementById('maxPriceSlider').value)){
	document.getElementById('maxPriceSlider').value = Number(newVal);
        document.getElementById('maxBox').value=Number(newVal);
     }
}

// Controls display of min based on input of max (max should not be less than min)
function updateMax(newVal){
    document.getElementById('maxBox').value=newVal;
    if(newVal <= Number(document.getElementById('minPriceSlider').value)){
    	document.getElementById('minPriceSlider').value = Number(newVal);
    	document.getElementById('minBox').value=Number(newVal);
    }
}

// Rebuilds the products on display
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

    // Process search request that come from searchs made from other pages
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

    // When any select class button is clicked, check to see if the value has changed
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

	// If changed, rebuild the displayed elements
	if(changed){
		filter();
	}
    });

    // Accept enter keypress to submit price on min input box
    $("#minBox").keypress(function(){
  	if ( event.which == 13 ) {
    	    moveSliderMin(document.getElementById('minBox').value);
        }
    });

     // Accept enter keypress to submit price on max input box
     $("#maxBox").keypress(function(){
        if ( event.which == 13 ) {
            moveSliderMax(document.getElementById('maxBox').value);
        }
    });

});
</script>
</body>
</html>
