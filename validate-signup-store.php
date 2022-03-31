<?php
session_start();
include "connection.php";

if (!isset($_POST['storeName']) && !isset($_POST['so-su-name'])) {
    // if the $_POST superglobal is not populated, it means the user didn't come from signup page
    header('Location: signup.php');
    die();
}

if (isset($_POST['storeName'])){
    $_SESSION['store-su-name'] = $_POST['storeName'];
    $_SESSION['store-su-location'] = $_POST['storeLocation'];
    
    $store = mysqli_real_escape_string($con, $_SESSION['store-su-name']);
    $query = "SELECT * FROM stores WHERE business_name = '$store' LIMIT 1";
    $result = mysqli_query($con, $query);
    if (mysqli_num_rows($result)) echo "no";
    else echo "yes";
    die();
}

$_SESSION['so-su-name'] = $_POST['so-su-name'];
$_SESSION['so-su-sex'] = $_POST['so-su-sex'];
$_SESSION['so-su-birthday'] = $_POST['so-su-birthday'];
$_SESSION['so-su-mobile'] = $_POST['so-su-mobile'];
$_SESSION['so-su-email'] = $_POST['so-su-email'];
$_SESSION['so-su-password'] = $_POST['so-su-password'];

$store_name = mysqli_real_escape_string($con, $_SESSION['store-su-name']);
$store_location = mysqli_real_escape_string($con, $_SESSION['store-su-location']);
$store_owner = mysqli_real_escape_string($con, $_POST['so-su-name']);
$store_owner_sex = mysqli_real_escape_string($con, $_POST['so-su-sex']);
$store_owner_birthday = mysqli_real_escape_string($con, $_POST['so-su-birthday']);
$store_owner_mobile = mysqli_real_escape_string($con, $_POST['so-su-mobile']);
$store_owner_email = mysqli_real_escape_string($con, $_POST['so-su-email']);
$store_owner_password = mysqli_real_escape_string($con, $_POST['so-su-password']);

// check if phone number is valid
if (strlen($store_owner_mobile) != 11 || !is_numeric($store_owner_mobile)){
    header("Location: signup.php?account=store&error=invalid phone number");
    die();
}

// check if email is valid
if (!filter_var($store_owner_email, FILTER_VALIDATE_EMAIL)){
    header("Location: signup.php?account=store&error=invalid email address");
    die();
}

$query = "INSERT INTO applications (business_name, business_addr, store_operator, sex, birthdate, mobile_no, email, password) VALUES ('$store_name', '$store_location', '$store_owner', '$store_owner_sex', '$store_owner_birthday', '$store_owner_mobile', '$store_owner_email', '$store_owner_password')";

if (mysqli_query($con, $query)) {
    $_SESSION['username'] = $_SESSION["so-su-name"];
    $_SESSION['business_name'] = $_SESSION["store-su-name"];
    $_SESSION['account-type'] = 'store owner';

    unset($_SESSION['store-su-name']);
    unset($_SESSION['store-su-location']);
    unset($_SESSION['so-su-name']);
    unset($_SESSION['so-su-sex']);
    unset($_SESSION['so-su-birthday']);
    unset($_SESSION['so-su-mobile']);
    unset($_SESSION['so-su-email']);
    unset($_SESSION['so-su-password']);

    header('Location: login.php?success=account successfully created');
    die();
} else {
    header('Location: login.php?error=something went wrong');
    die();
    //echo mysqli_error($con);
}
