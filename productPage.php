<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <title>Product Page</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>

<?php
require('header.php');
if(!isset($_GET['productID'])){
	header("Location: ./productList.php");
}


$id = $_GET['productID'];
include('dbConnect.php');
include('productCard.php');

$query = "SELECT * FROM product WHERE id = '" .$id;
$query = $query . "'";
$statement = $connect->prepare($query);
$statement->execute();
$origResult = $statement->fetchAll();
$pName = $origResult[0]['name'];
echo "<h2> Product Page for: $pName </h2>";

foreach($origResult as $row){
	echo "<b>Name:</b> " . $row['name'] . "<br>";
	echo "<b>Description:</b> " . $row['description'] . "<br>";
        echo "<b>Price:</b> " . $row['price'] . "<br>";
        echo "<b>Stock:</b> " . $row['inventory'] . "<br>";
}

echo "<br>";

$currentColor = $origResult[0]['color'];
$currentSize = $origResult[0]['size'];
$currentSKU = $origResult[0]['sku'];
$inv = $origResult[0]['inventory'];


$query = "SELECT DISTINCT color.description, product.color FROM product, color  WHERE product.color = color.id AND name = '" .$pName;
$query = $query . "'";
$query = $query . " AND size = '" .$currentSize;
$query = $query . "'";
$statement = $connect->prepare($query);
$statement->execute();
$colorResult = $statement->fetchAll();

$query = "SELECT DISTINCT size.code, product.size FROM product, size WHERE product.size = size.id AND name = '" .$pName;
$query = $query . "'";
$query = $query . " AND color = '" .$currentColor;
$query = $query . "'";
$statement = $connect->prepare($query);
$statement->execute();
$sizeResult = $statement->fetchAll();

$query = "SELECT sku FROM product WHERE name = '" .$pName;
$query = $query . "'";
$query = $query . " AND color = '" .$currentColor;
$query = $query . "'";
$query = $query . " AND size = '" .$currentSize;
$query = $query . "'";
$statement = $connect->prepare($query);
$statement->execute();
$skuResult = $statement->fetchAll();

?>
<div class="color">
        <span>Color</span>
        <div class="choose">
	 <?php
        foreach($colorResult as $row){
            echo '<button id="button_color_' . $row['description'];
            if($row['color'] == $currentColor)
                echo '" class="bColor active" value ="';
            else
                echo '" class="bColor" value="';
	    echo $row['color'] . '"> ';
            echo $row['description'] . "</button>";
        }
        ?>
         </div>
</div>

<div class="size">
        <span>Size</span>
        <div class="choose">
	<?php
        foreach($sizeResult as $row){
            echo '<button id="button_size_' . $row['code'];
            if($row['size'] == $currentSize)
                echo '" class="bSize active" value ="';
            else
                echo '" class="bSize" value ="';
	    echo $row['size'] . '"> ';
            echo $row['code'] . "</button>";
        }
        ?>

         </div>
</div>

<div class="sku">
        <span>SKU</span>
        <div class="choose">
	<?php
	foreach($skuResult as $row){
  	    echo '<button id="button_SKU_' . $row['sku'];
	    if($row['sku'] == $currentSKU)
	 	echo '" class="bSKU active">';
	    else
	        echo '" class="bSKU">';
	    echo $row['sku'] . "</button>";
	}
	?>
	 </div>
</div>

<div class="toCart">
        <div class="choose">
		<button id="button_addToCart" class="flashy">Add to Cart</button>
		<input type="number" onkeypress="return event.charCode >=48" min="1" value="1" style="width: 48px" id="quantitySelect"> 
        </div>
</div>



<script>
var currentColor = "<?php echo $currentColor ?>";
var currentSize = "<?php echo $currentSize ?>";
var currentSKU = "<?php echo $currentSKU ?>";
var inv =  "<?php echo $inv ?>";
var pName = "<?php echo $pName ?>";
var pID = "<?php echo $id ?>";
var uID = "<?php echo $_COOKIE['TriStorefrontUser']; ?>";
$(document).ready(function(){
 $('.bColor').click(function(){
        if(currentColor != $.trim($(this).val())){
		currentColor = $.trim($(this).val());
		doQuery();
	}
    });


 $('.bSize').click(function(){
	if(currentSize != $.trim($(this).val())){
                currentSize = $.trim($(this).val());
                doQuery();
        }
    });


 $('.bSKU').click(function(){
	if(currentSKU != $.trim($(this).html())){
                currentSKU = $.trim($(this).html());
                doSKUQuery();
        }
    });

 $('.flashy').click(function(){
	var quantity = document.getElementById("quantitySelect").value;
	addToCart(quantity);
    });

function addToCart(quantity){
$.ajax({
            url:"addToCart.php",
            method:"POST",
            data:{pID:pID, uID:uID, quantity:quantity, inv:inv},
            success:function(data){
                alert(data);
            }
        });

}

function doQuery(){
$.ajax({
            url:"productPageSelect.php",
            method:"POST",
            data:{pName:pName, currentColor:currentColor, currentSize:currentSize},
            success:function(data){
                window.location.href='./productPage.php?productID=' + data;
            }
        });

}

function doSKUQuery(){
$.ajax({
            url:"productPageSelect.php",
            method:"POST",
            data:{pName:pName, currentColor:currentColor, currentSize:currentSize, currentSKU:currentSKU},
            success:function(data){
                window.location.href='./productPage.php?productID=' + data;
            }
        });

}

});
</script>

</body>
</html>
