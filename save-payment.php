<?php
session_start();
include 'connection.php';

$store = mysqli_real_escape_string($con, $_SESSION["business_name"]);

$customer = $_POST['customer'];
$paymentType = $_POST['paymentType'];
$date = $_POST["paymentDate"];
$cash = $_POST['cash'];
$amountPaid = $_POST['amountPaid'];
$change = $_POST['change'];
$entryNo = $_POST["entryNo"];

if (!empty($_POST["comment"])){
    $comment = mysqli_real_escape_string($con, $_POST["comment"]);
    $query = "INSERT INTO payment_transactions (payment_type, date, business_name, customer, cash, amount_paid, change_amount, entry_no, comment) VALUES ('$paymentType', '$date', '$store', '$customer', '$cash', '$amountPaid', '$change', '$entryNo', '$comment')";
}
else {
    // using "change_amount" instead of "change" because change is a reserved keyword as warned by phpmyadmin
    $query = "INSERT INTO payment_transactions (payment_type, date, business_name, customer, cash, amount_paid, change_amount, entry_no) VALUES ('$paymentType', '$date', '$store', '$customer', '$cash', '$amountPaid', '$change', '$entryNo')";
}

if (!mysqli_query($con, $query)){
    header("Location: customer-account.php?customer=$customer&error=unable to save payment transaction");
    die();
}

$query = "UPDATE customers SET current_debt = current_debt - $amountPaid WHERE name = '$customer' AND business_name = '$store'";
if (!mysqli_query($con, $query)){
    header("Location: customer-account.php?customer=$customer&error=unable to update customer credit");
    die();
}