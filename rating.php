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
$rated_book = "SELECT rated_book FROM books_users WHERE user_id =" . $_SESSION['user'] . " AND book_id =" . $_SESSION['current_book_id'];
$query = $mysqli->query($rated_book);
$did_they_rate = $query->fetch_assoc();
if(isset($_POST['formRating']))
$rating = $_POST['formRating'];
if ($did_they_rate['rated_book'] == 0)
{
$total_rating = "UPDATE books SET total_rating = total_rating + $rating WHERE book_id =" . $_SESSION['current_book_id'];
$mysqli->query($total_rating);
$times_rated_increment = "UPDATE books SET times_rated = times_rated + 1 WHERE book_id = " . $_SESSION['current_book_id'];
$mysqli->query($times_rated_increment);
$sql = "UPDATE books SET b_rate = total_rating/times_rated WHERE book_id =" . $_SESSION['current_book_id'];
$mark_as_rated = "UPDATE books_users SET rated_book = 1 WHERE book_id =" . $_SESSION['current_book_id'] . " AND user_id = ". $_SESSION['user'];
$store_rating = "UPDATE books_users SET book_rating = $rating WHERE book_id =" . $_SESSION['current_book_id'] . " AND user_id = ". $_SESSION['user'];
$mysqli->query($mark_as_rated);
$mysqli->query($store_rating);
}
else
{
$old_rating = "SELECT book_rating FROM books_users WHERE book_id =" . $_SESSION['current_book_id'] . " AND user_id = " . $_SESSION['user'];
$subtract = $mysqli->query($old_rating);
$subtract_rating = $subtract->fetch_assoc();
$total_rating = "UPDATE books SET total_rating = total_rating - ". $subtract_rating['book_rating'] ." WHERE book_id=" . $_SESSION['current_book_id'];
$mysqli->query($total_rating);
$times_rated_decrement = "UPDATE books SET times_rated = times_rated - 1 WHERE book_id = " . $_SESSION['current_book_id'];
$mysqli->query($times_rated_decrement);
$sql = "UPDATE books SET b_rate = total_rating/times_rated WHERE book_id =" . $_SESSION['current_book_id'];
$mark_as_unrated = "UPDATE books_users SET rated_book = 0 WHERE book_id =" . $_SESSION['current_book_id']. " AND user_id = ". $_SESSION['user'];
$mysqli->query($mark_as_unrated);
$delete_rating = "UPDATE books_users SET book_rating = 0 WHERE book_id =" . $_SESSION['current_book_id'] . " AND user_id = ". $_SESSION['user'];
$mysqli->query($delete_rating);

}
if ($mysqli->query($sql) === TRUE) {
    $url='http://watchwinners.com/bookstore/bookdetails.php?b_id=' . $_SESSION['current_book_id'] ;
   echo '<META HTTP-EQUIV=REFRESH CONTENT="0; '.$url.'">';
} else {
    echo "Error: " . $sql . "<br>" . $mysqli->error;
}
?>



