<?php
session_start();
include "connection.php";

$store_operator = mysqli_real_escape_string($con, $$_SESSION["username"]);
$customer = $_POST["customer"];
$cash = $_POST["cash"];
if (!empty($_POST["amtPaid"])){
    $amtPaid = $_POST["amtPaid"];
}