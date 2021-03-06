<?php

ob_start();
session_start();
//require("details.php")

function phpAlert($msg) {
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}


// Create connection
$dbConfig = include('config.php');
$mysqli = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['name']);

// Check connection
if (mysqli_connect_error()) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Arbitrarily picking an author id to show functionality of this page.
//$author_id = 12;
if(isset($_GET["a_id"])){
 $author_id = $_GET["a_id"];
}else{
    header( "Location: index.php" );
}

// Query to get author's name and bio
$author_name_bio = $mysqli->query("SELECT a_name, a_bio FROM authors WHERE '$author_id' = author_id");

$a_info = $author_name_bio->fetch_assoc();



 $result = $mysqli->query("SELECT book_id, b_name, b_price, b_picture, b_description, b_rate FROM books WHERE books.book_id IN (SELECT book_id FROM books_authors WHERE '$author_id' = author_id)");
                      
                
                
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">    
        <title>Book Store </title>
        <meta http-equiv="content-type" content="text/plain">
        <link rel="stylesheet" type="text/css" href="css/styles.css">
        <script src="scripts/main.js"></script>
    </head>
    <title> "Author info" </title>
    <body>
        <?php
        require "header.php";
        ?>
         
        <section>
           <?php
            echo "<div class=section_title><h1>".$a_info['a_name']."</h1></div>";
            echo "<hr>";
            echo "<h3> Author's bio: </h3>";
            echo "<p>".$a_info['a_bio']."</p>";
            echo "<br>";
            echo "<h3>Books written by ".$a_info['a_name'].":</h3>";
                $GLOBALS["result"] = $result;
                require "includes/books_shown.php";
           ?>
        </section>   

        <div id="end_body"></div>  
    </body>


<?php
require "footer.php";
echo "</html>";
mysqli_close($mysqli);
?>
