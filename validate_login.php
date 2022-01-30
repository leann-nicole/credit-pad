<?php
session_start();
include "connection.php";

$username = $_POST['username'];
$password = $_POST['password'];
$_SESSION['username'] = $username;
$_SESSION['password'] = $password;

// check for missing information
function filled($data){
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  if (!empty($data)){
      return true;
  }
  else{
      return false;
  }
}

foreach ($_POST as $post_var){
  if (!filled($post_var)){
      header("Location: login.php?error=information missing");
      die();
  }
}


$username = mysqli_real_escape_string($con, $username);
$password = mysqli_real_escape_string($con, $password);

// check if username and password are correct
$query = "SELECT * FROM store_operators WHERE username = '$username' limit 1";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);
if (mysqli_num_rows($result) == 0 or $row['password'] != $password){
  header("Location: login.php?error=incorrect username or password");
  die();
}
else{
  header("Location: customers.php");
  die();
}