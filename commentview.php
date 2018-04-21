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

$view = "SELECT user_id, comment, c_date, anon_check FROM comments WHERE book_id =" . $_SESSION['book_id_test'];
$nickname_retrieval = "SELECT t1.u_nick FROM users t1, comments t2 WHERE t2.user_id = t1.user_id_number";

$result = $mysqli->query($view);
$result2 =$mysqli->query($nickname_retrieval);


if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if($row["anon_check"]==0)
        {
        $nickname = $result2->fetch_assoc();
        echo ""
        . "Name: " . $nickname['u_nick'] . "<br>        Date: " . $row["c_date"].
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
                "<br>".
                "_____________________________________________". 
                "<br>".
                 "<br>";
        }
        else {
            
            echo ""
        . "Name: Anonymous      <br>    Date: " . $row["c_date"].
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
                "_____________________________________________".
                "<br>".
                "<br>";
        }
    }
} else {
    echo "0 results";
}

    require "footer.php";
    echo "</html>";
    mysqli_close($mysqli);
    ?>