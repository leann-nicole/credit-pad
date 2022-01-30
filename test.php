<?php
include "connection.php";
$names = array("Leann's", "Moi's est'");
foreach($names as &$a){
    $a = mysqli_real_escape_string($con, $a);
}
print_r($names);