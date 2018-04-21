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
        <style>
            .wrapper {
                display: grid;
                grid-template-columns: auto auto auto auto auto;
                grid-template-rows: auto auto auto auto auto auto;
                grid-gap: 5px;
            }
            .section_title {
                grid-column: 1 / 5;
                grid-row: 1 / 2;
            }
            .books_shown {
                grid-column: 1 / 5;
                grid-row: 3 / 6;
            }
        </style>
    </head>
    
    <title> "Author info" </title>
    <body>
        
        <?php
        require "header.php";
        ?>
        <section>
            
            <div class = "wrapper">
                
                <div class="section_title">
                    <?php
                    <h1> echo ".$a_info['a_name']" </h1>
                    <h2> echo "Author bio: " </h2>
                    <p> echo "$a_info['a_bio']" </p>
                    ?>
                </div>
                        
                <hr>
                <br>
                
                <div class= "books_shown">
                    <?php
                    echo "Books written by ".$a_info['a_name']."";
                    $GLOBALS["result"] = $result;
                    require "includes/books_shown.php";
                    ?>
                </div>
                
            </div>
        </section> 
    </body>
</html>

<?php
require "footer.php";
echo "</html>";
mysqli_close($mysqli);
?>

