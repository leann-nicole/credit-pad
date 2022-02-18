<?php 
session_start();
include "connection.php";

$store_operator = $_SESSION['username'];
$store_operator = mysqli_real_escape_string($con, $store_operator);
$product = $_POST['product'];

$query = "SELECT price FROM products WHERE name = '$product' AND store_operator = '$store_operator'";
$result = mysqli_query($con, $query);
$price = mysqli_fetch_row($result)[0];
$roundDown = floor($price);

if ($price - $roundDown == 0){
    echo $roundDown;
}
else {
    echo $price;
}
