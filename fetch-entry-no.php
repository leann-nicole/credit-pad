<?php 
session_start();
include "connection.php";

$store = mysqli_real_escape_string($con, $_SESSION["business_name"]);

$customer = $_POST["customer"];
$transactionDate = $_POST["transactionDate"];

$query = "SELECT MAX(entry_no) FROM credit_transactions WHERE business_name = '$store' AND customer = '$customer' AND date = '$transactionDate'";
$latestCreditEntryNo = mysqli_fetch_row(mysqli_query($con, $query))[0];

$query = "SELECT MAX(entry_no) FROM payment_transactions WHERE business_name = '$store' AND customer = '$customer' AND date = '$transactionDate'";
$latestPaymentEntryNo = mysqli_fetch_row(mysqli_query($con, $query))[0];

if (is_null($latestCreditEntryNo) && is_null($latestPaymentEntryNo)){
    echo 1;
}
else {
    echo $latestCreditEntryNo > $latestPaymentEntryNo ? $latestCreditEntryNo + 1 : $latestPaymentEntryNo + 1;
}
