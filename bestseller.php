<?php
            $servername = "db720121368.db.1and1.com";
            $username = "dbo720121368";
            $password = "TeamSeven7@";
            $dbname = "db720121368"; 
        
            /*$servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "bookstore"; */

            // Create connection
            $mysqli = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if (mysqli_connect_error()) {
                die("Database connection failed: " . mysqli_connect_error());
            } 

//Getting the information send by the forms related to the pages and sorting 
$sort_values = ['book title' , 'author', 'price', 'book rating', 'release date'];
$sort_sql_values = ['b_name' , 'author_id', 'b_price', 'b_rate', 'b_release'];
$pages_values =['10', '20', '100'];


$initial_row = 0;
$offset=100;
$current_page=1;
if(isset($_POST['tp'])){
    $total_pages = $_POST['tp'];    
}else{
    $total_pages=1;    
}



if (isset($_POST['sort_select'])) {
    $sort = $_POST['sort_select'];
} else {
    $sort = '0';
}
 
if (isset($_POST['pages'])) {
    $page = $_POST['pages'];
    $offset = $pages_values[$page];    
} else {
    $page = '2';
}

if (isset($_POST['bt_1'])) {  //First page case 
    $initial_row = 0;
    $current_page=1;
    
}elseif(isset($_POST['bt_2'])){  //Previous page case
    $current_page = ($_POST['bt_2']<1) ? 1 : $_POST['bt_2'];
    $initial_row = ($current_page * $offset)-$offset;
}elseif(isset($_POST['bt_other'])){  //Other page number
    $current_page = $_POST['bt_other'];
    $initial_row = (($current_page * $offset)-$offset);
}elseif(isset($_POST['bt_3'])){ // Next Page case    
     $current_page = ($_POST['bt_3']>$total_pages) ? $total_pages : $_POST['bt_3'];
     $initial_row = (($current_page * $offset)-$offset);
}elseif(isset($_POST['bt_4'])){  //Last page case
    $current_page = $total_pages;
    $initial_row = ($total_pages * $offset)-$offset;
}

?>


<!doctype html>
<html>
  <head>
    <meta charset="utf-8">    
    <title>Best Sellers </title>
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
                        for($i=0; $i<count($sort_values); $i++){
                            echo '<option value="'.$i.'"';
                            if($i==$sort)
                                echo"selected";
                            echo '>'.$sort_values[$i].'</option>';
                        }
                    ?>
                    
                </select>
                </div>  
                
                <div id="pages">        
                     <label for="sort_select">View: </label>
                      <?php  
                        for($i=0; $i<count($pages_values); $i++){
                            echo '<input type="radio" name="pages" value="'.$i.'"';
                            if($i==$page)
                                echo'checked="checked"';
                             echo 'onchange="this.form.submit()">' . ($i == 2 ? 'all' : $pages_values[$i]);
                        }
                    
                      //Query to get the book information
                    if ($sort_sql_values[$sort] == 'author_id') {
                        $result = $mysqli->query("SELECT DISTINCT books.book_id, b_name, b_price, b_picture, b_description, b_rate FROM books, books_authors, authors WHERE books.book_id = books_authors.book_id AND authors.author_id= books_authors.author_id ORDER BY books.b_times_sold DESC, authors.a_name");
                        
                    } else {
                        $result = $mysqli->query("SELECT book_id, b_name, b_price, b_picture, b_description, b_rate FROM books ORDER BY books.b_times_sold DESC," . $sort_sql_values[$sort]);
                        
                    }
                    
                    $total_pages = ceil($result->num_rows/$pages_values[$page]);
                    echo '<input type=hidden name=tp value="'.$total_pages.'">';
                    $_POST['TP']=$total_pages;
                    echo "<span>&nbsp; &nbsp; &nbsp; Pages: </span>";
                    echo '<button value="0" name="bt_1" onchange="this.form.submit()">&lt&lt</button> ';
                    echo '<button value="'.($current_page-1).'" name="bt_2" onchange="this.form.submit()">&lt</button> ';
                    for($i = 0; $i < $total_pages || $i>2; $i++){
                        echo '<button value="'.($i+1).'" name="bt_other" onchange="this.form.submit()">'.($i+1).'</button>  ';
                    }
                    
                    echo '<button value="'.($current_page+1).'" name="bt_3" onchange="this.form.submit()">></button>  ';
                    echo '<button value="'.$total_pages.'" name="bt_4" onchange="this.form.submit()">>></button> ';
                     ?>                          
                </div>                
            </form>       
       
         
    </div>
    <section>
            <div class=section_title><h1> Best Sellers</h1></div>
            <hr>
            <div id="best" class="book_genre">  


                <?php
                //Query to get the book information
                if ($sort_sql_values[$sort] == 'author_id') {
                    $result = $mysqli->query("SELECT DISTINCT books.book_id, b_name, b_price, b_picture, b_description, b_rate FROM books, books_authors, authors WHERE books.book_id = books_authors.book_id AND authors.author_id= books_authors.author_id ORDER BY books.b_times_sold DESC, authors.a_name        LIMIT " . $initial_row.",". $offset);
                } else {
                    $result = $mysqli->query("SELECT book_id, b_name, b_price, b_picture, b_description, b_rate FROM books ORDER BY books.b_times_sold DESC," . $sort_sql_values[$sort] . " LIMIT ". $initial_row.",".$offset);
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