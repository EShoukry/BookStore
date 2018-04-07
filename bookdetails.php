<?php

ob_start();
session_start();
//require(details.php);

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
           <?php
            
            echo "<div class=section_title><h1>".$b_nam['b_name']."</h1></div>";
            echo "<hr>";
            echo "<div id='Book's Title' class='title'>";
           // echo "<img src='images/index.jpeg' alt='Book cover'>";
            echo "<img src='".$b_img['b_picture']."' alt='Book cover'>";
            echo "<h2> Author: ".$b_aut['a_name']."</h2>";
            echo "<p> Biography: ".$b_aut['a_bio']."</p>";
            echo "<p> Book description: ".$b_desc['b_description']." </p>";
            echo "<h3> Genre: ".$b_gen['b_genre']." </h3>";
            echo "<h4> Publishing info: ".$b_pub['b_pub_name']." </h4>";
            echo "<h5> Book Rating: ".$b_rat['b_rate']." </h5>";
            echo "<h6> Comments: ".$b_com['comment']." </h6>";
            echo "</div>";
          ?>

            
        </section>   

        <div id="end_body"></div>  
    </body>

<?php
require "footer.php";
echo "</html>";
mysqli_close($mysqli);
?>  

