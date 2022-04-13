<?php 
session_start();
include "connection.php";

$store = mysqli_real_escape_string($con, $_SESSION["business_name"]);

$date = $_POST["creditDate"];
$due = $_POST["dueDate"];
$customer = $_POST["customer"];
$product = $_POST["product"];
$quantity = $_POST["quantity"];
$price = $_POST["price"];
$subtotal = $_POST["subTotal"];
$grandTotal = $_POST["grandTotal"];
$entryNo = $_POST["entryNo"];

if (isset($_POST["comment"])){ // if index 0
    if (!empty($_POST["comment"])){
        $comment = mysqli_real_escape_string($con, $_POST["comment"]);
        $query = "INSERT INTO credit_transactions (date, due_date, business_name, customer, product, quantity, price, subtotal, grand_total, entry_no, comment, status) VALUES ('$date', '$due', '$store', '$customer', '$product', '$quantity', '$price', '$subtotal', '$grandTotal', '$entryNo', '$comment', 'unpaid')";    
    }
    else $query = "INSERT INTO credit_transactions (date, due_date, business_name, customer, product, quantity, price, subtotal, grand_total, entry_no, status) VALUES ('$date', '$due', '$store', '$customer', '$product', '$quantity', '$price', '$subtotal', '$grandTotal', '$entryNo', 'unpaid')";    
}
else {
    $query = "INSERT INTO credit_transactions (date, due_date, business_name, customer, product, quantity, price, subtotal, entry_no) VALUES ('$date', '$due', '$store', '$customer', '$product', '$quantity', '$price', '$subtotal', '$entryNo')";
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

