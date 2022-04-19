<?php
session_start();
include "connection.php";

$username = $_POST['username'];
if ($_POST["accountType"] == "store owner"){
    $query = "SELECT business_name FROM stores WHERE store_operator = '$username'";
    $result = mysqli_query($con, $query);
    if (mysqli_num_rows($result) == 0){
        ?>
        <option value="none" selected>--</option>
        <?php
    }
    while($row = mysqli_fetch_assoc($result)){
        ?>
        <option value="<?php echo $row["business_name"];?>" <?php if (isset($_SESSION["business_name"]) and $_SESSION["business_name"] == $row["business_name"]) { echo 'selected';} ?>><?php echo $row["business_name"]; ?></option>
        <?php
    }
}
else if ($_POST["accountType"] == "customer"){
    $query = "SELECT business_name FROM customers WHERE name = '$username'";
    $result = mysqli_query($con, $query);
    if (mysqli_num_rows($result) == 0){
        ?>
        <option value="none" selected>--</option>
        <?php
    }
    while($row = mysqli_fetch_assoc($result)){
        ?>
        <option value="<?php echo $row["business_name"];?>" <?php if (isset($_SESSION["business_name"]) and $_SESSION["business_name"] == $row["business_name"]) { echo 'selected';} ?>><?php echo $row["business_name"]; ?></option>
        <?php
    }
}