<?php
session_start();
include 'connection.php';

$store_operator = mysqli_real_escape_string($con, $_SESSION['username']);
$customer = $_POST['customer'];
$paymentType = $_POST['paymentType'];
$date = $_POST["paymentDate"];
$cash = $_POST['cash'];
$amountPaid = $_POST['amountPaid'];
$change = $_POST['change'];

if (!empty($_POST["comment"])){
    $comment = mysqli_real_escape_string($con, $_POST["comment"]);
    $query = "INSERT INTO payment_transactions (payment_type, date, store_operator, customer, cash, amount_paid, change_amount, comment) VALUES ('$paymentType', '$date', '$store_operator', '$customer', '$cash', '$amountPaid', '$change', '$comment')";
}
else {
    // using "change_amount" instead of "change" because change is a reserved keyword as warned by phpmyadmin
    $query = "INSERT INTO payment_transactions (payment_type, date, store_operator, customer, cash, amount_paid, change_amount) VALUES ('$paymentType', '$date', '$store_operator', '$customer', '$cash', '$amountPaid', '$change')";
}

if (!mysqli_query($con, $query)){
    mysqli_error($con);
    header("Location: customer-account.php?error=unable to save payment transaction");
    die();
}

$query = "UPDATE customers SET current_debt = current_debt - $amountPaid WHERE name = '$customer'";
if (!mysqli_query($con, $query)){
    mysqli_error($con);
    header("Location: customer-account.php?error=unable to update customer credit");
    die();
}