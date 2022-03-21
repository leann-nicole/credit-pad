<?php 
session_start();
include "connection.php";

$store = mysqli_real_escape_string($con, $_SESSION["business_name"]);

$product = $_POST['product'];

$query = "SELECT price FROM products WHERE name = '$product' AND business_name = '$store'";
$result = mysqli_query($con, $query);
$price = mysqli_fetch_row($result)[0];
$roundDown = floor($price);

if ($price - $roundDown == 0){
    echo $roundDown;
}
else {
    echo $price;
}
