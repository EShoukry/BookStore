<?php
ob_start();
session_start();

$servername = "db720121368.db.1and1.com";
$username = "dbo720121368";
$password = "TeamSeven7@";
$dbname = "db720121368";

//$servername = "localhost";
//$username = "root";
//$password = "";
//$dbname = "bookstore";

// Create connection
$mysqli = new mysqli($servername, $username, $password, $dbname);

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
            <div id="Shopping Cart" class="shopping_cart_main">

                <?php
                //Query to get the user's checked out books
                //$currentUserLoginId = $_SESSION['user']; //sets to logged in users Primary Key
                $currentUserLoginId = "Test Everything";
                $_SESSION["shoppingCart"] = $mysqli->query(""
                        . "SELECT users.user_id_number"
                        . " FROM users"
                        . " WHERE users.u_login_id = '" . $currentUserLoginId . "'");
                mysqli_close($mysqli);
                require "includes/books_shoppingCart.php";
                ?>        

            </div>
        </section>   
    </body>
    <?php
    require "footer.php";
    ?>
</html>