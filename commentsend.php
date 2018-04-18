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
$comment = $_POST['bookcomments'];
$date = date("y-m-d");
$sql = "INSERT INTO comments (user_id, book_id, comment, c_date)
VALUES ($_SESSION[user], $_SESSION[book_id_test],'$comment', '$date')";
if ($mysqli->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $mysqli->error;
}
?>


  
