<?php
ob_start();
session_start();

$database = include('config.php');

// Create connection
$mysqli = new mysqli($database['host'], $database['user'], $database['pass'], $database['name']);

// Check connection
if (mysqli_connect_error()) {
    die("Database connection failed: " . mysqli_connect_error());
}

/* @var $comment type */
$rating = $_POST['formRating'];
$total_rating = "UPDATE books SET total_rating = total_rating + $rating WHERE book_id = 1465464867";
$mysqli->query($total_rating);
$times_rated_increment = "UPDATE books SET times_rated = times_rated + 1 WHERE book_id = 1465464867";
$mysqli->query($times_rated_increment);
$sql = "UPDATE books SET b_rate = total_rating/times_rated WHERE book_id = 1465464867";
$mark_as_rated = "UPDATE books_users SET rated_book = 1 WHERE book_id = 1465464867";
$mysqli->query($mark_as_rated);
if ($mysqli->query($sql) === TRUE) {
    echo "New rating created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $mysqli->error;
}
?>


  
