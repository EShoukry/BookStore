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
     * Shopping cart updates
     * 
     */
    if (isset($_POST['cart_update'])) { //Update book quantities
        for ($i = 0; $i < $_SESSION['cart_num_rows']; $i++) {
            $bookId = $_POST['id_' . $i];


            if (isset($_POST['cart_moveToWishlist_' . $i]) ||
                    isset($_POST['cart_remove_' . $i])) {
                $query_removeFromShoppingCart = ""
                        . "DELETE FROM shoppingcart WHERE "
                        . "book_id = '" . $bookId . "' AND "
                        . "user_id = " . $userId . ";";
                $query_addToWishList = ""
                        . "INSERT INTO wishlist (book_id, user_id)"
                        . " VALUES (\"" . $bookId . "\"," . $userId . ");";
                if ($mysqli->query($query_removeFromShoppingCart) == FALSE) {
                    echo "Error deleting record: " . $mysqli->error;
                    error_log("Error updating record: " . $mysqli->error);
                }
                if (!isset($_POST['cart_remove_' . $i])) {
                    if ($mysqli->query($query_addToWishList) == FALSE) {
                        echo "Error adding record: " . $mysqli->error;
                        error_log("Error updating record: " . $mysqli->error);
                    }
                }
                continue;
            }

            $toUpdateQuantity = $_POST['quantity_' . $i];
            $updateQuery = ""
                    . "UPDATE shoppingcart s"
                    . " SET s.b_quantity = " . $toUpdateQuantity
                    . " WHERE s.book_id = \"" . $bookId . "\""
                    . " AND s.user_id = " . $userId;
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
    if (isset($_POST['wishlist_remove']) != '' ||
            isset($_POST['wishlist_moveToCart']) != '') { //if deleting book from wishlist or moving it to cart
        //delete the book from wishlist
        $fromWishlistBookId = $_POST['wishlist_book_id'];
        $query_removeFromWishlist = "" .
                "DELETE FROM wishlist WHERE " .
                "book_id = \"" . $fromWishlistBookId . "\" AND " .
                "user_id = " . $userId;
        if ($mysqli->query($query_removeFromWishlist) == FALSE) {
            echo "ERROR: " . $query_removeFromWishlist;
        }
        //if we are moving it to cart, add it to the shopping cart
        if (isset($_POST['wishlist_moveToCart']) != '') {
            $query_addToShoppingCart = "INSERT INTO shoppingcart (book_id, user_id, b_quantity) "
                    . "VALUES (\"" . $fromWishlistBookId . "\"," . $userId . "," . 1 . ");";
            if ($mysqli->query($query_addToShoppingCart) == FALSE) {
                echo "ERROR: " . $query_addToShoppingCart;
            }
        }
    }


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
                           max="<?php echo $dataTableRow["quantity_left"]; ?>" />
                </div>
                <div class="cart_book_remove">
                    <label for="chkbox_remove<?php echo $i; ?>"> Remove </label>
                    <input type='checkbox' 
                           name="cart_remove_<?php echo $i ?>" 
                           id="chkbox_remove<?php echo $i; ?>" />
                </div>
                <div class="cart_book_moveToWishlist">
                    <label for="chkbox_toWishList<?php echo $i; ?>"> To Wishlist </label>
                    <input type='checkbox' 
                           name="cart_moveToWishlist_<?php echo $i ?>" 
                           id="chkbox_toWishList<?php echo $i; ?>" />
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

    <h2>Items in Wishlist</h2>
    <?php
    if ($wishlistNumRows == 0) {
        echo '<h4>Wishlist is Empty</h4>';
    } else {
        // output data of each row

        for ($i = 0; $i < $wishlistNumRows; $i++) {
            $wishlistDataTableRow = $wishlistDataTable->fetch_assoc();
            ?>
            <form method="post" class="wishlist_form">
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
                    <button class="wishlist_remove"
                            type="submit" 
                            name="wishlist_remove[]" 
                            value="move to cart" >
                        <img class="wishlist_img_remove" src="images/x.png" width="20" height="20">
                    </button>
                    <button class="wishlist_moveToCart"
                            type="submit" 
                            name="wishlist_moveToCart[]" 
                            value="remove" >
                        <img class="wishlist_img_moveToCart" src="images/shoppingCartAdd.png" width="20" height="20">
                    </button>
                </div>
            </form>
            <?php
        }
    }
    ?>


    <?php
} else {
    echo "Empty Cart";
}
mysqli_close($mysqli);
?>

