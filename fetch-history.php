<?php 
session_start();
include "connection.php";

$store_operator = mysqli_real_escape_string($con, $_SESSION["username"]);
$customer = $_POST["customer"];

$query = "SELECT DISTINCT date FROM credit_transactions WHERE store_operator = '$store_operator' AND customer = '$customer' ORDER BY date DESC";
$result1 = mysqli_query($con, $query);

while($transactionDate = mysqli_fetch_assoc($result1)){
    $date = $transactionDate['date'];
    $query = "SELECT product, quantity, subtotal, comment FROM credit_transactions WHERE store_operator = '$store_operator' AND customer = '$customer' AND date = '$date'";
    $result2 = mysqli_query($con, $query);
    $grandTotal = 0;
    $comment = "";
?>
<div class="history-item">
    <div class="history-item-list">
<?php
    while($transactionInDate = mysqli_fetch_assoc($result2)){
        $qty = fmod($transactionInDate['quantity'], 1) ? $transactionInDate['quantity'] : floor($transactionInDate['quantity']);
        $grandTotal += $transactionInDate['subtotal'];
        if ($transactionInDate["comment"] != ""){ $comment .= "<br>" . $transactionInDate["comment"]; }
?>
        <span class="history-item-list-item"><?php echo $qty . " " . $transactionInDate['product']; ?></span>
<?php
}
?>   
        <span class="history-item-comment"><?php echo $comment;?></span>
    </div>
    <div class="history-item-header">
        <span class="history-item-date"><?php echo date("F j, Y", strtotime($transactionDate["date"])); ?></span>
        <span class="history-item-total"><?php echo "₱ ".$grandTotal; ?></span>
    </div>
</div>
<?php
}