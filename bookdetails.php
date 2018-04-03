<?php

ob_start();
session_start();
require(details.php);

function phpAlert($msg) {
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Create connection
$dbConfig = include('config.php');
$mysqli = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['name']);

// Check connection
if (mysqli_connect_error()) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Book that is arbitrarily chosen to display info and show proper functionality.
$book_id = '0545010225';

// Queries to extract information about a book, to then populater respetive fields.
$book_name = $mysqli->query("SELECT b_name FROM books WHERE book_id = $book_id");
$author_bio = $mysqli->query("SELECT a_name, a_bio FROM authors, books_authors WHERE $book_id = books_authors.book_id AND author_id = books_author.author_id");
$book_description = $mysqli->query("SELECT b_description FROM books WHERE $book_id = book_id");
$book_genre = $mysqli->query("SELECT b_genre FROM books WHERE $book_id = book_id");
// Commented out because we do not have a publisher table in our database
// $publishing_info = $mysqli->query("SELECT");
$book_rating = $mysqli->query("SELECT b_rate FROM books WHERE $book_id = book_id");
$comments = $mysqli->query("SELECT comment, c_date FROM comments WHERE $book_id = book_id");

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
    <title> Book's info </title>
    <body>
        <?php
        require "header.php";
        ?>
        <div id=main_image>
            <img src="images/index.jpeg" alt="Team 7 book store" >
        </div>  
        <section>
            <div class=section_title><h1> Book's Title </h1></div>
            <hr>
            <div id="Book's Title" class="title">
            <img src="images/index.jpeg" alt="Book cover">
            <h2> Author </h2>
                
            <p> Book description </p>
            <h3> Genre </h3>
            <h4> Publishing info </h4>
            <h5> Book Rating </h5>
            <h6> Rating </h6>
      

            </div>
        </section>   

        <div id="end_body"></div>  
    </body>

<?php
require "footer.php";
echo "</html>";
mysqli_close($mysqli);
?>  

