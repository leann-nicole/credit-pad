<?php
session_start();
if (!isset($_POST['username'])){ // if the $_POST superglobal is not populated, it means the user didn't come from login page
    header("Location: login.php");
    die();
}

include 'connection.php';

// transfer the variables from the submitted form
$username = $_POST['username'];
$password = $_POST['password'];

// transfer variables to $_SESSION superglobal to be able to access across pages
$_SESSION['username'] = $username;
$_SESSION['password'] = $password;

// escape special charactesr in the string (such as the apostrophe in "Leann's Store" ==> "Leann\'s Store") to avoid errors in the SQL query below
$username = mysqli_real_escape_string($con, $username);
$password = mysqli_real_escape_string($con, $password);

// check for missing information
function filled($data)
{
    $data = trim($data); // trims leading and trailing whitespace
    $data = stripslashes($data); // removes the slashes inserted by the addslashes function (prevent special characters from being escaped)
    $data = htmlspecialchars($data); // converts predefined characters into html entities (prevents sql injection)
    if (!empty($data)) {
        return true;
    } else {
        return false;
    }
}

// loop through the contents of $_POST
foreach ($_POST as $post_var) {
    if (!filled($post_var)) {
        //check if variable is empty
        header('Location: login.php?error=information missing');
        die();
    }
}

// check if username and password are correct
$query = "SELECT * FROM store_operators WHERE username = '$username' limit 1";
$result = mysqli_query($con, $query); // mysqli_query() returns a mysqli_result object when successful, false if failed
$row = mysqli_fetch_assoc($result); // mysqli_fetch_assoc() returns an associative array corresponding to the fetched row. ex. $row = array("name"=>"Leann", "age"=>"42");
if (mysqli_num_rows($result) == 0 or $row['password'] != $password) {
    // if user does not exist or password is incorrect
    header('Location: login.php?error=incorrect username or password');
    die();
} else {
    header('Location: customers.php');
    die();
}
