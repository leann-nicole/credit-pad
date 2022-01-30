<?php

$dbhost = "localhost";
$dbuser = "root";
$pass = "";
$dbname = "listahan";

$con = mysqli_connect($dbhost, $dbuser, $pass, $dbname);
if (!$con){
    die("connection failed");
}