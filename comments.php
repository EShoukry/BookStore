<?php
$database = include('config.php');
$mysqli = new mysqli($database['host'], $database['user'], $database['pass'], $database['name']);
// Check connection
if (mysqli_connect_error()) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>

<html>
	<head>
    	<meta charset="utf-8">    
    	<title>Book Store </title>
    	<meta http-equiv="content-type" content="text/plain">
    	<link rel="stylesheet" type="text/css" href="css/styles.css">
  	</head>
  	<body>
<?php 
	require "header.php";
?>
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
