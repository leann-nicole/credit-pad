<?php 
session_start();

include "connection.php";

$store_operator = $_SESSION['username'];
$store_operator = mysqli_real_escape_string($con, $store_operator);

if (isset($_POST['ccolname'])){
    if (!isset($_SESSION[$_POST['ccolname']])){
        $_SESSION[$_POST['ccolname']] = "ASC";
    }
    if ($_SESSION[$_POST['ccolname']] == "ASC"){
        $_SESSION[$_POST['ccolname']] = "DESC";
        $query = "SELECT * FROM customers WHERE store_operator = '$store_operator' ORDER BY {$_POST['ccolname']} DESC";
    }
    else {
        $_SESSION[$_POST['ccolname']] = "ASC";
        $query = "SELECT * FROM customers WHERE store_operator = '$store_operator' ORDER BY {$_POST['ccolname']} ASC";
    }
}
else {
    $query = "SELECT * FROM customers WHERE store_operator = '$store_operator' ORDER BY id DESC";
}
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) > 0){
?>
<table id="list-table">
    <tr>
        <th class="sortable-header" id="col1" onclick="sortCustomers(this)" data-colname="name">CUSTOMER</th>
        <th class="sortable-header" id="col2" onclick="sortCustomers(this)" data-colname="current_debt">CURRENT DEBT</th>
        <th class="sortable-header" id="col3" onclick="sortCustomers(this)" data-colname="rating">RATING</th>
    </tr>
<?php
    while($row = mysqli_fetch_assoc($result)){
?>
    <tr>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['current_debt']; ?></td>
        <td id="stars"><?php for($i = 1; $i <= $row['rating']; $i++){ echo "&#128970;";} ?></td>
    </tr>
<?php 
    }
echo "</table>";
}
else {
?>
<table id="no-record-header">
    <tr>
        <th>NO CUSTOMERS YET</th>
    </tr>
</table>
<?php
}
