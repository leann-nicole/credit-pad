<?php
session_start();
include "connection.php";

$businessName = mysqli_real_escape_string($con, $_POST['storeName']);
$storeOperator = mysqli_real_escape_string($con, $_POST['storeOperator']);
$businessAddr = mysqli_real_escape_string($con, $_POST['storeLocation']);
$applicationDate = date_format(date_create(mysqli_real_escape_string($con, $_POST['applicationDate'])), "Y-m-d");

// delete from applicants table 
$query = "DELETE FROM applicants WHERE business_name = '$businessName' AND business_addr = '$businessAddr' AND store_operator = '$storeOperator' AND application_date = '$applicationDate'";
mysqli_query($con, $query);