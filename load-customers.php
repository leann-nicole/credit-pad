<?php
session_start();
include "connection.php";

$store_operator = $_SESSION['username'];
$store_operator = mysqli_real_escape_string($con, $store_operator);

if (isset($_POST['ccolname'])){ // if loading list with an order to sort by a particular column (happens through the ajax)
    if (!isset($_SESSION[$_POST['ccolname']])){ // default action is to sort names in ascending order
        $_SESSION[$_POST['ccolname']] = "ASC"; // remember the order, to be used when table column header is clicked again, see if-else codes below
    }
    // asc to desc, desc to asc
    if ($_SESSION[$_POST['ccolname']] == "ASC"){
        $_SESSION[$_POST['ccolname']] = "DESC";
        $query = "SELECT * FROM customers WHERE store_operator = '$store_operator' ORDER BY {$_POST['ccolname']} DESC, name ASC";
    }
    else {
        $_SESSION[$_POST['ccolname']] = "ASC";
        $query = "SELECT * FROM customers WHERE store_operator = '$store_operator' ORDER BY {$_POST['ccolname']} ASC, name ASC";
    }
}
else { // default is sort by the time the record was created in the database
    $query = "SELECT * FROM customers WHERE store_operator = '$store_operator' ORDER BY id DESC";
}
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) > 0){
?>
<table class="list-table" id="customer-list-table">
    <tr>
        <th class="sortable-header" id="col1" onclick="sortCustomers(this)" data-colname="name">CUSTOMER</th>
        <th class="sortable-header" id="col2" onclick="sortCustomers(this)" data-colname="current_debt">CREDIT</th>
        <th class="sortable-header" id="col3" onclick="sortCustomers(this)" data-colname="rating">RATING</th>
    </tr>
<?php
    while($row = mysqli_fetch_assoc($result)){
?>
    <tr onclick="selectCustomer(this)">
        <td><?php echo $row['name']; ?></td>
        <td><?php if (fmod($row['current_debt'],1)){ echo number_format($row['current_debt'],2);} else { echo number_format($row['current_debt']); } ?></td>
        <td class="star"><?php for($i = 1; $i <= $row['rating']; $i++){ echo "&#128970;";} ?></td>
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
