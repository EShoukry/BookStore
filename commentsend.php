
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
VALUES (" . $_SESSION['user'] . "," . $_SESSION['current_book_id'] . ",'$comment', '$date', '1')";
}
else
{
$sql = "INSERT INTO comments (user_id, book_id, comment, c_date, anon_check)
VALUES (" . $_SESSION['user'] . "," . $_SESSION['current_book_id'] . ",'$comment', '$date', '0')";
}
if ($mysqli->query($sql) === TRUE) {
    $url='http://watchwinners.com/bookstore/bookdetails.php?b_id=' . $_SESSION['current_book_id'] ;
   echo '<META HTTP-EQUIV=REFRESH CONTENT="0; '.$url.'">';
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



