<?php
$servername = "db720121368.db.1and1.com";
$username = "dbo720121368";
$password = "TeamSeven7@";
$dbname = "db720121368";

//$servername = "localhost";
//$username = "root";
//$password = "";
//$dbname = "bookstore";

// Create connection
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check connection
if (mysqli_connect_error()) {
    die("Database connection failed: " . mysqli_connect_error());
}

//Getting the information send by the forms related to the pages and sorting 
$sort_values = ['book title', 'author', 'price', 'book rating', 'release date'];
$sort_sql_values = ['b_name', 'author_id', 'b_price', 'b_rate', 'b_release'];
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
            <div class=section_title><h1> Library</h1></div>
            <hr>
            <div id="Library" class="book_genre">  


                <?php
                //Query to get the book information
                if ($sort_sql_values[$sort] == 'author_id') {
                    $result = $mysqli->query("SELECT DISTINCT books.book_id, b_name, b_price, b_picture, b_description, b_rate FROM books, books_authors, authors WHERE books.book_id = books_authors.book_id AND authors.author_id= books_authors.author_id ORDER BY authors.a_name        LIMIT " . $pages_values[$page]);
                } else {
                    $result = $mysqli->query("SELECT book_id, b_name, b_price, b_picture, b_description, b_rate FROM books ORDER BY " . $sort_sql_values[$sort] . " LIMIT " . $pages_values[$page]);
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

