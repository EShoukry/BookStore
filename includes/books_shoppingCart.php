<?php
// Create connection
$dbConfig = include('config.php');
$mysqli = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['name']);

if (!$_SESSION["shoppingCart"]) {
    die('Invalid Query: ' . mysql_error());
}

//if a user id is passed into shoppingCart session variable
if ($_SESSION["shoppingCart"]->num_rows > 0) {
    $userId;
    ?>
    <head><head>
        <link rel="stylesheet" href="css/cart_styles.css">
    </head></head>

    <form method="post">
        <?php
        // output data of each row
        $numRows;
        if ($idsTable = $_SESSION["shoppingCart"]->fetch_assoc()) {
            //Query to obtain all data for output
            $userId = $idsTable["user_id_number"];
            $dataTable = $mysqli->query(""
                    . "SELECT b.book_id, b.b_release, b.b_rate, b.b_name, b_price, b.b_picture, b.b_description, bu.b_quantity, GROUP_CONCAT(DISTINCT a.a_name SEPARATOR ', ') AS a_name"
                    . " FROM books_authors ba, authors a, books b, books_users bu"
                    . " WHERE b.book_id = ba.book_id"
                    . " AND ba.author_id = a.author_id"
                    . " AND b.book_id = bu.book_id"
                    . " AND bu.user_id = '" . $userId . "'"
                    . " GROUP BY b.book_id");

            if (!$dataTable) {
                die('Invalid Query 1: ' . mysql_error());
            } else {
                $numRows = $dataTable->num_rows;
            }

            for ($i = 0; $dataTableRow = $dataTable->fetch_assoc(); $i++) {
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
        }
        ?>

        <?php
        if ($numRows > 0) {
            echo '<div class="cart_review_container">';
            echo '<input type="submit" name="cart_update" value="update" />';
            echo '</div>';
        }
        ?>
    </form>

    <form method="post">
        
    </form>
    
    <?php
    //Update book quantities
    if (isset($_POST['cart_update'])) {
        for ($i = 0; $i < $numRows; $i++) {
            $bookId = $_POST['id_' . $i];
            $toUpdateQuantity = $_POST['quantity_' . $i];

            $updateQuery = ""
                    . "UPDATE books_users b"
                    . " SET b.b_quantity = " . $toUpdateQuantity
                    . " WHERE b.book_id = " . $bookId
                    . " AND b.user_id = " . $userId;
            if ($mysqli->query($updateQuery) == FALSE) {
                echo "Error updating record: " . $mysqli->error;
                error_log("Error updating record: " . $mysqli->error);
                adad();
            }
            $updateDeleteQuery = ""
                    . "DELETE FROM books_users"
                    . " WHERE books_users.b_quantity = 0";
            if ($mysqli->query($updateDeleteQuery) == FALSE) {
                echo "Error updating record: " . $mysqli->error;
                error_log("Error updating record: " . $mysqli->error);
                adad();
            }
        }
        ?>
        <script>
            window.location.reload()
            window.location.href = window.location.href.split('?')[0]
        </script>
        <?php
    }
} else {
    echo "Empty Cart";
}
mysqli_close($mysqli);
?>

