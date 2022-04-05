<?php
session_start();
include "connection.php";

if (isset($_POST["admin"])){ // admin notes
    // update note first, if needed
    if (isset($_POST["notes"])){
        $notes = $_POST["notes"];
        $notes = mysqli_real_escape_string($con, $notes);
        $query = "UPDATE administrator SET notes = '$notes'";
        mysqli_query($con, $query);
    }

    // then fetch and store in session variable
    $query = "SELECT notes FROM administrator";
    $result = mysqli_query($con, $query);
    $rows = mysqli_fetch_assoc($result);
    $_SESSION["notes"] = $rows["notes"];
}
else if (isset($_POST["customer"])){
    $customer = mysqli_real_escape_string($con, $_SESSION["username"]);

    if (isset($_POST["notes"])){
        $notes = $_POST["notes"];
        $notes = mysqli_real_escape_string($con, $notes);
        $query = "UPDATE customers SET notes = '$notes' WHERE name = '$customer'";
        mysqli_query($con, $query);
    }

    // then fetch and store in session variable
    $query = "SELECT notes FROM customers WHERE name = '$customer'";
    $result = mysqli_query($con, $query);
    $rows = mysqli_fetch_assoc($result);
    $_SESSION["notes"] = $rows["notes"];
}
else { // store owner notes
    $store = mysqli_real_escape_string($con, $_SESSION["business_name"]);

    // update note first, if needed
    if (isset($_POST["notes"])){
        $notes = $_POST["notes"];
        $notes = mysqli_real_escape_string($con, $notes);
        $query = "UPDATE stores SET notes = '$notes' WHERE business_name = '$store'";
        mysqli_query($con, $query);
    }
    
    // then fetch and store in session variable
    $query = "SELECT notes FROM stores WHERE business_name = '$store'";
    $result = mysqli_query($con, $query);
    $rows = mysqli_fetch_assoc($result);
    $_SESSION["notes"] = $rows["notes"];   
}