<?php

ob_start();
session_start();
//require(details.php);

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

// Book that is arbitrarily chosen to display info and show proper functionality.
//$book_id = '0545010225'; B073HDB6ZN

if(isset($_GET["b_id"])){
 $book_id = $_GET["b_id"];
}else{
    header( "Location: index.php" );
}


// Queries to extract information about a book, to then populater respetive fields.

$book_name = $mysqli->query("SELECT b_name FROM books WHERE book_id = '".$book_id."'");
$author_bio = $mysqli->query("SELECT a_name, a_bio FROM authors, books_authors WHERE '".$book_id."'= books_authors.book_id AND authors.author_id = books_authors.author_id");
$book_description = $mysqli->query("SELECT b_description FROM books WHERE '$book_id' = book_id");
$book_genre = $mysqli->query("SELECT b_genre FROM books WHERE '$book_id' = book_id");
// Commented out because we do not have a publisher table in our database
$publishing_info = $mysqli->query("SELECT b_pub_name FROM books WHERE book_id = '".$book_id."'");
$book_rating = $mysqli->query("SELECT b_rate FROM books WHERE '$book_id' = book_id");
$comments = $mysqli->query("SELECT comment, c_date FROM comments WHERE '$book_id' = book_id");
$image = $mysqli->query("SELECT b_picture FROM books WHERE '$book_id' = book_id");



$b_nam = $book_name->fetch_assoc();
$b_aut = $author_bio->fetch_assoc();
$b_desc = $book_description->fetch_assoc();
$b_gen = $book_genre->fetch_assoc();
$b_pub = $publishing_info->fetch_assoc();
$b_rat = $book_rating->fetch_assoc();
$b_com = $comments->fetch_assoc();
$b_img = $image->fetch_assoc();

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
                grid-gap: 5px;
                grid-template-rows: auto auto auto auto auto;
            }
            .book_title {
                grid-column: 1 ;
                grid-row: 1;
            }
            .book_cover { 
                grid-column: 1 / 2;
                grid-row: 1 / 3;
            }
            .book_details {
                grid-column: 3 / 5;
                grid-row: 2 / 3;
            }
            .author_info {
                grid-column: 1 / 5;
                grid-row: 4;
            }
            .ratings_comments {
                grid-column: 1 / 5;
                grid-row: 5;
            }
        </style>
    </head>
        <title> Book's info </title>
    <body>
        <?php
        require "header.php";
        ?>
        <!---<div id=main_image>
            <img src="images/index.jpeg" alt="Team 7 book store" >
        </div>  
        -->
        <section>
            
            <div class="wrapper">
                
                <div class="book_title">
                    <?php
                    <h1> echo ".$b_nam['b_name']." </h1>
                    ?>
                </div>
                
                <div class="book_cover">
                    <?php
                    echo "<img 'src=".$b_img['b_picture']."' alt='Book cover'>";
                    ?>
                </div>
                
                <div class="book_details">
                    <?php
                    <h2> echo "By ".$b_aut['a_name']."" </h2>
                    <p> echo ".$b_aut['a_name']." </p>
                    <h3> echo "Genre ".$b_gen['b_genre'].""</h3>
                    <p> echo ".$b_gen['b_genre']." </p>
                    <h4> echo "Publisher" </h4>
                    <p> echo ".$b_pub['b_pub_name']." </p>
                    <p> echo "Book description ".$b_desc['b_description']."" </p>
                    ?>
                </div>
                
                <div class="author_info">
                    <?php
                    <h1> echo "Author" </h1>
                    <p> echo ".$b_aut['a_name']." </p>
                    <h2> echo "Biography" </h2>
                    <p> echo ".$b_aut['a_bio']." </p>
                    ?>
                </div>
                
                <div class="ratings_comments">
                </div>
                
            </div>
            
        </section>
        
        <div id="end_body"></div>
        
    </body>
    
</html>


