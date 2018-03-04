<?php
ob_start();
session_start();

/*$servername = "db720121368.db.1and1.com";
$username = "dbo720121368";
$password = "TeamSeven7@";
$dbname = "db720121368";
*/
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookstore";

// Create connection
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check connection
if (mysqli_connect_error()) {
    die("Database connection failed: " . mysqli_connect_error());
}

/* @var $comment type */
$comment = $_GET['bookcomments'];
$date = date("y-m-d");
$sql = "INSERT INTO comments (user_id, book_id, comment, c_date)
VALUES ('6','5', '$comment', '$date')";
if ($mysqli->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $mysqli->error;
}
?>


  
