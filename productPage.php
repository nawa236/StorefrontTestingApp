<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Product Page</title>

</head>

<body>

    <?php
    require('header.php');
    if (!isset($_GET['productID'])) {
        header("Location: ./productList.php");
    }

    $id = 0;
    if ($_SERVER["REQUEST_METHOD"] == "GET")
        $id = $_GET['productID'];
    if ($_SERVER["REQUEST_METHOD"] == "POST")
        $id = trim($_POST['id']);
    include('dbConnect.php');
    include('productCard.php');

    // Retrieve data on requested product id
    $query = "SELECT * FROM product WHERE id = '" . $id;
    $query = $query . "'";
    $statement = $connect->prepare($query);
    $statement->execute();
    $origResult = $statement->fetchAll();
    $total_row = $statement->rowCount();
    if ($total_row == 0) {
        echo "<h1> This is not a valid product id.</h1>";
        return;
    }

    // Build display divide for product image
    $pName = $origResult[0]['name'];
    echo "<h2> Product Page for: $pName </h2>";

    echo '<div class="container">
  <div class="flex-grid">
    <aside class="col sidebar2">
<div class="sidebarContents">';


    //***** Bug 13 Start *****//
    $bugCode = bug_check(13);
    if (is_null($bugCode))
        $image = "./images/" . $id . ".jpg";
    else
        eval($bugCode);
    //***** Bug 13 End *****//


    echo "<img id=\"image$id\" class='ppImg' src=$image>";

    echo '</div></aside><section class="col main"> <div>';

    // Display core product data
    foreach ($origResult as $row) {
        echo "<b>Name:</b> " . $row['name'] . "<br>";
        echo "<b>Description:</b> " . $row['description'] . "<br>";
        echo "<b>Price:</b> " . $row['price'] . "<br>";
        echo "<b>Stock:</b> " . $row['inventory'] . "<br>";
    }

    echo "<br>";

    $currentId = $origResult[0]['id'];
    $currentDesc = $origResult[0]['description'];
    $currentPrice = $origResult[0]['price'];
    $currentInventory = $origResult[0]['inventory'];
    $currentColor = $origResult[0]['color'];
    $currentSize = $origResult[0]['size'];
    $currentSKU = $origResult[0]['sku'];
    $inv = $origResult[0]['inventory'];

    // Find all color variants that are possible for current product size selection
    $query = "SELECT DISTINCT color.description, product.color FROM product, color  WHERE product.color = color.id AND name = '" . $pName;
    $query = $query . "'";
    $query = $query . " AND size = '" . $currentSize;
    $query = $query . "'";
    $statement = $connect->prepare($query);
    $statement->execute();
    $colorResult = $statement->fetchAll();

    // Find all size variants that are possible for current product color selection
    $query = "SELECT DISTINCT size.code, product.size FROM product, size WHERE product.size = size.id AND name = '" . $pName;
    $query = $query . "'";
    $query = $query . " AND color = '" . $currentColor;
    $query = $query . "'";
    $statement = $connect->prepare($query);
    $statement->execute();
    $sizeResult = $statement->fetchAll();

    // Find all sku variants that otherwise have the exact same color and size
    $query = "SELECT sku FROM product WHERE name = '" . $pName;
    $query = $query . "'";
    $query = $query . " AND color = '" . $currentColor;
    $query = $query . "'";
    $query = $query . " AND size = '" . $currentSize;
    $query = $query . "'";
    $statement = $connect->prepare($query);
    $statement->execute();
    $skuResult = $statement->fetchAll();

    ?>
    <!-- Display buttons for each of the color variants -->
    <div class="color">
        <span>Color</span>
        <div class="choose">
            <?php
            foreach ($colorResult as $row) {
                echo '<button id="button_color_' . $row['description'];
                if ($row['color'] == $currentColor)
                    echo '" class="bColor active" value ="';
                else
                    echo '" class="bColor" value="';
                echo $row['color'] . '"> ';
                echo $row['description'] . "</button>";
            }
            ?>
        </div>
    </div>

    <!-- Display buttons for each of the size variants -->
    <div class="size">
        <span>Size</span>
        <div class="choose">
            <?php
            foreach ($sizeResult as $row) {
                echo '<button id="button_size_' . $row['code'];
                if ($row['size'] == $currentSize)
                    echo '" class="bSize active" value ="';
                else
                    echo '" class="bSize" value ="';
                echo $row['size'] . '"> ';
                echo $row['code'] . "</button>";
            }
            ?>

        </div>
    </div>

    <!-- Display buttons for each of the SKU variants -->
    <div class="sku">
        <span>SKU</span>
        <div class="choose">
            <?php
            foreach ($skuResult as $row) {
                echo '<button id="button_SKU_' . $row['sku'];
                if ($row['sku'] == $currentSKU)
                    echo '" class="bSKU active">';
                else
                    echo '" class="bSKU">';
                echo $row['sku'] . "</button>";
            }
            ?>
        </div>
    </div>

    <!-- Allow items to be added to the cart with a quantity selection -->
    <div class="toCart">
        <div class="choose">
            <button id="button_addToCart" class="flashy">Add to Cart</button>
            <input type="number" onkeypress="return event.charCode >=48" min="1" value="1" style="width: 48px" id="quantitySelect">
        </div>
    </div>

    <?php echo '</div></section>'; ?>

    <!-- Display product updating information for store admins -->
    <section class="col-3">
        <?php
        $cookie_name1 = "TriStorefrontUser";
        if (!isset($_COOKIE[$cookie_name1]))
            header("Location: ./login.php");

        $userId = $_COOKIE[$cookie_name1];
        $query = "SELECT * FROM authentication WHERE id=$userId;";
        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $accounttype = 0;
        $accounttype = $result[0]["type"];

        //if user intention is to change product information
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //if conditionals to determine if product information is being changed/applies changes
            if (isset($_POST['name'])) {
                $name = trim($_POST['name']);
                $sql = "UPDATE product SET name = '$name' WHERE id = $id";
                $res = mysqli_query($connection, $sql) or die("Could not update" . mysql_error());
            }
            if (isset($_POST['description'])) {
                $name = trim($_POST['description']);
                $sql = "UPDATE product SET description = '$name' WHERE id = $currentId";
                $res = mysqli_query($connection, $sql) or die("Could not update" . mysql_error());
            }
            if (isset($_POST['sku'])) {
                $name = trim($_POST['sku']);
                $sql = "UPDATE product SET sku = '$name' WHERE id = $currentId";
                $res = mysqli_query($connection, $sql) or die("Could not update" . mysql_error());
            }
        }

        if ($accounttype == 2) echo "
    <h4 class='form-header'> Update Product </h4>
    <form class= 'form-style-9' id = 'Product Update' action = 'productPage.php' method = 'post'>
    <input type = 'hidden' id = 'prodId' name = 'id' value = '$id'>
    <ul class='list-unstyled'>
    <li>
        <div class='form-group form-check-inline'>
        <label style='margin-right: 3%;'>Name:</label>
        <input class='field-style field-full align-none' type = 'text' id='prodNameInput' name = 'name' value = '$pName'>
    </li>
    <li>
        <div class='form-group form-check-inline'>
        <label style='margin-right: 3%;'>Description:</label>
        <input class='field-style field-full align-none' type = 'text' id='prodDescInput' name = 'description' value = '$currentDesc'>
    </li>
    <li>
        <div class='form-group form-check-inline'>
        <label style='margin-right: 3%;'>SKU:</label>
        <input class='field-style field-full align-none' type = 'text' id='prodSkuInput' name = 'sku' value = '$currentSKU'>
    </li>
    <li>
        <div class='form-group form-check-inline'>
        <label style='margin-right: 3%;'>Price:</label>
        <input class='field-style field-full align-none' type = 'number' id='prodDescInput' name = 'price' value = '$currentPrice'>
    </li>
    <li>
        <div class='form-group form-check-inline'>
        <label style='margin-right: 3%;'>Stock:</label>
        <input class='field-style field-full align-none' type = 'number' id='prodInvInput' name = 'inventory' value = '$currentInventory'>
    </li>
    <li>
        <div class='form-group form-check-inline'>
        <label style='margin-right: 3%;'>Color:</label>
        <input class='field-style field-full align-none' type = 'number' id='prodColorInput' name = 'color' value = '$currentColor'>
    </li>
    <li>
        <div class='form-group form-check-inline'>
        <label style='margin-right: 3%;'>Size:</label>
        <input class='field-style field-full align-none' type = 'text' id='prodSizeInput' name = 'size' value = '$currentSize'>
    </li>
    <li>
    <br>
	<input class='btn btn-primary' type = 'submit' value = 'Update'/>
    </li>
    </ul>
    </form>";
        ?>
    </section>

    <script>
        var currentColor = "<?php echo $currentColor ?>";
        var currentSize = "<?php echo $currentSize ?>";
        var currentSKU = "<?php echo $currentSKU ?>";
        var inv = "<?php echo $inv ?>";
        var pName = "<?php echo $pName ?>";
        var pID = "<?php echo $id ?>";
        var uID = "<?php echo $_COOKIE['TriStorefrontUser']; ?>";
        var quantity = 1;
        $(document).ready(function() {

            // Event triggers for page elements
            $('.bColor').click(function() {
                if (currentColor != $.trim($(this).val())) {
                    currentColor = $.trim($(this).val());
                    doQuery();
                }
            });


            $('.bSize').click(function() {
                if (currentSize != $.trim($(this).val())) {
                    currentSize = $.trim($(this).val());
                    doQuery();
                }
            });


            $('.bSKU').click(function() {
                if (currentSKU != $.trim($(this).html())) {
                    currentSKU = $.trim($(this).html());
                    //*****  Bug 10 Start ****//
                    var bugCode = "<?php echo bug_check(10); ?>";
                    if (bugCode == "")
                        doSKUQuery();
                    //*****  Bug 10 End  ****//
                }
            });

            $('.flashy').click(function() {
                //*****  Bug 9 Start ****//
                var bugCode = "<?php echo bug_check(9); ?>";
                if (bugCode == "")
                    quantity = document.getElementById("quantitySelect").value;
                //*****  Bug 9 End  ****//
                addToCart(quantity);
            });
            // ******************************


            // Runs add to cart script, returns basic success/fail message
            function addToCart(quantity) {
                $.ajax({
                    url: "addToCart.php",
                    method: "POST",
                    data: {
                        pID: pID,
                        uID: uID,
                        quantity: quantity,
                        inv: inv
                    },
                    success: function(data) {
                        alert(data);
                    }
                });

            }

            // Based on current input, determines what product page should be redirected to
            function doQuery() {
                $.ajax({
                    url: "productPageSelect.php",
                    method: "POST",
                    data: {
                        pName: pName,
                        currentColor: currentColor,
                        currentSize: currentSize
                    },
                    success: function(data) {
                        window.location.href = './productPage.php?productID=' + data;
                    }
                });

            }

            // Based on current input, determines what product page should be redirected to
            function doSKUQuery() {
                $.ajax({
                    url: "productPageSelect.php",
                    method: "POST",
                    data: {
                        pName: pName,
                        currentColor: currentColor,
                        currentSize: currentSize,
                        currentSKU: currentSKU
                    },
                    success: function(data) {
                        window.location.href = './productPage.php?productID=' + data;
                    }
                });

            }

        });
    </script>

</body>

</html>