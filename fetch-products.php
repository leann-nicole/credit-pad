<?php
session_start();
include "connection.php";

$store_operator = $_SESSION["username"];
$store_operator = mysqli_real_escape_string($con, $store_operator);

$query = "SELECT name FROM products WHERE store_operator = '$store_operator'";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result)){
    while ($row = mysqli_fetch_row($result)){
?>
<option value="<?php echo $row[0]; ?>"><?php echo $row[0]; ?></option>
<?php
    }
}

