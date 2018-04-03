<?php

ob_start();
session_start();
require("details.php")

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

// Arbitrarily picking an author id to show functionality of this page.
$author_id = 12;

// Query to get author's name and bio
$author_name_bio = $mysqli->query("SELECT a_name, a_bio FROM authors WHERE $author_id = author_id");

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">    
        <title>Book Store </title>
        <meta http-equiv="content-type" content="text/plain">
        <link rel="stylesheet" type="text/css" href="css/styles.css">
        <script src="scripts/main.js"></script>
    </head>
    <title> "Book's info" </title>
    <body>
        <?php
        require "header.php";
        ?>
        <div id=main_image>
            <img src="images/index.jpeg" alt="Team 7 book store" >
        </div>  
        <section>
            <hr>
            <h2> Author's name </h2>
            <h3> Author's bio </h3>
        </section>   

        <div id="end_body"></div>  
    </body>


<?php
require "footer.php";
echo "</html>";
mysqli_close($mysqli);
?>
