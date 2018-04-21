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

$anonORnickname = "";
$comment = $_POST['bookcomments'];
$date = date("y-m-d");
if(isset($_POST['AnonOrNickname']))
{
    $anonORnickname = test_input($_POST["AnonOrNickname"]);
}
if ($anonORnickname == "anon")
{
$sql = "INSERT INTO comments (user_id, book_id, comment, c_date, anon_check)
VALUES (" . $_SESSION['user'] . "," . $_SESSION['book_id_test'] . ",'$comment', '$date', '1')";
}
else
{
$sql = "INSERT INTO comments (user_id, book_id, comment, c_date, anon_check)
VALUES (" . $_SESSION['user'] . "," . $_SESSION['book_id_test'] . ",'$comment', '$date', '0')";
}
if ($mysqli->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $mysqli->error;
}
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>



