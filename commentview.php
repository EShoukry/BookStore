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



//Getting the information send by the forms related to the pages and sorting 

$view = "SELECT user_id, comment, c_date FROM comments WHERE book_id = $_SESSION[book_id_test]";
$result = $mysqli->query($view);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo ""
        . "Name: " . $row["user_id"]. " " . "        Date: " . $row["c_date"].
                "<br>".
                "<br>".
                "<br>".
                "<br>"
                .$row["comment"]. 
                "<br>".
                "<br>".
                "<br>".
                "<br>".
                "<br>".
                "<br>";
        
    }
} else {
    echo "0 results";
}

    require "footer.php";
    echo "</html>";
    mysqli_close($mysqli);
    ?>