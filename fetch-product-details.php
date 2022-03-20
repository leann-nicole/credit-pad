<?php
session_start();
include 'connection.php';

$store_operator = mysqli_real_escape_string($con, $_SESSION['username']);
$product_name = mysqli_real_escape_string($con, $_POST['productName']);

$query = "SELECT * FROM products WHERE name = '$product_name'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);
?>

<div id="product-name-info"><?php echo $row['name']; ?></div>
<div id="product-category-info"><?php echo "Category<br>" . $row['category']; ?></div>
<div id="product-description-info"><?php echo "Description<br>" . $row['description']; ?></div>
<div id="product-price-info"><?php echo "Price<br>";if (fmod($row['price'], 1)) echo "₱ " . number_format($row['price'], 2); else echo "₱ " . number_format($row['price']); ?></div>

<button id="edit-button" class="material-icons button" onclick="toggleEditForm()" title="edit">edit</button>
