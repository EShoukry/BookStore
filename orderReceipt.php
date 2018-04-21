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
 * DB queries
 * 
 * 
 */
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

$nextOrderIdQuery = $mysqli->query(""
        . "SELECT order_id FROM orders ORDER BY order_id DESC LIMIT 1"
);

/*
 * 
 * Review Order Updates
 * 
 */
if (isset($_POST['cart_purchase']) != "") {
    $formattedDate = date("c");
    $addressId = $_POST["reviewOrder_address"];
    $creditCardId = $_POST["reviewOrder_creditCart"];

    $isAnyQueryBroken = false;

    try {
        for ($i = 0; $i < $cartDataTable->num_rows; $i++) {
            $cartRow = $cartDataTable->fetch_assoc();
            $bookId = $cartRow["book_id"];


            $checkIfExistsQuery = ""
                    . "SELECT user_id, book_id, b_quantity FROM books_users"
                    . " WHERE user_id = " . $userId
                    . " AND book_id = '" . $bookId . "';";
            $checkIfExistsResults = $mysqli->query($checkIfExistsQuery);
            
            //check for existing user_id, book_id pair in books_users
            if ($checkIfExistsResults->num_rows > 0) {
                $mysqli->query(""
                        . "UPDATE books_users"
                        . " SET b_quantity = " .
                        $checkIfExistsResults->fetch_assoc()["b_quantity"] + $cartRow["b_quantity"]
                        . " WHERE user_id = " . $userId
                        . " AND book_id = '" . $bookId . "';"
                );
            } else {
                echo ""
                        . "INSERT INTO books_users (book_id, user_id, b_quantity, rated_book, book_rating)"
                        . " VALUES (\"" . $bookId . "\"," . $userId . "," . $cartRow["b_quantity"] . ",0,0);\n";
                $mysqli->query(""
                        . "INSERT INTO books_users (book_id, user_id, b_quantity, rated_book, book_rating)"
                        . " VALUES (\"" . $bookId . "\"," . $userId . "," . $cartRow["b_quantity"] . ",0,0);"
                );
            }
        }
    } catch (Exception $e) {
        echo '<h2>Query failed: ' . $e->getMessage() . '</h2>';
        exit();
    }

    $insertQuery = "INSERT INTO orders (user_id, o_date, o_amount, order_id, o_is_compleated)"
            . " VALUES (\"" . $userId . "\",\"" . $formattedDate . "\",\"" . $cartRow["book_id"] . "\",\"" . $bookId . "\");";
} else {
    echo '<h1>No Order has been Placed!</h1>';
    exit();
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
            <div class=section_title><h1>Receipt</h1></div>
            <div class="receipt_show">
                <h2>You have made a purchase!</h2>
            </div>
        </section>
    </body>
<?php
require "footer.php";
?>
</html>