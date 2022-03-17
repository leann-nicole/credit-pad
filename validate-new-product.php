<?php
session_start();
include 'connection.php';

$store_owner = $_SESSION['username'];

// transfer data from the submitted form into variables
$product = $_POST['product'];
$price = $_POST['price'];

// store data into $_SESSION superglobal to be able to access them across pages
$_SESSION['product'] = $product;
$_SESSION['price'] = $price;

// escape special characters (remove their special meaning) to avoid errors when doing an SQL query below
$store_owner = mysqli_real_escape_string($con, $store_owner);
$product = mysqli_real_escape_string($con, $product);
$description = mysqli_real_escape_string($con, $description);

if (!empty($_POST['description'])) {
    $description = $_POST['description'];
    $_SESSION['description'] = $description;
}
if (!empty($_POST['category'])) {
    $category = $_POST['category'];
    $_SESSION['category'] = $category;
}

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
        header('Location: products.php?error=information missing');
        die();
    }
}

// check if product name is taken
$query = "SELECT * FROM products WHERE name = '$product' AND store_operator = '$store_owner' limit 1";
$result = mysqli_query($con, $query);
if (mysqli_num_rows($result)) {
    header('Location: products.php?error=product name already exists');
    die();
}

// save data to database
$query = "INSERT INTO products (name, description, category, price, store_operator) VALUES ('$product', '$description', '$category', '$price', '$store_owner')";

if (mysqli_query($con, $query)) {
    // upon successfully saving the new product information to the database, unset the following variables to clear the form
    unset($_SESSION['product']);
    unset($_SESSION['description']);
    unset($_SESSION['category']);
    unset($_SESSION['price']);
    header('Location: products.php?success=product successfully registered');
    die();
} else {
    header('Location: products.php?error=something went wrong');
    die();
    //echo mysqli_error($con);
}
