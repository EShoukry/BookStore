<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="scripts/js/buttonClick.js"></script>
</head> 

<?php
if (isset($_POST['add_book_to_cart'])) { //if adding book from main page or book details
    $bookId = $_POST["add_book_id"];
    $userId = $_POST["add_user_id"];

    $insertQuery = "INSERT INTO shoppingcart (book_id, user_id, b_quantity)"
            . " VALUES (\"" . $bookId . "\"," . $userId . "," . 1 . ");";

    if ($mysqli->query($insertQuery) == FALSE) {
        echo "ERROR: " . $insertQuery;
    }
}

if (!$GLOBALS["result"]) {
    die('Invalid Query: ' . mysql_error());
}

//echo '<form method="post" class = "book_form">';

if ($GLOBALS["result"]->num_rows > 0) {
    //if a user is logged on, find which books are already in that user's shopping cart
    $booksInUserCart;
    if (isset($_SESSION['user']) != "") {
        $booksInUserCart = $mysqli->query(""
                . "SELECT book_id FROM shoppingcart"
                . " WHERE user_id = " . $_SESSION['user']);
    }

    // output data of each row
    for ($i = 0; $i < $GLOBALS["result"]->num_rows; $i++) {
        $row = $GLOBALS["result"]->fetch_assoc();

        //Quary to obtain the authors base on the book id
        $result1 = $mysqli->query("SELECT a_name, books_authors.author_id FROM authors, books_authors WHERE books_authors.book_id='" . $row["book_id"] . "' AND books_authors.author_id= authors.author_id");

        if (!$result1) {
            die('Invalid Query 1: ' . mysql_error());
        }

        echo '<div class="book_container">';
        echo '<div class="book_cover"><a href="bookdetails.php?b_id=' . $row["book_id"] . '"><img src="' . $row["b_picture"] . '" class="cover_img"></div></a>';
        echo '<div class="book_name"><a href="bookdetails.php?b_id=' . $row["book_id"] . '">' . $row["b_name"] . '</a></div>';
        echo '<div class="book_author"><span>author</span>';

        $temp_count = $result1->num_rows;
        while ($row1 = $result1->fetch_assoc()) {
            echo '<a href="booksbyauthor.php?a_id=' . $row1["author_id"] . '">' . $row1["a_name"] . '</a>';
            if ($temp_count > 1) {
                echo ", ";
                $temp_count--;
            }
        }

        echo '</div>';
        echo '<div class="book_rate"><img src="images/' . $row["b_rate"] . 'stars.png"></div>';
        echo '<div class="book_price">$' . $row["b_price"] . '</div>';

        //shows the button to add book to shopping cart
        $isBookInUserCart = false;
        mysqli_data_seek($booksInUserCart, 0);

        $numBooksInCart = $booksInUserCart->num_rows;
        while ($numBooksInCart-- > 0) {
            $booksInCart = $booksInUserCart->fetch_assoc();
            if ($booksInCart['book_id'] == $row["book_id"]) {
                $isBookInUserCart = true;
                break;
            }
        }
        if ($isBookInUserCart == false) {
            $forwardPage = "#";
            if (isset($_SESSION['user']) == "") { //if there is no logged in user 
                $forwardPage = "login.php";
            }
            ?>
            <form classname="dummy" action="<?php echo $forwardPage; ?>" method="post">
                <button classname="dummy"
                        type="submit" 
                        name="add_book_to_cart" 
                        value="set"
                        id= "b_cart">
                    <img class="book_input_add_to_cart" src="images/shoppingCartAdd.png">
                </button>
                <input name="add_book_id"
                       value="<?php echo $row['book_id']; ?>" 
                       hidden="true" />
                <input name="add_user_id"
                       value="<?php echo $_SESSION['user']; ?>" 
                       hidden="true" />
            </form>
            <?php
        }
        echo '</div>';
    }
} else {
    echo "0 results";
}

echo '</form>';
?>