<?php
// Create connection
$dbConfig = include('config.php');
$mysqli = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['name']);

if (!$_SESSION["shoppingCart"]) {
    die('Invalid Query: ' . mysql_error());
}

//if a user id is passed into shoppingCart session variable
if ($_SESSION["shoppingCart"]->num_rows > 0) {
    $userId = $_SESSION["shoppingCart"]->fetch_assoc()["user_id_number"];
    ?>

    <head><head>
        <link rel="stylesheet" href="css/cart_styles.css">
    </head></head>

    <?php
    /*
     * 
     * DB Queries
     * 
     */
    //Query to obtain all data for shopping cart output
    $wishlistDataTable = $mysqli->query(""
            . "SELECT b.book_id, b.b_release, b.b_rate, b.b_name, b_price, b.b_picture, b.b_description, GROUP_CONCAT(DISTINCT a.a_name SEPARATOR ', ') AS a_name"
            . " FROM books_authors ba, authors a, books b, wishlist w"
            . " WHERE b.book_id = ba.book_id"
            . " AND ba.author_id = a.author_id"
            . " AND b.book_id = w.book_id"
            . " AND w.user_id = '" . $userId . "'"
            . " GROUP BY b.book_id");

    $wishlistNumRows = 0;
    if ($wishlistDataTable != null) {
        $wishlistNumRows = $wishlistDataTable->num_rows;
    }

    //Query to obtain all data for output
    $cartDataTable = $mysqli->query(""
            . "SELECT b.book_id, b.b_release, b.b_rate, b.b_name, b_price, b.b_picture, b.b_description, bu.b_quantity, GROUP_CONCAT(DISTINCT a.a_name SEPARATOR ', ') AS a_name"
            . " FROM books_authors ba, authors a, books b, shoppingcart bu"
            . " WHERE b.book_id = ba.book_id"
            . " AND ba.author_id = a.author_id"
            . " AND b.book_id = bu.book_id"
            . " AND bu.user_id = '" . $userId . "'"
            . " GROUP BY b.book_id");

    $cartNumRows = 0;
    if ($cartDataTable != null) {
        $cartNumRows = $cartDataTable->num_rows;
    }

    /*
     * 
     * Shopping cart updates
     * 
     */
    if (isset($_POST['cart_update'])) { //Update book quantities
        for ($i = 0; $i < $_SESSION['cart_num_rows']; $i++) {
            $bookId = $_POST['id_' . $i];
            $toUpdateQuantity = $_POST['quantity_' . $i];

            $updateQuery = ""
                    . "UPDATE shoppingcart b"
                    . " SET b.b_quantity = " . $toUpdateQuantity
                    . " WHERE b.book_id = \"" . $bookId . "\""
                    . " AND b.user_id = " . $userId;
            if ($mysqli->query($updateQuery) == FALSE) {
                echo "Error updating record: " . $mysqli->error;
                error_log("Error updating record: " . $mysqli->error);
            }
            $updateDeleteQuery = ""
                    . "DELETE FROM shoppingcart"
                    . " WHERE shoppingcart.b_quantity = 0";
            if ($mysqli->query($updateDeleteQuery) == FALSE) {
                echo '<h4>Wishlist is Empty</h4>';
                error_log("Error updating record: " . $mysqli->error);
            }
        }
    }
    if (isset($_POST['add_book_to_cart'])) { //if adding book from main page or book details
        $bookId = $_POST["add_book_id"];
        $userId = $_POST["add_user_id"];
        
        $insertQuery = "INSERT INTO shoppingcart (book_id, user_id, b_quantity)"
                . " VALUES (\"" . $bookId . "\"," . $userId . "," . 1 . ");";

        if ($mysqli->query($insertQuery) == FALSE) {
            echo "ERROR: " . $insertQuery;
        }
    }


    /*
     * 
     * Wishlist updates
     * 
     */
    if (isset($_POST['wishlist_moveToCart'])) { //if moving book from wishlist to cart
        $bookId = $_POST['wishlist_book_id'];
        echo "moveto: " . $bookId;
    }
    if (isset($_POST['wishlist_remove'])) { //if deleting book from wishlist
        $bookId = $_POST['wishlist_book_id'];
        echo "remove: " . $bookId;
    }


    /*
     * 
     * Forms
     * 
     */
    ?>
    <!--Form for displaying logged in user's shopping cart-->
    <form method="post" class="shopping_cart_form">
        <h2>Items in Cart</h2>
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
                           max="50" />
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
                <input class ="cart_review_input_update" type="submit" name="cart_update" value="update" />
            </div>

            <?php
        }
        ?>
    </form>

    <!--Form for displaying logged in user's wishlist-->
    <form method="post" class="wishlist_form">
        <h2>Items in Wishlist</h2>
        <?php
        if ($wishlistNumRows == 0) {
            echo '<h4>Wishlist is Empty</h4>';
        } else {
            // output data of each row
            for ($i = 0; $i < $wishlistNumRows; $i++) {
                $wishlistDataTableRow = $wishlistDataTable->fetch_assoc();
                ?>
                <div class = "cart_book_container">
                    <div class = "cart_book_cover"><img src = "<?php echo $wishlistDataTableRow["b_picture"] ?>" class = "cover_img"></div>
                    <div class = "cart_book_rate"> <img src = "images/<?php echo $wishlistDataTableRow["b_rate"] ?>stars.png"></div>
                    <div class = "cart_book_name"><span>title</span><?php echo $wishlistDataTableRow["b_name"] ?> </div>
                    <div class = "cart_book_author"><span>author</span><?php echo $wishlistDataTableRow["a_name"] ?> </div>
                    <div class = "cart_book_price"><span>price</span>
                        $<?php echo $wishlistDataTableRow["b_price"] ?>
                    </div>
                    <input size="1"
                           name="wishlist_book_id"
                           value="<?php echo $wishlistDataTableRow["book_id"] ?>" 
                           hidden="true" />
                    <input class ="wishlist_remove" type="submit" name="wishlist_remove[]" value="remove" />
                    <input class ="wishlist_moveToCart" type="submit" name="wishlist_moveToCart[]" value="move to cart" />
                </div>
                <?php
            }
        }
        ?>
    </form>

    <?php
} else {
    echo "Empty Cart";
}
mysqli_close($mysqli);
?>

