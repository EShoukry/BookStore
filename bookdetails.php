<?php
ob_start();
session_start();

function phpAlert($msg) {
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}

// Create connection
$dbConfig = include('config.php');
$mysqli = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['name']);


if (mysqli_connect_error()) {
    die("Database connection failed: " . mysqli_connect_error());
}

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
$publishing_info = $mysqli->query("SELECT b_pub_name FROM books WHERE book_id = '".$book_id."'");
$book_rating = $mysqli->query("SELECT b_rate FROM books WHERE '$book_id' = book_id");
$image = $mysqli->query("SELECT b_picture FROM books WHERE '$book_id' = book_id");

$b_nam = $book_name->fetch_assoc();
$b_aut = $author_bio->fetch_assoc();
$b_desc = $book_description->fetch_assoc();
$b_gen = $book_genre->fetch_assoc();
$b_pub = $publishing_info->fetch_assoc();
$b_rat = $book_rating->fetch_assoc();
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
        
        <section>
           <?php
            
            echo "<div class=section_title><h1>".$b_nam['b_name']."</h1></div>";
            echo "<hr>";
            echo "<div id='Book's Title' class='title'>";
            echo "<img class='img' src='".$b_img['b_picture']."' alt='Book cover'>";
            echo "<h2> Author: ".$b_aut['a_name']."</h2>";
            echo "<p> Biography: ".$b_aut['a_bio']."</p>";
            echo "<p> Book description: ".$b_desc['b_description']." </p>";
            echo "<h3> Genre: ".$b_gen['b_genre']." </h3>";
            echo "<h4> Publishing info: ".$b_pub['b_pub_name']." </h4>";
            echo '<h5> Rating: </h5> <div class="book_rate"><img src="images/'.$b_rat["b_rate"].'stars.png"></div></br></br>';
           
            if (isset($_SESSION['user']) != "")
            {
                $_SESSION['current_book_id'] = $book_id;
                $owns_book = "SELECT b1.book_id, b2.b_rate FROM books_users b1, books b2 WHERE b1.user_id =" 
                    . $_SESSION['user'] . " AND b1.book_id = b2.book_id AND b1.book_id =" . $book_id;
                $result = $mysqli->query($owns_book);
                $book_id_testing = $result->fetch_assoc();

        if ($book_id_testing['book_id'] == $book_id)
        {
            echo <<< END_OF_TEXT
            <html>
                <head>
                    <meta charset="utf-8">    
                        <form method="post" action="commentsend.php">
                            <input type="radio" name="AnonOrNickname" value = "anon"> Anonymous<br>
                            <input type="radio" name="AnonOrNickname" value = "nick"> Username<br>
                            <label><b>Comment on this book!</b></label>
                            </br>
                            </br>
                            <textarea name= "bookcomments" placeholder="Comment Here" style="width:500px; height:200px;"></textarea>
                            <input type="submit" name ="submit" value="submit">
                            </br>
                        </form>
                </head>
            </html>
END_OF_TEXT;
            
            $rated_book = "SELECT rated_book FROM books_users WHERE user_id =" . $_SESSION['user'] . " AND book_id =" . $book_id_testing['book_id'];
            $query = $mysqli->query($rated_book);
            $did_they_rate = $query->fetch_assoc();

            if ($did_they_rate['rated_book'] == 0)
            {
                echo <<< END_OF_TEXT
                <html>
                    <form action="rating.php" method="post">
                        <label><b>Rate this book!</b></label>
                        </br>
                        </br>
                        <p>   
                            <button classname="dummy"
                                    type="submit" 
                                    name="formRating" 
                                    value="1"
                                    id= "book_rate">
                                <div class="book_rate"><img src="images/ratingbutton.png"></div>
                            </button>
                            <button classname="dummy"
                                    type="submit" 
                                    name="formRating" 
                                    value="2"
                                    id= "book_rate">
                                <div class="book_rate"><img src="images/ratingbutton.png"></div>
                            </button>
                            <button classname="dummy"
                                    type="submit" 
                                    name="formRating" 
                                    value="3"
                                    id= "book_rate">
                                <div class="book_rate"><img src="images/ratingbutton.png"></div>
                            </button>
                            <button classname="dummy"
                                    type="submit" 
                                    name="formRating" 
                                    value="4"
                                    id= "book_rate">
                                <div class="book_rate"><img src="images/ratingbutton.png"></div>
                            </button>
                            <button classname="dummy"
                                    type="submit" 
                                    name="formRating" 
                                    value="5"
                                    id= "book_rate">
                                <div class="book_rate"><img src="images/ratingbutton.png"></div>
                            </button>
                        </p>
                        </br>
                        
                    </form>
END_OF_TEXT;
            }
            else 
            {
                echo <<< END_OF_TEXT
                    <html>
                        <form action="rating.php" method="POST"> 
                            <input type=submit value="Clear Rating"> 
                    </form>
END_OF_TEXT;
            }
        } 
        else
        {
            echo <<< END_OF_TEXT
            <html>
                <head>
                    <meta charset="utf-8">    
                        <title>Book Store </title>
                            <meta http-equiv="content-type" content="text/plain">
                            <link rel="stylesheet" type="text/css" href="css/styles.css">
                </head>
                <body>
                    <head>
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                    
                    </head>
END_OF_TEXT;
        }
            }
            else 
            {
                echo <<< END_OF_TEXT
                <html>
                <head>
                    <meta charset="utf-8">    
                    <title>Book Store </title>
                    <meta http-equiv="content-type" content="text/plain">
                    <link rel="stylesheet" type="text/css" href="css/styles.css">
                </head>
                <body>
                <head>
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

                </head>
                <body>
END_OF_TEXT;
}
        echo "</div>";
        $view = "SELECT user_id, comment, c_date, anon_check FROM comments WHERE book_id = '$book_id'";
        $nickname_retrieval = "SELECT t1.u_nick FROM users t1, comments t2 WHERE t2.user_id = t1.user_id_number";
        $result = $mysqli->query($view);
        $result2 =$mysqli->query($nickname_retrieval);

if ($result->num_rows > 0) {
    echo "<label><b>Comments</b></label> </br>";
    while($row = $result->fetch_assoc()) {
        if($row["anon_check"]==0)
        {
        $nickname = $result2->fetch_assoc();
        echo ""
        . "<div id='container'>"
                . "<p class='solid'>". $nickname['u_nick'] . "<br>" . $row["c_date"].
                "<br>".
                "<br>".
                $row["comment"]. 
                "<br>".
                "<br>".
                "<br>".
                "<br>".
                "<br>".
                "<br>". 
                "<br>".
                 "<br></p></div>";
        }
        else {
            
            echo ""
        ."<div id='container'>"
                    . "<p class='solid'>Anonymous      <br>" . $row["c_date"].
                "<br>".
                "<br>".
                $row["comment"]. 
                "<br>".
                "<br>".
                "<br>".
                "<br>".
                "<br>".
                "<br>".
                "<br></p></div>"; 
        }
    }
} else {
    echo "0 results";
}
?>

            
        </section>   

        <div id="end_body"></div>  
    </body>

<?php
require "footer.php";
echo "</html>";
mysqli_close($mysqli);
?>  

