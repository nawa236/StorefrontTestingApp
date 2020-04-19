<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Store Management Page</title>

</head>

<body>

    <?php
    require('header.php');

    include('dbConnect.php');
    include('productCard.php');

    $cookie_name1 = "TriStorefrontUser";
    if (!isset($_COOKIE[$cookie_name1]))
        header("Location: ./login.php");

    //ensure connection established
    if ($connection->connect_error) {
        echo "Error connecting to database - " + $connection->connect_error;
    }

    $userId = $_COOKIE[$cookie_name1];
    $query = "SELECT * FROM authentication WHERE id=$userId;";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $accounttype = 0;
    $accounttype = $result[0]["type"];

    if ($accounttype == 0) {
        echo "<h1>This page is restricted to store managers.</h1>";
        return;
    }

    // Find all categories to be used in creating products
    $query = "SELECT * FROM category;";
    $statement = $connect->prepare($query);
    $statement->execute();
    $categories = $statement->fetchAll();

    // Find all colors to be used in creating products
    $query = "SELECT * FROM color;";
    $statement = $connect->prepare($query);
    $statement->execute();
    $colors = $statement->fetchAll();

    // Find all sizes to be used in creating products
    $query = "SELECT * FROM size;";
    $statement = $connect->prepare($query);
    $statement->execute();
    $sizes = $statement->fetchAll();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['product-button'])) {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $sku = trim($_POST['sku']);
            $price = trim($_POST['price']);
            $inventory = trim($_POST['inventory']);
            $category = trim($_POST['category']);
            $color = trim($_POST['color']);
            $size = trim($_POST['size']);

            $sql = "INSERT INTO product (name, description, sku, price, inventory, category, color, size) ";
            $sql = $sql . "VALUES ('$name', '$description', '$sku', '$price', '$inventory', '$category', '$color', '$size');";
            $res = mysqli_query($connection, $sql) or die("Could not update" . mysql_error());
        } elseif (isset($_POST['discount-button'])) {
            $amt = trim($_POST['discount']);
            $sql = "UPDATE configuration SET value = '$amt' WHERE parameter = 'discount';";
            $res = mysqli_query($connection, $sql) or die("Could not update" . mysql_error());
        }
    }

    ?>

    <div class="container">
        <div class="flex-grid">
            <section class="col-10">
                <!-- product add form creation -->
                <h4 class="form-header"> Add Product </h4>
                <form class="form-style-9" id="Product Add" action="storeManagement.php" method="post">
                    <ul class="list-unstyled">
                        <li>
                            <div class='form-group form-check-inline'>
                                <label style='margin-right: 3%;'>Name:</label>
                                <input class='field-style field-full align-none' type='text' id='prodNameInput' name='name' placeholder=" Enter Name">
                        </li>
                        <li>
                            <div class='form-group form-check-inline'>
                                <label style='margin-right: 3%;'>Description:</label>
                                <input class='field-style field-full align-none' type='text' id='prodDescInput' name='description' placeholder="Enter Description">
                        </li>
                        <li>
                            <div class='form-group form-check-inline'>
                                <label style='margin-right: 3%;'>SKU:</label>
                                <input class='field-style field-full align-none' type='text' id='prodSkuInput' name='sku' placeholder="Enter SKU">
                        </li>
                        <li>
                            <div class='form-group form-check-inline'>
                                <label style='margin-right: 3%;'>Price:</label>
                                <input class='field-style field-full align-none' type='number' id='prodPriceInput' name='price' placeholder="Enter Price">
                        </li>
                        <li>
                            <div class='form-group form-check-inline'>
                                <label style='margin-right: 3%;'>Inventory:</label>
                                <input class='field-style field-full align-none' type='number' id='prodStockInput' name='inventory' placeholder="Enter Initial Stock">
                        </li>
                        <li>
                            <label style='margin-right: 5px;'>Category:</label>
                            <div style='padding-bottom: 3%;' class='btn-group btn-group-toggle' data-toggle='buttons'>
                                <?php
                                foreach ($categories as $category) {
                                    echo '<label class="btn btn-primary btn-sm border border-dark">';
                                    echo '<input type="radio" name="category" value=' . $category['id'] . '>' . $category["name"];
                                    echo '</label>';
                                }
                                ?>
                            </div>
                        </li>
                        <li>
                            <label style='margin-right: 5px;'>Color:</label>
                            <div style='padding-bottom: 3%;' class='btn-group btn-group-toggle' data-toggle='buttons'>
                                <?php
                                foreach ($colors as $color) {
                                    echo '<label class="btn btn-primary btn-sm border border-dark">';
                                    echo '<input type="radio" name="color" value=' . $color['id'] . '>' . $color["description"];
                                    echo '</label>';
                                }
                                ?>
                            </div>
                        </li>
                        <li>
                            <label style='margin-right: 5px;'>Size:</label>
                            <div style='padding-bottom: 3%;' class='btn-group btn-group-toggle' data-toggle='buttons'>
                                <?php
                                foreach ($sizes as $size) {
                                    echo '<label class="btn btn-primary btn-sm border border-dark">';
                                    echo '<input type="radio" name="size" value=' . $size['id'] . '>' . $size["code"];
                                    echo '</label>';
                                }
                                ?>
                            </div>
                        </li>
                        <br>
                        <input class='btn btn-primary' name="product-button" type="submit" value="Add Product" />
                        </li>
                    </ul>
                </form>

            </section>
            <section class="col-6">
                <!-- store discount form creation -->
                <h4 class="form-header"> Set Store Discount </h4>
                <form class="form-style-9" id="Store Discount" action="storeManagement.php" method="post">
                    <ul class="list-unstyled">
                        <li>
                            <div class='form-group form-check-inline'>
                                <label style='margin-right: 3%;'>Percent Discount:</label>
                                <input class='field-style field-full align-none' type='number' min='0' max='100' id='storeDiscountInput' name='discount' placeholder="%">
                        </li>
                        <br>
                        <input class='btn btn-primary' name="discount-button" type="submit" value="Set Discount" />
                        </li>
                    </ul>
            </section>
        </div>
    </div>

</body>

</html>