<?php
session_start();
include 'connection.php';

$store_owner = $_SESSION['username'];

// transfer values from the submitted form
$name = $_POST['username'];
$birthdate = $_POST['birthdate'];
$sex = $_POST['sex'];
$mobile_no = $_POST['mobile_no'];
$address = $_POST['address'];
$rating = $_POST['rate'];

// store variables in $_SESSION superglobal to be able to access them across pages
$_SESSION['cusername'] = $name;
$_SESSION['cbirthdate'] = $birthdate;
$_SESSION['csex'] = $sex;
$_SESSION['cmobile_no'] = $mobile_no;
$_SESSION['caddress'] = $address;
$_SESSION['crate'] = $rating;

// escape special characters (ex. Leann's Store ==> Leann\'s Store) to avoid errors in the SQL query below
$store_owner = mysqli_real_escape_string($con, $store_owner);
$name = mysqli_real_escape_string($con, $name);
$address = mysqli_real_escape_string($con, $address);

if (!empty($_POST['email'])){
    $email = $_POST['email'];
    $_SESSION['cemail'] = $email;
    $address = mysqli_real_escape_string($con, $address);
}

// these are optional information, no need to check if they are missing
// no need to unset rate post var since it has a default value of 1 star
unset($_POST['email']);

// check for missing information
function filled($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    if (!empty($data)) {
        return true;
    } else {
        return false;
    }
}

foreach ($_POST as $post_var) {
    if (!filled($post_var)) {
        header('Location: customers.php?error=information missing');
        die();
    }
}

// check if username is taken
$query = "SELECT * FROM customers WHERE name = '$name' AND store_operator = '$store_owner' limit 1";
$result = mysqli_query($con, $query);
if (mysqli_num_rows($result)) {
    header('Location: customers.php?error=username is already taken');
    die();
}

// check if email is valid
if (!empty($email) and !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: customers.php?error=invalid email address');
    die();
}

// check if phone number is valid
if (!strlen($mobile_no) == 11 or !is_numeric($mobile_no)){
    header("Location: customers.php?error=invalid phone number");
    die();
}

// save data to database
$query = "INSERT INTO customers (name, birthdate, sex, mobile_no, email, address, store_operator, rating) VALUES ('$name', '$birthdate', '$sex', '$mobile_no', '$email', '$address', '$store_owner', '$rating')";

if (mysqli_query($con, $query)) {
    // if new account information is successfully saved to database, we can clear the form for new input
    unset($_SESSION['cusername']);
    unset($_SESSION['cbirthdate']);
    unset($_SESSION['csex']);
    unset($_SESSION['cmobile_no']);
    unset($_SESSION['cemail']);
    unset($_SESSION['caddress']);
    unset($_SESSION['crate']);
    header('Location: customers.php?success=account successfully created');
    die();
} else {
    header('Location: customers.php?error=something went wrong');
    die();
    //echo mysqli_error($con);
}
