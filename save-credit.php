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
$entryNo = $_POST["entryNo"];

if (!empty($_POST["comment"])){
    $comment = mysqli_real_escape_string($con, $_POST["comment"]);
    $query = "INSERT INTO credit_transactions (date, store_operator, customer, product, quantity, price, subtotal, entry_no, comment) VALUES ('$date', '$store_operator', '$customer', '$product', '$quantity', '$price', '$subtotal', '$entryNo', '$comment')";
}
else {
    $query = "INSERT INTO credit_transactions (date, store_operator, customer, product, quantity, price, subtotal, entry_no) VALUES ('$date', '$store_operator', '$customer', '$product', '$quantity', '$price', '$subtotal', '$entryNo')";
}


if (!mysqli_query($con, $query)){
    header("Location: customer-account.php?error=unable to save credit transaction");
    die();
}
$query = "UPDATE customers SET current_debt = current_debt + $grandTotal WHERE name = '$customer'";
if (!mysqli_query($con, $query)){
    header("Location: customer-account.php?error=unable to update customer credit");
    die();
}

