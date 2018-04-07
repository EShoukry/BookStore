<?php

/*
 * Must pass parameters ajax_bookIdToCart and ajax_userIdToCart via POST method
 */

// Create connection
$dbConfig = include($_SERVER['DOCUMENT_ROOT'] . '/Bookstore/config.php');
$mysqli = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['name']);

sleep(5);

$bookId = $_POST["ajax_bookIdToCart"];
$userId = $_POST["ajax_userIdToCart"];

$mysqli->query(""
        . "INSERT INTO books_users ('book_id', 'user_id', 'b_quantity')"
        . "VALUES ('" . $bookId . "', '" . $userId . "', 1);"
);

mysqli_close($mysqli);
?>

