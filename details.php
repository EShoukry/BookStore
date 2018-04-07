
<?php 
if(isset($_GET["b_id"])){
 echo "Use book id: ". $_GET["b_id"]. " to retrive the information of the DB to show book details";
}
if(isset($_GET["a_id"])){
 echo "Use author id: ". $_GET["a_id"]. " to retrive the information of the DB to show all the book details of the author with this id";
}
?>