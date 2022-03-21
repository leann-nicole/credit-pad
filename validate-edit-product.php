<?php
session_start();
include 'connection.php';

$store = mysqli_real_escape_string($con, $_SESSION["business_name"]);

$current_product_name = mysqli_real_escape_string($con, $_POST['current_product_name']);

// transfer data from the submitted form into variables
$product = $_POST['product'];
$price = $_POST['price'];
$description = $_POST['description'];
$category = $_POST['category'];

// store data into $_SESSION superglobal to be able to access them across pages
$_SESSION['product-edit'] = $product;
$_SESSION['price-edit'] = $price;
$_SESSION['description-edit'] = $description;
$_SESSION['category-edit'] = $category;

// escape special characters (remove their special meaning) to avoid errors when doing an SQL query below
$product = mysqli_real_escape_string($con, $product);
$description = mysqli_real_escape_string($con, $description);
$category = mysqli_real_escape_string($con, $category);

// these are optional information so there's no need to check if they are missing
unset($_POST['description']);
unset($_POST['category']);

// check for missing information
function filled($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    if (!empty($data)) {
        return true;
    } else {
        return false;
    }
}

foreach ($_POST as $post_var) {
    // each item in the $_POST superglobal will be given the alias of $post_var, this is pass by value (origina values in the superglobal are not altered)
    if (!filled($post_var)) {
        header("Location: products.php?error-edit=information missing&product=$current_product_name");
        die();
    }
}

// check if product name is taken
if ($product != $current_product_name){
    $query1 = "SELECT * FROM products WHERE name = '$product' AND business_name = '$store' limit 1";
    $result = mysqli_query($con, $query1);
    if (mysqli_num_rows($result)) {
        header('Location: products.php?error-edit=product name already exists');
        die();
    }    
}

// save data to database
$query2 = "UPDATE products SET description = '$description', category = '$category', price = '$price' WHERE name = '$current_product_name' AND business_name = '$store'";
$query3 = "UPDATE products SET name = '$product' WHERE name = '$current_product_name' AND business_name = '$store'";

if (mysqli_query($con, $query2) && mysqli_query($con, $query3)) {
    // upon successfully saving the new product information to the database, unset the following variables to clear the form
    unset($_SESSION['product-edit']);
    unset($_SESSION['description-edit']);
    unset($_SESSION['category-edit']);
    unset($_SESSION['price-edit']);
    header('Location: products.php?success-edit=product successfully registered');
    die();
} else {
    header('Location: products.php?error-edit=something went wrong');
    die();
    //echo mysqli_error($con);
}
