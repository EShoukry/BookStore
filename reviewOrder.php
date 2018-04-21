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

/*
 * 
 * DB Queries
 * 
 */
//Query to obtain all data for order
$cartDataTable = $mysqli->query(""
        . "SELECT b.book_id, b.b_release, b.b_rate, b.b_name, b_price, b.b_picture, b.b_description,"
        . " sc.b_quantity, b.b_quantity AS quantity_left, GROUP_CONCAT(DISTINCT a.a_name SEPARATOR ', ') AS a_name"
        . " FROM books_authors ba, authors a, books b, shoppingcart sc"
        . " WHERE b.book_id = ba.book_id"
        . " AND ba.author_id = a.author_id"
        . " AND b.book_id = sc.book_id"
        . " AND sc.user_id = '" . $userId . "'"
        . " GROUP BY b.book_id;"
);

$addressesQuery = $mysqli->query(""
        . "SELECT address_id,"
        . " CONCAT(fname,CHAR(13),"
        . "line1,' ',line2,CHAR(13),"
        . "city,',',state,' ',zip,CHAR(13))"
        . " AS formattedOutput"
        . " FROM address"
        . " WHERE user_id = " . $userId . ";"
);

$creditCartQuery = $mysqli->query(""
        . "SELECT CC_four, CC_id FROM credit_card"
        . " WHERE user_id = " . $userId
);

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
        <link rel="stylesheet" type="text/css" href="css/reviewOrder_styles.css">
    </head>
    <body>
        <?php
        require "header.php";
        ?>

        <section>
            <div class=section_title><h1>Checkout</h1></div>
            <div class="shopping_cart_form">
                <h2>Checkout Items</h2>
                <?php
                $cartSubtotalAmount = 0;
                // output data of each row
                if ($cartNumRows == 0) {
                    echo '<h4>Do some shopping to see checkout items!</h4>';
                }


                for ($i = 0; $i < $cartNumRows; $i++) {
                    $dataTableRow = $cartDataTable->fetch_assoc();
                    $cartSubtotalAmount += $dataTableRow["b_price"] * $dataTableRow["b_quantity"];
                    ?>
                    <div class = "cart_book_container">
                        <div class = "cart_book_cover"><img src = "<?php echo $dataTableRow["b_picture"]
                    ?>" class = "cover_img"></div>
                        <div class = "cart_book_rate"> <img src = "images/<?php echo $dataTableRow["b_rate"] ?>stars.png"></div>
                        <div class = "cart_book_name"><span>title</span><?php echo $dataTableRow["b_name"] ?> </div>
                        <div class = "cart_book_author"><span>author</span><?php echo $dataTableRow["a_name"] ?> </div>
                        <div class = "cart_book_price"><span>price</span>
                            $<?php echo $dataTableRow["b_price"] ?>
                        </div>
                        <div class = "cart_book_quantity"><span>quantity</span>
                            <?php echo $dataTableRow["b_quantity"] ?>
                        </div>
                    </div>
                    <?php
                }
                ?>

                <form class = "reviewOrder_review_container" method="post" name="placeOrder_form" action="orderReceipt.php">
                    <h2>Subtotal: </h2>
                    <h1><?php echo "$" . $cartSubtotalAmount; ?> </h1>
                    <div>
                        <input class="reviewOrder_checkout" type="submit" name="cart_purchase" value="checkout" />
                        <div class="reviewOrder_address_text">Address</div>
                        <select class="reviewOrder_address" name="reviewOrder_address">
                            <?php
                            for ($j = 0; $j < $addressesQuery->num_rows; $j++) {
                                $addrRow = $addressesQuery->fetch_assoc();
                                echo '<option value=' . $addrRow["address_id"] . '>'
                                . $addrRow["formattedOutput"] .
                                '</option>';
                            }
                            ?>
                        </select>

                        <div class ="reviewOrder_creditCart_text">Card last 4</div>
                        <select class="reviewOrder_creditCart" name="reviewOrder_creditCart">
                            <?php
                            for ($j = 0; $j < $creditCartQuery->num_rows; $j++) {
                                $ccRow = $creditCartQuery->fetch_assoc();
                                echo '<option value=' . $ccRow["CC_id"] . '>'
                                . $ccRow["CC_four"] .
                                '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </form>



            </div>
        </section>
    </body>
    <?php
    require "footer.php";
    ?>
</html>