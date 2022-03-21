<?php
session_start();
include 'connection.php';

$store_operator = mysqli_real_escape_string($con, $_SESSION["username"]);
$current_customer_name = mysqli_real_escape_string($con, $_POST["current_customer_name"]);

// transfer values from the submitted form
$name = $_POST['username'];
$birthdate = $_POST['birthdate'];
$sex = $_POST['sex'];
$mobile_no = $_POST['mobile_no'];
$address = $_POST['address'];
$rating = $_POST['rate'];

// store variables in $_SESSION superglobal to be able to access them across pages
$_SESSION['cusername-edit'] = $name;
$_SESSION['cbirthdate-edit'] = $birthdate;
$_SESSION['csex-edit'] = $sex;
$_SESSION['cmobile_no-edit'] = $mobile_no;
$_SESSION['caddress-edit'] = $address;
$_SESSION['crate-edit'] = $rating;

// escape special characters (ex. Leann's Store ==> Leann\'s Store) to avoid errors in the SQL query below
$address = mysqli_real_escape_string($con, $address);
$name = mysqli_real_escape_string($con, $name);

if (!empty($_POST['email'])){
    $email = $_POST['email'];
    $_SESSION['cemail-edit'] = $email;
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
        header("Location: customer-account.php?customer=$current_customer_name&error=information missing");
        die();
    }
}

// check if email is valid
if (!empty($email) and !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: customer-account.php?customer=$current_customer_name&error=invalid email address");
    die();
}

// check if phone number is valid
if (strlen($mobile_no) != 11 or !is_numeric($mobile_no)){
    header("Location: customer-account.php?customer=$current_customer_name&error=invalid phone number");
    die();
}

// check if username is taken
if ($name != $current_customer_name){
    $query1 = "SELECT * FROM customers WHERE name = '$name' AND store_operator = '$store_operator' limit 1";
    $result = mysqli_query($con, $query1);
    if (mysqli_num_rows($result)) {
        header("Location: customer-account.php?customer=$current_customer_name&error=username is already taken");
        die();
    }
}

// update customer information
$query2 = "UPDATE customers SET birthdate = '$birthdate', sex = '$sex', mobile_no = '$mobile_no', email = '$email', address = '$address', rating = '$rating' WHERE name = '$current_customer_name' AND store_operator = '$store_operator'";
$query3 = "UPDATE customers SET name = '$name' WHERE name = '$current_customer_name' AND store_operator = '$store_operator'";

// update information from previous payment and credit transactions
$query4 = "UPDATE payment_transactions SET customer = '$name' WHERE customer = '$current_customer_name' AND store_operator = '$store_operator'";
$query5 = "UPDATE credit_transactions SET customer = '$name' WHERE customer = '$current_customer_name' AND store_operator = '$store_operator'";

if (mysqli_query($con, $query2) && mysqli_query($con, $query3) && mysqli_query($con, $query4) && mysqli_query($con, $query5)) {
    // if new account information is successfully saved to database, we can clear the form for new input
    unset($_SESSION['cusername-edit']);
    unset($_SESSION['cbirthdate-edit']);
    unset($_SESSION['csex-edit']);
    unset($_SESSION['cmobile_no-edit']);
    unset($_SESSION['cemail-edit']);
    unset($_SESSION['caddress-edit']);
    unset($_SESSION['crate-edit']);
    header("Location: customer-account.php?customer=$name&success=account successfully edited");
    die();
} else {
    header("Location: customer-account.php?customer=$current_customer_name&error=something went wrong");
    die();
    //echo mysqli_error($con);
}
