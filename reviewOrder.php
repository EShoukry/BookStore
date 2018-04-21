<?php
ob_start();
session_start();

// Create connection
$dbConfig = include('config.php');
$mysqli = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['name']);

// Check connection
if (mysqli_connect_error()) {
    die("Database connection failed: " . mysqli_connect_error());
}

$userId;
if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user'];
} else {
    exit();
}

//Query to obtain all data for order
$cartDataTable = $mysqli->query(""
        . "SELECT b.book_id, b.b_release, b.b_rate, b.b_name, b_price, b.b_picture, b.b_description,"
        . " sc.b_quantity, b.b_quantity AS quantity_left, GROUP_CONCAT(DISTINCT a.a_name SEPARATOR ', ') AS a_name"
        . " FROM books_authors ba, authors a, books b, shoppingcart sc"
        . " WHERE b.book_id = ba.book_id"
        . " AND ba.author_id = a.author_id"
        . " AND b.book_id = sc.book_id"
        . " AND sc.user_id = '" . $userId . "'"
        . " GROUP BY b.book_id");
$cartNumRows = 0;
if ($cartDataTable != null) {
    $cartNumRows = $cartDataTable->num_rows;
}
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">    
        <title>Book Store </title>
        <meta http-equiv="content-type" content="text/plain">
        <link rel="stylesheet" type="text/css" href="css/styles.css">
        <link rel="stylesheet" type="text/css" href="css/cart_styles.css">
    </head>
    <body>
        <?php
        require "header.php";
        ?>

        <section>
            <div class=section_title><h1>Checkout</h1></div>
            <form method="post" class="shopping_cart_form">
                <h2>Checkout</h2>
                <?php
                $cartSubtotalAmount = 0;
                // output data of each row
                if ($cartNumRows == 0) {
                    echo '<h4>Shopping Cart is Empty</h4>';
                }


                for ($i = 0; $i < $cartNumRows; $i++) {
                    $dataTableRow = $cartDataTable->fetch_assoc();
                    $cartSubtotalAmount += $dataTableRow["b_price"] * $dataTableRow["b_quantity"];
                    ?>
                    <div class = "cart_book_container">
                        <div class = "cart_book_cover"><img src = "<?php echo $dataTableRow["b_picture"] ?>" class = "cover_img"></div>
                        <div class = "cart_book_rate"> <img src = "images/<?php echo $dataTableRow["b_rate"] ?>stars.png"></div>
                        <div class = "cart_book_name"><span>title</span><?php echo $dataTableRow["b_name"] ?> </div>
                        <div class = "cart_book_author"><span>author</span><?php echo $dataTableRow["a_name"] ?> </div>
                        <div class = "cart_book_price"><span>price</span>
                            $<?php echo $dataTableRow["b_price"] ?></div>
                        <div class = "cart_book_quantity"><span>quantity</span>
                            <input class= "cart_book_quantity_input" 
                                   type="number" 
                                   size="1"
                                   name="quantity_<?php echo $i ?>"
                                   value="<?php echo $dataTableRow["b_quantity"] ?>"
                                   required="true"
                                   min="0"
                                   max="<?php echo $dataTableRow["quantity_left"]; ?>" />
                        </div>
                        <input size="1"
                               name="id_<?php echo $i ?>"
                               value="<?php echo $dataTableRow["book_id"] ?>" 
                               hidden="true" />

                    </div>
                    <?php
                }
                ?>

                <?php
                if ($cartNumRows > 0) {
                    $_SESSION['cart_num_rows'] = $cartNumRows;
                    ?>

                    <div class="cart_review_container">
                        <h2>Subtotal: </h2> 
                        <h1><?php echo "$" . $cartSubtotalAmount; ?> </h1>
                        <input class="cart_review_input_update" type="submit" name="cart_update" value="update" />
                        <form method="post" action="../reviewOrder.php">
                            <input class="cart_review_input_purchase" type="submit" name="cart_purchase" value="purchase" />
                        </form>
                    </div>

                    <?php
                }
                ?>
            </form>
        </section>
    </body>
    <?php
    require "footer.php";
    ?>
</html>