<?php
session_start();
include 'connection.php';

$store = mysqli_real_escape_string($con, $_SESSION["business_name"]);

// transfer data from the submitted form into variables
$product = $_POST['product'];
$price = $_POST['price'];
$description = $_POST['description'];
$category = $_POST['category'];

// store data into $_SESSION superglobal to be able to access them across pages
$_SESSION['product'] = $product;
$_SESSION['price'] = $price;
$_SESSION['category'] = $category;
$_SESSION['description'] = $description;

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
    // each item in the $_POST superglobal will be given the alias of $post_var, this is pass by value (original values in the superglobal are not altered)
    if (!filled($post_var)) {
        header('Location: products.php?error=information missing');
        die();
    }
}

// check if product name is taken
$query = "SELECT * FROM products WHERE name = '$product' AND business_name = '$store' limit 1";
$result = mysqli_query($con, $query);
if (mysqli_num_rows($result)) {
    header('Location: products.php?error=product name already exists');
    die();
}

// save data to database
$query = "INSERT INTO products (name, description, category, price, business_name) VALUES ('$product', '$description', '$category', '$price', '$store')";

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
