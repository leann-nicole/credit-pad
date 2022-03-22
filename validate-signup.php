<?php
session_start();
if (!isset($_POST['username'])) {
    // if the $_POST superglobal is not populated, it means the user didn't come from signup page
    header('Location: signup.php');
    die();
}

include 'connection.php'; // we will be using the connection in our queries

// transfer the variables from the submitted form
$username = $_POST['username'];
$birthdate = $_POST['birthdate'];
$sex = $_POST['sex'];
$mobile_no = $_POST['mobile_no'];
$email = $_POST['email'];
$business_name = $_POST['business_name'];
$business_addr = $_POST['business_addr'];
$password = $_POST['password'];

// store them in the $_SESSION superglobal to be able to access them across pages
$_SESSION['username'] = $username;
$_SESSION['birthdate'] = $birthdate;
$_SESSION['sex'] = $sex;
$_SESSION['mobile_no'] = $mobile_no;
$_SESSION['email'] = $email;
$_SESSION['business_name'] = $business_name;
$_SESSION['business_addr'] = $business_addr;
$_SESSION['password'] = $password;

// escape special characters (such as the apostrophe in "Leann's Store" ==> "Leann\'s Store") to avoid errors in the SQL query below
$username = mysqli_real_escape_string($con, $username);
$business_name = mysqli_real_escape_string($con, $business_name);
$business_addr = mysqli_real_escape_string($con, $business_addr);
$password = mysqli_real_escape_string($con, $password);

// a function to check for missing information
function filled($data)
{
    $data = trim($data); // removes whitespace from the beginning and end of the string
    $data = stripslashes($data); // removes slashes added by addslashes function, prevents special characters from being escaped
    $data = htmlspecialchars($data); // converts predefined characters into html entities (prevents sql injection)
    if (!empty($data)) {
        return true;
    } else {
        return false;
    }
}

// loop through the contents of $_POST
foreach ($_POST as $post_var) {
    // check whether each variable in the $_POST superglobal is not empty
    if (!filled($post_var)) {
        header('Location: signup.php?error=information missing');
        die();
    }
}

// check if business is already registered by someone
$query = "SELECT * FROM stores WHERE business_name = '$business_name' limit 1"; // if the given username matches at least 1 username already in the database, ask user to provide another username
$result = mysqli_query($con, $query); // mysqli_query() returns a mysqli_result object for successful SELECT query, false on failure
if (mysqli_num_rows($result)) {
    // if row == 1, username is already taken
    header('Location: signup.php?error=business is already registered');
    die();
}

// check if email is valid
if (!empty($email) and !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: signup.php?error=invalid email address');
    die();
}

// check if phone number is valid
if (strlen($mobile_no) != 11 or !is_numeric($mobile_no)){
    header("Location: signup.php?error=invalid phone number");
    die();
}

// save data to database
$query1 = "INSERT INTO store_operators (username, birthdate, sex, mobile_no, email) VALUES ('$username', '$birthdate', '$sex', '$mobile_no', '$email')";
$query2 = "INSERT INTO stores (business_name, store_operator, business_addr, password) VALUES ('$business_name', '$username', '$business_addr', '$password')";

if (mysqli_query($con, $query1) && mysqli_query($con, $query2)) {
    // mysqli_query() returns true or false for INSERT query
    unset($_SESSION['password']);
    header('Location: login.php?success=account successfully created');
    die();
} else {
    header('Location: login.php?error=something went wrong');
    die();
    //echo mysqli_error($con);
}
