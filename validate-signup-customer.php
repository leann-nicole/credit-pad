<?php
session_start();
include "connection.php";

if (isset($_POST['accountType'])){
    $_SESSION['account-type-signup'] = $_POST['accountType'];
    die();
}

$_SESSION["customer-su-name"] = $_POST["customer-su-name"];
$_SESSION["customer-su-store"] = $_POST["customer-su-store"];

$customer = mysqli_real_escape_string($con, $_POST["customer-su-name"]);
$store = mysqli_real_escape_string($con, $_POST["customer-su-store"]);
$password = mysqli_real_escape_string($con, $_POST["customer-su-password"]);

$query = "SELECT * FROM customers WHERE name = '$customer' AND business_name = '$store' LIMIT 1";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result)){
    $row = mysqli_fetch_assoc($result);
    if ($row['password'] != NULL){
        header("Location: signup.php?account=customer&error=account already exists");
    }
    else{
        $query = "UPDATE customers SET password = '$password' WHERE name = '$customer' AND business_name = '$store'";
        if(mysqli_query($con, $query)){
            unset($_SESSION["customer-su-name"]);
            unset($_SESSION["customer-su-store"]);
            $_SESSION['username'] = $_POST["customer-su-name"];
            $_SESSION['business_name'] = $_POST["customer-su-store"];
            $_SESSION['account-type'] = 'customer';
            header("Location: login.php");
        }
        else {
            header('Location: login.php?error=something went wrong');
            die();
            //echo mysqli_error($con);
        }
    }
}
else {
    header("Location: signup.php?account=customer&error=customer or store does not exist");
}

