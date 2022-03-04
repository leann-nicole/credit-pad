<?php 
session_start();
include "connection.php";

$store_operator = mysqli_real_escape_string($con, $_SESSION["username"]);
$customer = $_POST["customer"];
$transaction = $_POST["transaction"];
$transactionDate = $_POST["transactionDate"];

$query = "SELECT MAX(entry_no) FROM `$transaction` WHERE store_operator = '$store_operator' AND customer = '$customer' AND date = '$transactionDate'";
$result = mysqli_query($con, $query);

$row = mysqli_fetch_row($result);

echo is_null($row[0])? 1:$row[0] + 1;