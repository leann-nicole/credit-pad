<?php
session_start();
include 'connection.php';

$businessName = mysqli_real_escape_string($con, $_POST['storeName']);
$query = "SELECT * FROM applicants WHERE business_name = '$businessName' ORDER BY application_date DESC, id ASC LIMIT 1";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);

$store = $_POST['storeName'];
$storeOperator = $row['store_operator'];
$businessAddr = $row['business_addr'];
$password = $row['password'];
$birthdate = $row['birthdate'];
$sex = $row['sex'];
$mobileNo = $row['mobile_no'];
$email = $row['email'];

// save into stores table
$query1 = "INSERT INTO stores (business_name, store_operator, business_addr, password, date_approved) VALUES ('$businessName', '$storeOperator', '$businessAddr', '$password', now())";
mysqli_query($con, $query1);

// save into store operators table
$query2 = "INSERT INTO store_operators (username, birthdate, sex, mobile_no, email) VALUES ('$storeOperator', '$birthdate', '$sex', '$mobileNo', '$email')";
mysqli_query($con, $query2);

// delete from applicants table
$query3 = "DELETE FROM applicants WHERE business_name = '$businessName'";
mysqli_query($con, $query3);

$email = 'leannnicole.velasco@gmail.com';
$subject = 'Your application has been approved!';
$message = <<<EOD
Greetings!

Your application for a store owner account has been approved.

    Account: store owner
    Name: $storeOperator
    Store: $store

Log in to Credit Pad using the password you used to create your account.

Sincerely,
Credit Pad Team
EOD;
$recipient = mysqli_real_escape_string($con, $row['email']);
$mailheader = 'From: Credit Pad <' . $email . ">\r\n";

mail($recipient, $subject, $message, $mailheader) or
    die('Error: Email message not sent.');
