<?php
session_start();
include "connection.php";

$store = mysqli_real_escape_string($con, $_SESSION["business_name"]);

if ($_POST["type"] == "product"){
    $product = mysqli_real_escape_string($con, $_POST["item"]);
    $query = "DELETE FROM products WHERE name = '$product' AND business_name = '$store'";
    if (mysqli_query($con, $query)){
        echo "success";
        die();
    }
    else {
        echo "failure";
        die();
    }
}
else if ($_POST["type"] == "customer"){
    $customer = mysqli_real_escape_string($con, $_POST["item"]);
    $query1 = "DELETE FROM payment_transactions WHERE customer = '$customer' AND business_name = '$store'";
    $query2 = "DELETE FROM credit_transactions WHERE customer = '$customer' AND business_name = '$store'";
    $query3 = "DELETE FROM customers WHERE name = '$customer' AND business_name = '$store'";
    if (mysqli_query($con, $query1) && mysqli_query($con, $query2) && mysqli_query($con, $query3)){
        unset($_SESSION['cusername-edit']);
        unset($_SESSION['cbirthdate-edit']);
        unset($_SESSION['csex-edit']);
        unset($_SESSION['cmobile_no-edit']);
        unset($_SESSION['cemail-edit']);
        unset($_SESSION['caddress-edit']);
        unset($_SESSION['crate-edit']);
        echo "customers.php";
        die();
    }
    else {
        echo "customer-account.php?customer={$_POST['item']}&response=failed to delete account";
        die();
    }
}