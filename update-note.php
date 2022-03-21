<?php
session_start();
include "connection.php";

$store = mysqli_real_escape_string($con, $_SESSION["business_name"]);

// update note first, if needed
if (isset($_POST["notes"])){
    $notes = $_POST["notes"];
    $notes = mysqli_real_escape_string($con, $notes);
    $query = "UPDATE notes SET content = '$notes' WHERE business_name = '$store'";
    mysqli_query($con, $query);
}

// then fetch and store in session variable
$query = "SELECT * FROM notes WHERE business_name = '$store'";
$result = mysqli_query($con, $query);
$rows = mysqli_fetch_assoc($result);
$_SESSION["notes"] = $rows["content"];

