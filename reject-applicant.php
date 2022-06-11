<?php
session_start();
include 'connection.php';

$store = $_POST['storeName'];
$businessName = mysqli_real_escape_string($con, $_POST['storeName']);
$storeOperator = mysqli_real_escape_string($con, $_POST['storeOperator']);
$businessAddr = mysqli_real_escape_string($con, $_POST['storeLocation']);
$applicationDate = date_format(
    date_create(mysqli_real_escape_string($con, $_POST['applicationDate'])),
    'Y-m-d'
);

// delete from applicants table
$query = "DELETE FROM applicants WHERE business_name = '$businessName' AND business_addr = '$businessAddr' AND store_operator = '$storeOperator' AND application_date = '$applicationDate'";
if (mysqli_query($con, $query)) echo "success";
else echo "failed";

$email = 'leannnicole.velasco@gmail.com';
$subject = 'Your application was not approved';
$message = <<<EOD
We regret to inform you that application for a store owner account was not approved.

    Account: store owner
    Name: $storeOperator
    Store: $store

If you need help, you may respond to this email.

Sincerely,
Credit Pad Team
EOD;
$recipient = mysqli_real_escape_string($con, $_POST["applicationEmail"]);
$mailheader = 'From: Credit Pad <' . $email . ">\r\n";

mail($recipient, $subject, $message, $mailheader) or
    die('Error: Email message not sent.');
