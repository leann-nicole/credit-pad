<?php
session_start();
include "connection.php";

$username = $_POST['username'];
$birthdate = $_POST['birthdate'];
$sex = $_POST['sex'];
$mobile_no = $_POST['mobile_no'];
$email = $_POST['email'];
$business_name = $_POST['business_name'];
$business_addr = $_POST['business_addr'];
$password = $_POST['password'];

$_SESSION['username'] = $username;
$_SESSION['birthdate'] = $birthdate;
$_SESSION['sex'] = $sex;
$_SESSION['mobile_no'] = $mobile_no;
$_SESSION['email'] = $email;
$_SESSION['business_name'] = $business_name;
$_SESSION['business_addr'] = $business_addr;
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
        header("Location: signup.php?error=information missing");
        die();
    }
}

$username = mysqli_real_escape_string($con, $username);
$business_name = mysqli_real_escape_string($con, $business_name);
$business_addr = mysqli_real_escape_string($con, $business_addr);
$password = mysqli_real_escape_string($con, $password);

// check if username is taken
$query = "SELECT * FROM store_operators WHERE username = '$username' limit 1";
$result = mysqli_query($con, $query);
if (mysqli_num_rows($result)){
    header("Location: signup.php?error=username is already taken");
    die();
}

// check if email is valid
if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
    header("Location: signup.php?error=invalid email address");
    die();
}


// save data to database
$query = "INSERT INTO store_operators (username, birthdate, sex, mobile_no, email, business_name, business_addr, password) VALUES ('$username', '$birthdate', '$sex', '$mobile_no', '$email', '$business_name', '$business_addr', '$password')";

if (mysqli_query($con, $query)){
    header("Location: login.php?success=account successfully created");
    die();
}
else{
    header("Location: login.php?error=something went wrong");
    die();
    //echo mysqli_error($con);
}