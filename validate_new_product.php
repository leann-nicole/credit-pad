<?php
session_start();
include "connection.php";

$product = $_POST['product'];
$price = $_POST['price'];
if (isset($_POST['description'])){
    $description = $_POST['description'];;
}
else {
    $description = "None";
}
if (isset($_POST['category'])){
    $category = $_POST['category'];;
}
else {
    $category = "None";
}
$store_owner = $_SESSION['username'];

$_SESSION['product'] = $product;
$_SESSION['description'] = $description;
$_SESSION['category'] = $category;
$_SESSION['price'] = $price;

// these are optional information
unset($_POST['description']);
unset($_POST['category']);

// check for missing information
function filled($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    if (!empty($data)){
        return true;
    }
    else{
        return false;
    }
}

foreach ($_POST as $post_var){
    if (!filled($post_var)){
        header("Location: products.php?error=information missing");
        die();
    }
}

// check if product name is taken
$query = "SELECT * FROM products WHERE name = '$product' limit 1";
$result = mysqli_query($con, $query);
if (mysqli_num_rows($result)){
    header("Location: products.php?error=product name already exists");
    die();
}

$store_owner = mysqli_real_escape_string($con, $store_owner);
$product = mysqli_real_escape_string($con, $product);
$description = mysqli_real_escape_string($con, $description);

// save data to database
$query = "INSERT INTO products (name, description, category, price, store_operator) VALUES ('$product
', '$description', '$category', '$price', '$store_owner')";

if (mysqli_query($con, $query)){
    unset($_SESSION['product']);
    unset($_SESSION['description']);
    unset($_SESSION['category']);
    unset($_SESSION['price']);
    header("Location: products.php?success=product successfully registered");
    die();
}
else{
    header("Location: products.php?error=something went wrong");
    die();
    //echo mysqli_error($con);
}