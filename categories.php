i<?php
// Create connection
$dbConfig = include('config.php');
$mysqli = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['name']);

session_start();

// Check connection
if (mysqli_connect_error()) {
    die("Database connection failed: " . mysqli_connect_error());
}

//Getting the information send by the forms related to the pages and sorting 
$sort_values = ['book title --- A-Z','book title --- Z-A', 'author --- A-Z', 'author --- Z-A', 'price --- Low-High','price --- High-Low', 'book rating --- Low-High', 'book rating --- High-Low','release date --- Old-New', 'release date --- New-Old'];
$sort_sql_values = ['b_name', 'b_name', 'author_id', 'author_id', 'b_price', 'b_price','b_rate', 'b_rate', 'b_release', 'b_release'];
$pages_values = ['10', '20', '100'];


if (isset($_POST['sort_select'])) {
    $sort = $_POST['sort_select'];
} else {
    $sort = '0';
}

if (isset($_POST['pages'])) {
    $page = $_POST['pages'];
} else {
    $page = '2';
}

/* echo $GLOBALS['sort'];
  echo $GLOBALS['page']; */
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
        <div id=main_image>
            <img src="images/index.jpeg" alt="Team 7 book store" >
        </div>  

        <div id="sort_container">    


            <form action="#" method="POST" id="sort_form">
                <div id="sort">
                    <label for="sort_select">Sort by: </label>
                    <select name="sort_select" id="sort_option" onchange="this.form.submit()">
                        <?php
                        for ($i = 0; $i < count($sort_values); $i++) {
                            echo '<option value="' . $i . '"';
                            if ($i == $sort)
                                echo"selected";
                            echo '>' . $sort_values[$i] . '</option>';
                        }
                        ?>

                    </select>
                </div>  

                <div id="pages">        
                    <label for="sort_select">View: </label>
                    <?php
                    for ($i = 0; $i < count($pages_values); $i++) {
                        echo '<input type="radio" name="pages" value="' . $i . '"';
                        if ($i == $page)
                            echo'checked="checked"';
                        echo 'onchange="this.form.submit()">' . $pages_values[$i];
                    }
                    ?>                          
                </div>                
            </form>       


        </div>
        

        <section>
            <div class=section_title><h1> Fantasy</h1></div>
            <hr>
            <div id="Fantasy" class="book_genre">

                <?php
                //Query to get the book information
                if ($sort_sql_values[$sort] == 'author_id') {
                    if($sort%2==0){
                        $result = $mysqli->query("SELECT DISTINCT books.book_id, b_name, b_price, b_picture, b_description, b_rate FROM books, books_authors, authors WHERE b_genre='Fantasy' AND books.book_id = books_authors.book_id AND authors.author_id= books_authors.author_id ORDER BY authors.a_name ASC LIMIT " . $pages_values[$page]);
                    }else{
                         $result = $mysqli->query("SELECT DISTINCT books.book_id, b_name, b_price, b_picture, b_description, b_rate FROM books, books_authors, authors WHERE b_genre='Fantasy' AND books.book_id = books_authors.book_id AND authors.author_id= books_authors.author_id ORDER BY authors.a_name DESC LIMIT " . $pages_values[$page]);
                    }
                } else {
                     if($sort%2==0){
                         $result = $mysqli->query("SELECT book_id, b_name, b_price, b_picture, b_description, b_rate, b_genre FROM books WHERE b_genre='Fantasy' ORDER BY " . $sort_sql_values[$sort] . " ASC LIMIT " . $pages_values[$page]);
                     }else{
                         $result = $mysqli->query("SELECT book_id, b_name, b_price, b_picture, b_description, b_rate, b_genre FROM books WHERE b_genre='Fantasy' ORDER BY " . $sort_sql_values[$sort] . " DESC LIMIT " . $pages_values[$page]);   
                     }
                }


                $GLOBALS["result"] = $result;
                require "includes/books_shown.php";
                ?>        

            </div>
        </section>   

        <section>
            <div class=section_title><h1> Health and Fitness</h1></div>
            <hr>
            <div id="Health_and_Fitness" class="book_genre">

                <?php
                //Query to get the book information
                if ($sort_sql_values[$sort] == 'author_id') {
                     if($sort%2==0){
                         $result = $mysqli->query("SELECT DISTINCT books.book_id, b_name, b_price, b_picture, b_description, b_rate FROM books, books_authors, authors WHERE b_genre='Health_Fitness' AND books.book_id = books_authors.book_id AND authors.author_id= books_authors.author_id ORDER BY authors.a_name ASC LIMIT " . $pages_values[$page]);
                     }else{
                          $result = $mysqli->query("SELECT DISTINCT books.book_id, b_name, b_price, b_picture, b_description, b_rate FROM books, books_authors, authors WHERE b_genre='Health_Fitness' AND books.book_id = books_authors.book_id AND authors.author_id= books_authors.author_id ORDER BY authors.a_name DESC LIMIT " . $pages_values[$page]);
                     }
                } else {
                    if($sort%2==0){
                        $result = $mysqli->query("SELECT book_id, b_name, b_price, b_picture, b_description, b_rate, b_genre FROM books WHERE b_genre='Health_Fitness' ORDER BY " . $sort_sql_values[$sort] . " ASC LIMIT " . $pages_values[$page]);
                    }else{
                         $result = $mysqli->query("SELECT book_id, b_name, b_price, b_picture, b_description, b_rate, b_genre FROM books WHERE b_genre='Health_Fitness' ORDER BY " . $sort_sql_values[$sort] . " DESC LIMIT " . $pages_values[$page]);
                    }
                }


                $GLOBALS["result"] = $result;
                require "includes/books_shown.php";
                ?>        

            </div>
        </section> 

        <section>
            <div class=section_title><h1> Science Fiction</h1></div>
            <hr>
            <div id="Science_Fiction" class="book_genre">

                <?php
                //Query to get the book information
                if ($sort_sql_values[$sort] == 'author_id') {
                    if($sort%2==0){
                        $result = $mysqli->query("SELECT DISTINCT books.book_id, b_name, b_price, b_picture, b_description, b_rate FROM books, books_authors, authors WHERE b_genre='Science Fiction'AND books.book_id = books_authors.book_id AND authors.author_id= books_authors.author_id ORDER BY authors.a_name ASC LIMIT " . $pages_values[$page]); 
                    }else{
                        $result = $mysqli->query("SELECT DISTINCT books.book_id, b_name, b_price, b_picture, b_description, b_rate FROM books, books_authors, authors WHERE b_genre='Science Fiction' AND books.book_id = books_authors.book_id AND authors.author_id= books_authors.author_id ORDER BY authors.a_name DESC LIMIT " . $pages_values[$page]);
                    }
                } else {
                    if($sort%2==0){
                        $result = $mysqli->query("SELECT book_id, b_name, b_price, b_picture, b_description, b_rate, b_genre FROM books WHERE b_genre='Science Fiction' ORDER BY " . $sort_sql_values[$sort] . " ASC LIMIT " . $pages_values[$page]);
                    }else{
                        $result = $mysqli->query("SELECT book_id, b_name, b_price, b_picture, b_description, b_rate, b_genre FROM books WHERE b_genre='Science Fiction' ORDER BY " . $sort_sql_values[$sort] . " DESC LIMIT " . $pages_values[$page]);
                    }
                }


                $GLOBALS["result"] = $result;
                require "includes/books_shown.php";
                ?>        

            </div>
        </section> 

        <div id="end_body"></div>  
    </body>

<?php
require "footer.php";
echo "</html>";
mysqli_close($mysqli);
?>

