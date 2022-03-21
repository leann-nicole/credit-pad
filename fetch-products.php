<?php
session_start();
include "connection.php";

$store = mysqli_real_escape_string($con, $_SESSION["business_name"]);

$query = "SELECT name FROM products WHERE business_name = '$store'";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result)){
    while ($row = mysqli_fetch_row($result)){
?>
<option value="<?php echo $row[0]; ?>"><?php echo $row[0]; ?></option>
<?php
    }
}

