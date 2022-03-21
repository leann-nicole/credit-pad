<?php
session_start();
include "connection.php";

$store = mysqli_real_escape_string($con, $_SESSION["business_name"]);

$query = "SELECT DISTINCT category FROM products WHERE business_name = '$store'";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result)){
    while ($row = mysqli_fetch_assoc($result)){
?>
<option value="<?php echo $row['category']; ?>"><?php echo $row['category']; ?></option>
<?php
    }
}
