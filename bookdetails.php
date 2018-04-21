
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
            echo "<img class ='img cover' src='".$b_img['b_picture']."' height='500' width = '400' alt='Book cover'>";
            echo "<h2> Author: ".$b_aut['a_name']."</h2>";
            echo "<p> Biography: ".$b_aut['a_bio']."</p>";
            echo "<p> Book description: ".$b_desc['b_description']." </p>";
            echo "<h3> Genre: ".$b_gen['b_genre']." </h3>";
            echo "<h4> Publishing info: ".$b_pub['b_pub_name']." </h4>";
            echo "<h5> Book Rating: ".$b_rat['b_rate']." </h5>";
            echo "</div>";

            echo '<h5> Rating: </h5> <div class="book_rate"><img src="images/'.$b_rat["b_rate"].'stars.png"></div></br></br>';
            
            if (isset($_SESSION['user']) != "")
            {
                $_SESSION['current_book_id'] = $book_id;
                $owns_book = "SELECT b1.book_id, b2.b_rate FROM books_users b1, books b2 WHERE b1.user_id =" 
                    . $_SESSION['user'] . " AND b1.book_id = b2.book_id AND b1.book_id = '$book_id'";
                $result = $mysqli->query($owns_book);
                $book_id_testing = $result->fetch_assoc();
                $rated_book = "SELECT rated_book FROM books_users WHERE user_id =" . $_SESSION['user'] . " AND book_id = '$book_id'";
                $query = $mysqli->query($rated_book);
                $did_they_rate = $query->fetch_assoc();
            
                if ($did_they_rate['rated_book'] != 0)
            {
                echo <<< END_OF_TEXT
                    <html>
                        <form action="rating.php" method="POST"> 
                            <input type=submit value="Clear Your Rating"> 
                    </form>
                    </br>
END_OF_TEXT;
            }
        if ($book_id_testing['book_id'] == $book_id || strcmp("$book_id_testing", "$book_id"))
        {
            echo <<< END_OF_TEXT
            <html>
                <head>
                    <meta charset="utf-8">    
                        <form method="post" action="commentsend.php">
                            <label><b>Comment on this book!</b></label></br>
                            <input type="radio" name="AnonOrNickname" value = "anon"> Anonymous<br>
                            <input type="radio" name="AnonOrNickname" value = "nick"> Username<br>
                                <style type="text/css">
            body {
                margin-right: 0;
                max-width: 50000px;
            margin-left: 30px;
                padding: 20px;
            }
                                textarea.text_box{
                                    background-color:#ffffff;
                                    border-width:1;
                                    border-style:solid;
                                    border-color:#cccccc;
                                    font-family:Arial;
                                    font-size:12pt;
                                    color:#000000;}
                                    input.text_box{
            
                                    background-color:#ffffff;
                                    font-family:Arial;
                                    font-size:12pt;
                                    color:#000000;}
                                    </style>
                                    <textarea name="bookcomments" cols="40" rows="8" class="text_box">Comment here...</textarea><br>
                                    <input type="submit" value="Submit" class="text_box"><input type="reset" value="Clear" class="text_box">
                        </form>
                </head>
            </html>
END_OF_TEXT;
            
            

            if ($did_they_rate['rated_book'] == 0)
            {
                echo <<< END_OF_TEXT
                
                <html>
                <style>
                .button {
    background-color: beige;
    border: none;
                opacity: 0.6;
  transition: 0.3s;
    color: white;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    cursor: pointer;
}

                .button:hover {opacity: 1}
                </style>
                    <form action="rating.php" method="post">
                        <label><b>Rate this book!</b></label>
                        </br>
                        </br>
                        <p>   
                            <button class="button"
                                    type="submit" 
                                    name="formRating" 
                                    value="1"
                                    >
                                <img src="images/ratingbutton.png?34" width = "50" height = "50"></div>
                            </button>
                            <button class="button"
                                    type="submit" 
                                    name="formRating" 
                                    value="2"
                                    id= "book_rate">
                                <div><img src="images/ratingbutton.png?3" width = "50" height = "50"></div>
                            </button>
                            <button class="button"
                                    type="submit" 
                                    name="formRating" 
                                    value="3"
                                    id= "book_rate">
                                <div ><img src="images/ratingbutton.png?3" width = "50" height = "50"></div>
                            </button>
                            <button class="button"
                                    type="submit" 
                                    name="formRating" 
                                    value="4"
                                    id= "book_rate">
                                <div><img src="images/ratingbutton.png?3" width = "50" height = "50"></div>
                            </button>
                            <button class="button"
                                    type="submit" 
                                    name="formRating" 
                                    value="5"
                                    id= "book_rate">
                                <div><img src="images/ratingbutton.png?3" width = "50" height = "50"></div>
                            </button>
                        </p>
                        </br>
                </br>
                        </div>
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
    
    echo "
        <html>
        <style>
        div.a {
    font-size: 30px;
    padding-top: 10px;
    
}
</style>
</html>
<div class='a' > Comments Section </div>";
    while($row = $result->fetch_assoc()) {
        if($row["anon_check"]==0)
        {
        $nickname = $result2->fetch_assoc();
        $space = "              ";   
        echo <<< END_OF_TEXT
   <html>
       <style>
   body {
                margin-right: 0;
                max-width: 50000px;
                padding: 0 20px;
            }

.container {
    border: 2px solid #dedede;
    border-top: 5px solid #dedede;
    background-color: #f1f1f1;
    border-radius: 5px;
    padding-top: 0px;
    padding-left: 0px;
    padding-right: 0px;
    padding-bottom: 30px;
    margin: 20px;
    margin-right: 900px;
        
}
.name{
        font-size: 30px;
   }
.comment{
        padding-left: 20px;
        font-size: 20px;
        }
.darker {
    border-color: #ccc;
    background-color: #ddd;
        
        
}
    .date-right {
    float: right;
    color: #000000;
}
        </style>
        </html>
        
<div class="container darker">
  <p class="name" style = "background-color: #ccc;">$space $space $nickname[u_nick]  <span class="date-right" style = "background-color: #ccc;"> $row[c_date]</span></p>
                <br>
                <br>
                <p class = "comment" style = "background-color: #ddd;"> $row[comment]</p>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br> 
                <br>
                <br></p>
  
</div>
        
                
               
END_OF_TEXT;
        }
        else {
            
        echo <<< END_OF_TEXT
                <html>
       <style>
   body {
                margin-right: 0;
                max-width: 50000px;
                padding: 0 20px;
            }

.container {
    border: 2px solid #dedede;
    border-top: 5px solid #dedede;
    background-color: #f1f1f1;
    border-radius: 5px;
    padding-top: 0px;
    padding-left: 0px;
    padding-right: 0px;
    padding-bottom: 30px;
    margin: 20px;
    margin-right: 900px;
        
}
.name{
        font-size: 30px;
   }
.comment{
        padding-left: 20px;
        font-size: 20px;
        }
.darker {
    border-color: #ccc;
    background-color: #ddd;
        
        
}
    .date-right {
    float: right;
    color: #000000;
}
        </style>
        </html>
        
<div class="container darker">
  <p class="name" style = "background-color: #ccc;">Anonymous  <span class="date-right" style = "background-color: #ccc;"> $row[c_date]</span></p>
                <br>
                <br>
                <p class = "comment" style = "background-color: #ddd;"> $row[comment]</p>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br> 
                <br>
                <br></p>
  
</div>
        
END_OF_TEXT;
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

