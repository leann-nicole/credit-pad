<?php
// write code you want to test here
// open in browser: http://localhost/creditpad/test.php
session_start();
include "connection.php";
$store_operator = $_SESSION["username"];
$notes = "hello world";
$query = "INSERT INTO notes (store_operator, content) VALUES ('$store_operator','$notes')";
mysqli_query($con, $query);
