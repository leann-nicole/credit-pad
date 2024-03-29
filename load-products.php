<?php
session_start();
include 'connection.php';

$store = mysqli_real_escape_string($con, $_SESSION["business_name"]);

if (isset($_POST['pcolname'])) {
    if (!isset($_SESSION[$_POST['pcolname']])) {
        $_SESSION[$_POST['pcolname']] = 'DESC';
    }

    if ($_SESSION[$_POST['pcolname']] == 'ASC') {
        $_SESSION[$_POST['pcolname']] = 'DESC';
        if ($_POST['pcolname'] == "pname") $_POST['pcolname'] = substr($_POST['pcolname'], 1);
        $query = "SELECT * FROM products WHERE business_name = '$store' ORDER BY {$_POST['pcolname']} DESC, name ASC";
    } else {
        $_SESSION[$_POST['pcolname']] = 'ASC';
        if ($_POST['pcolname'] == "pname") $_POST['pcolname'] = substr($_POST['pcolname'], 1);
        $query = "SELECT * FROM products WHERE business_name = '$store' ORDER BY {$_POST['pcolname']} ASC, name ASC";
    }
} else {
    unset($_SESSION["pname"]);
    unset($_SESSION["category"]);
    unset($_SESSION["price"]);

    $query = "SELECT * FROM products WHERE business_name = '$store' ORDER BY id DESC";
}
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) > 0) { ?>
<table class="list-table" id="product-list-table">
    <tr>
        <th class="sortable-header" id="col1" onclick="sortProducts(this)" data-colname="pname">PRODUCT<span class="material-icons">arrow_drop_up</span></th>
        <th class="sortable-header" id="col2" onclick="sortProducts(this)" data-colname="category">CATEGORY<span class="material-icons">arrow_drop_up</span></th>
        <th class="sortable-header" id="col3" onclick="sortProducts(this)" data-colname="price">PRICE<span class="material-icons">arrow_drop_up</span></th>
    </tr>
<?php
while ($row = mysqli_fetch_assoc($result)) { ?>
    <tr onclick="selectProduct(this)">
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['category']; ?></td>
        <td><?php if (fmod($row['price'],1)){ echo "₱ " . number_format($row['price'], 2);} else { echo "₱ " . number_format($row['price']); } ?></td>
    </tr>
<?php }
echo '</table>';
} else { ?>
<table id="no-record-header">
    <tr>
        <td>No products yet.</td>
    </tr>
</table>
<?php }
