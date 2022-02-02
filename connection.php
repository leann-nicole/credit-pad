<?php

$dbhost = 'localhost'; // the database server: localhost or 127.0.0.1 or ::1. all refer to this computer (loopback address, not sure)
$dbuser = 'root'; // root user has been granted all privileges
$pass = '';
$dbname = 'credit_pad';

$con = mysqli_connect($dbhost, $dbuser, $pass, $dbname);
if (!$con) {
    die('connection failed');
}
