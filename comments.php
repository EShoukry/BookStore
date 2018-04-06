 <?php
ob_start();
session_start();
require "header.php";
$database = include('config.php');
$mysqli = new mysqli($database['host'], $database['user'], $database['pass'], $database['name']);
// Check connection
if (mysqli_connect_error()) {
    die("Database connection failed: " . mysqli_connect_error());
}
if (isset($_SESSION['user']) != "") {
    $_SESSION['book_id_test'] = '1465464867';
    $owns_book = "SELECT b1.book_id, b2.b_rate FROM books_users b1, books b2 WHERE b1.user_id = 3 AND b1.book_id = b2.book_id AND b1.book_id =" . $_SESSION['book_id_test'];
    $result = mysqli_query($mysqli, $owns_book);
    $book_id = mysqli_fetch_array($result, MYSQLI_BOTH);
//    echo $book_id['book_id'];
//    echo "</br>";
//    echo $book_id['b_rate'];
    $count = $result->num_rows;
                    while($row = $result->fetch_assoc()){
                        echo $row['book_id']; 
                        if($count >1){
                            echo "& ";
                            $count--;
                        }
                    }
    if($book_id['book_id'] == $_SESSION['book_id_test'])
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

	

<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>

</script>
</head>
<body>
	<div id=main_image>
	<img src="images/index.jpeg" alt="Team 7 book store" >
    	</div>  
		<input type="radio" name="Anon_User" checked > Anonymous<br>
		<input type="radio" name="Anon_User" > Username<br>
	<form action="commentsend.php" method="post">
	<label><b>Comment on this book!</b></label>
	</br>
	</br>

	<textarea name= "bookcomments" placeholder="Comment Here" style="width:500px; height:200px;"></textarea>

	<input type="submit" value="Submit">
        </form>
	</br>
        <form action="rating.php" method="post">
	<label><b>Rate this book!</b></label>
	</br>
	</br>
        <p>
        What is your rating of this book?
        <select name="formRating">

            <option value="">Select...</option>
            <option value="1">One Star</option>
            <option value="2">Two Stars</option>
            <option value="3">Three Stars</option>
            <option value="4">Four Stars</option>
            <option value="5">Five Stars</option>
</select>

</p>
        </br>
	<input type="submit" value="Submit">
        </form>
END_OF_TEXT;
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

	

<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>

</script>
</head>
<body>
	<div id=main_image>
	<img src="images/index.jpeg" alt="Team 7 book store" >
END_OF_TEXT;
   }

}
?>
    



<!--<html>
	<head>
    	<meta charset="utf-8">    
    	<title>Book Store </title>
    	<meta http-equiv="content-type" content="text/plain">
    	<link rel="stylesheet" type="text/css" href="css/styles.css">
  	</head>
  	<body>

<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>

</script>
</head>
<body>
	<div id=main_image>
	<img src="images/index.jpeg" alt="Team 7 book store" >
    	</div>  
		<input type="radio" name="Anon_User" checked > Anonymous<br>
		<input type="radio" name="Anon_User" > Username<br>
	<form action="commentsend.php" method="post">
	<label><b>Comment on this book!</b></label>
	</br>
	</br>

	<textarea name= "bookcomments" placeholder="Comment Here" style="width:500px; height:200px;"></textarea>

	<input type="submit" value="Submit">
        </form>
	</br>
        <form action="rating.php" method="post">
	<label><b>Rate this book!</b></label>
	</br>
	</br>
        <p>
        What is your rating of this book?
        <select name="formRating">

            <option value="">Select...</option>
            <option value="1">One Star</option>
            <option value="2">Two Stars</option>
            <option value="3">Three Stars</option>
            <option value="4">Four Stars</option>
            <option value="5">Five Stars</option>
</select>

</p>
        </br>
	<input type="submit" value="Submit">
        </form>-->
