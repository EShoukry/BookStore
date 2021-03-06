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
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">    
        <title>Book Store </title>
        <meta http-equiv="content-type" content="text/plain">
        <link rel="stylesheet" type="text/css" href="css/styles.css">
    </head>
    <body>
        <?php
        require "header.php";
        ?>

        <section>
            <div class=section_title><h1>Shopping Cart</h1></div>
            <hr>
            <?php
            if (isset($_SESSION['user'])) {
                //Query to get the user's checked out books
                //$currentUserLoginId = $_SESSION['user']; //sets to logged in users Primary Key
                $currentUserLoginId = $_SESSION['user'];
                $_SESSION["shoppingCart"] = $mysqli->query(""
                        . "SELECT users.user_id_number"
                        . " FROM users"
                        . " WHERE users.user_id_number = '" . $currentUserLoginId . "'");
                mysqli_close($mysqli);
                require "includes/books_shoppingCart.php";
            }
            ?>        
        </section>
    </body>
    <?php
    require "footer.php";
    ?>
</html>