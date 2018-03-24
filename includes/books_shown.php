<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="scripts/js/buttonClick.js"></script>
</head> 

<?php 
          session_start();
          if (!$GLOBALS["result"]) {
                die('Invalid Query: ' . mysql_error());
            }

            
            if ($GLOBALS["result"]->num_rows > 0) { 
                // output data of each row
                while($row = $GLOBALS["result"]->fetch_assoc()) {          
                    
                                    
                    //Quary to obtain the authors base on the book id
                    $result1 = $mysqli->query("SELECT a_name FROM authors, books_authors WHERE books_authors.book_id='".$row["book_id"]."' AND books_authors.author_id= authors.author_id");
                    
                    if (!$result1) {
                        die('Invalid Query 1: ' . mysql_error());
                    }
                    
                    echo '<div class="book_container">';
                    echo '<div class="book_cover"><img src="'.$row["b_picture"].'" class="cover_img"></div>';         
                    echo '<div class="book_name">'.$row["b_name"].'</div>';
                    echo '<div class="book_author"><span>author</span>';
                    
                    $temp_count = $result1->num_rows;
                    while($row1 = $result1->fetch_assoc()){
                        echo $row1["a_name"]; 
                        if($temp_count >1){
                            echo ", ";
                            $temp_count--;
                        }
                    }
                    
                    echo '</div>';        
                    echo '<div class="book_rate"><img src="images/'.$row["b_rate"].'stars.png"></div>';           
                    echo '<div class="book_price">$'.$row["b_price"].'</div>';  
                    
                    
                    if (isset($_SESSION['user']) != "") {
                        echo $_SESSION["user"];
                        ?>
                        <input class="book_input_add_to_cart" 
                               type="image" 
                               src="images/shoppingCartAdd.png" 
                               onclick="addBookToUserCart(<?php echo $row["book_id"] ?>, <?php echo $_SESSION['user']; ?> ,false)"/>
                        
                        <?php
                    }
                    echo '</div>' ;     
                }
            } else {
                echo "0 results";
            }

?>