<?php
session_start();
include "connection.php";

$store_operator = $_SESSION['username'];
$store_operator = mysqli_real_escape_string($con, $store_operator);

$query = "SELECT DISTINCT category FROM products WHERE store_operator = '$store_operator' AND category <> 'None'";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result)){
    while ($row = mysqli_fetch_assoc($result)){
?>
<option value="<?php echo $row['category']; ?>"><?php echo $row['category']; ?></option>
<?php
    }
}
