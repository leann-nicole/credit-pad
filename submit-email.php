<?php
session_start();
include 'connection.php';

$email = mysqli_real_escape_string($con, $_POST['contactAddress']);
$subject = mysqli_real_escape_string($con, $_POST['contactSubject']);
$message = mysqli_real_escape_string($con, $_POST['contactMessage']);
$recipient = 'leannnicole.velasco@gmail.com';
$mailheader = 'From: <' . $email . ">\r\n";

mail($recipient, $subject, $message, $mailheader) or
    die('Error: Email message not sent.');
