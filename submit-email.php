<?php
session_start();
include 'connection.php';

$email = mysqli_real_escape_string($con, $_POST['contactAddress']);
$subject = mysqli_real_escape_string($con, $_POST['contactSubject']);
$message = mysqli_real_escape_string($con, $_POST['contactMessage']);
$recipient = 'leannnicole.velasco@gmail.com';
$mailheader = 'From: <' . $email . ">\r\n";

if (empty($email) || empty($subject) || empty($message)) 
    echo json_encode(array("fail", "Failed to send message"));
else {
    if (mail($recipient, $subject, $message, $mailheader))
        echo json_encode(array("success", "Your message has been sent."));
    else echo json_encode(array("fail", "Failed to send message"));
}