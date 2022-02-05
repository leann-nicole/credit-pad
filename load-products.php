<?php
session_start();
include 'connection.php';

$store_operator = $_SESSION['username'];
$store_operator = mysqli_real_escape_string($con, $store_operator);

if (isset($_POST['pcolname'])) {
    if (!isset($_SESSION[$_POST['pcolname']])) {
        $_SESSION[$_POST['pcolname']] = 'ASC';
    }
    
    if ($_SESSION[$_POST['pcolname']] == 'ASC') {
        $_SESSION[$_POST['pcolname']] = 'DESC';
        $query = "SELECT * FROM products WHERE store_operator = '$store_operator' ORDER BY {$_POST['pcolname']} DESC, name ASC";
    } else {
        $_SESSION[$_POST['pcolname']] = 'ASC';
        $query = "SELECT * FROM products WHERE store_operator = '$store_operator' ORDER BY {$_POST['pcolname']} ASC, name ASC";
    }
} else {
    $query = "SELECT * FROM products WHERE store_operator = '$store_operator' ORDER BY id DESC";
}
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) > 0) { ?>
<table id="list-table">
    <tr>
        <th class="sortable-header" id="col1" onclick="sortProducts(this)" data-colname="name">PRODUCT</th>
        <th class="sortable-header" id="col2" onclick="sortProducts(this)" data-colname="category">CATEGORY</th>
        <th class="sortable-header" id="col3" onclick="sortProducts(this)" data-colname="price">PRICE</th>
    </tr>
<?php
while ($row = mysqli_fetch_assoc($result)) { ?>
    <tr onclick="selectProduct(this)">
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['category']; ?></td>
        <td><?php echo $row['price']; ?></td>
    </tr>
<?php }
echo '</table>';
} else { ?>
<table id="no-record-header">
    <tr>
        <th>NO PRODUCTS YET</th>
    </tr>
</table>
<?php }