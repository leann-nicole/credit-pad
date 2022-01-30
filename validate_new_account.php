<?php
session_start();
include "connection.php";

$name = $_POST['username'];
$birthdate = $_POST['birthdate'];
$sex = $_POST['sex'];
$mobile_no = $_POST['mobile_no'];
$email = $_POST['email'];
$address = $_POST['address'];
if (isset($_POST['rate'])){
    $rating = $_POST['rate'];;
}
else {
    $rating = 0;
}
$store_owner = $_SESSION['username'];

$_SESSION['cusername'] = $name;
$_SESSION['cbirthdate'] = $birthdate;
$_SESSION['csex'] = $sex;
$_SESSION['cmobile_no'] = $mobile_no;
$_SESSION['cemail'] = $email;
$_SESSION['caddress'] = $address;
$_SESSION['crate'] = $rating;

// these are optional information
unset($_POST['email']);
unset($_POST['rate']);

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
        header("Location: customers.php?error=information missing");
        die();
    }
}

// check if username is taken
$query = "SELECT * FROM customers WHERE name = '$name' limit 1";
$result = mysqli_query($con, $query);
if (mysqli_num_rows($result)){
    header("Location: customers.php?error=username is already taken");
    die();
}

// check if email is valid
if (!empty($email) and !filter_var($email, FILTER_VALIDATE_EMAIL)){
    header("Location: customers.php?error=invalid email address");
    die();
}

$store_owner = mysqli_real_escape_string($con, $store_owner);
$name = mysqli_real_escape_string($con, $name);
$address = mysqli_real_escape_string($con, $address);

// save data to database
$query = "INSERT INTO customers (name, birthdate, sex, mobile_no, email, address, store_operator, rating) VALUES ('$name', '$birthdate', '$sex', '$mobile_no', '$email', '$address', '$store_owner', '$rating')";

if (mysqli_query($con, $query)){
    unset($_SESSION['cusername']);
    unset($_SESSION['cbirthdate']);
    unset($_SESSION['csex']);
    unset($_SESSION['cmobile_no']);
    unset($_SESSION['cemail']);
    unset($_SESSION['caddress']);
    unset($_SESSION['crate']);
    header("Location: customers.php?success=account successfully created");
    die();
}
else{
    header("Location: customers.php?error=something went wrong");
    die();
    //echo mysqli_error($con);
}