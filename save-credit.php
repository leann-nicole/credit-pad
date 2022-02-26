<?php 
session_start();
include "connection.php";

$store_operator = mysqli_real_escape_string($con, $_SESSION["username"]);
$date = $_POST["creditDate"];
$customer = $_POST["customer"];
$product = $_POST["product"];
$quantity = $_POST["quantity"];
$price = $_POST["price"];
$subtotal = $_POST["subTotal"];
$grandTotal = $_POST["grandTotal"];

if (!empty($_POST["comment"])){ 
    // used to be (!empty($_POST["comment"])) and
    // ajax success was spitting back html code (console.log(data)))
    // tried mysqli_error($con) which printed "field 'comment' doesn't have a default value"
    // tried saving a credit transaction with comment and it worked
    // reason: empty() doesn't warn if variable doesn't even exists
    $comment = $_POST["comment"];
    $query = "INSERT INTO credit_transactions (date, store_operator, customer, product, quantity, price, subtotal, comment) VALUES ('$date', '$store_operator', '$customer', '$product', '$quantity', '$price', '$subtotal', '$comment')";
}
else {
    $query = "INSERT INTO credit_transactions (date, store_operator, customer, product, quantity, price, subtotal) VALUES ('$date', '$store_operator', '$customer', '$product', '$quantity', '$price', '$subtotal')";
}

if (!mysqli_query($con, $query)){
    header("Location: customer-account.php?error=something went wrong");
    die();
}
$query = "UPDATE customers SET current_debt = current_debt + $grandTotal WHERE name = '$customer'";
if (!mysqli_query($con, $query)){
    header("Location: customer-account.php?error=something went wrong");
    die();
}

