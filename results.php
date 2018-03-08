<?php
// Create connection
$dbConfig = include('config.php');
$mysqli = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['name']);

// Check connection 
if (mysqli_connect_error()) {
    die("Database connection failed: " . mysqli_connect_error());
}

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
    <body>
        <?php
        require "header.php";
        ?>
        
        <section>
            <div class=section_title><h1> Results</h1></div>
            <hr>
            <div id="Results" class="book_genre">  


                <?php
                if(!isset($_POST['search']) || ($_POST['search']==''))
                   echo '0 Results for this serch';
                else{
                    //Query to get the book information
                
                    $result = $mysqli->query("SELECT DISTINCT books.book_id, b_name, b_price, b_picture, b_description, b_rate FROM books, books_authors, authors WHERE books.book_id = books_authors.book_id AND authors.author_id= books_authors.author_id and (books.b_name LIKE '%".$_POST['search']."%' or authors.a_name LIKE '%".$_POST['search']."%')");
                

                $GLOBALS["result"] = $result;
                require "includes/books_shown.php";
                    
                }
               
                ?>

            </div> 
        </section>
        
        
        <div id="end_body"></div>  
    </body>
</html>   