<?php
session_start();
session_unset(); 
session_destroy(); 

// session_unset() unsets all session variables, similar to $_SESSION = array();
// session_destroy() terminates the session, destroys the data about the session like session_id() (not the variables, that is done by session_unset())
// however, PHPSESSID cookie ($_COOKIE['PHPSESSID']) will still exist in the server and the browser, although without data now
// by default, PHPSESSID cookie automatically expires when browser is closed
// it's good practice to not wait for this
// therefore also delete the PHPSESSID cookie yourself
// code below does that. it is from: https://csveda.com/php-destroy-session-and-unset-session-variables/

if (isset($_COOKIE[session_name()])) { // session_name() is PHPSESSID
    setcookie(session_name(), '', time() - 3600, '/'); // expiration set to 3600 seconds or 1 hour in the past, equivalent to deleting the cookie, / refers to diretory where cookie is stored
}

header('Location: login.php');
die();
