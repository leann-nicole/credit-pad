<?php 
session_start();
include "connection.php";

$store = mysqli_real_escape_string($con, $_SESSION["business_name"]);

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
    $query = "INSERT INTO credit_transactions (date, business_name, customer, product, quantity, price, subtotal, entry_no, comment) VALUES ('$date', '$store', '$customer', '$product', '$quantity', '$price', '$subtotal', '$entryNo', '$comment')";
}
else {
    $query = "INSERT INTO credit_transactions (date, business_name, customer, product, quantity, price, subtotal, entry_no) VALUES ('$date', '$store', '$customer', '$product', '$quantity', '$price', '$subtotal', '$entryNo')";
}

if (!mysqli_query($con, $query)){
    header("Location: customer-account.php?customer=$customer&error=unable to save credit transaction");
    die();
}

$query = "UPDATE customers SET current_debt = current_debt + $grandTotal WHERE name = '$customer' AND business_name = '$store'";
if (!mysqli_query($con, $query)){
    header("Location: customer-account.php?customer=$customer&error=unable to update customer credit");
    die();
}

