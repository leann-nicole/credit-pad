<?php 
session_start();
include "connection.php";

$store_operator = mysqli_real_escape_string($con, $_SESSION["username"]);
$customer = $_POST["customer"];

$query = "SELECT DISTINCT date FROM credit_transactions WHERE store_operator = '$store_operator' AND customer = '$customer'";
$result1 = mysqli_query($con, $query);
$creditDates = array(); // convert mysqli_result object  to array of date strings
while($row = mysqli_fetch_assoc($result1)){
    array_push($creditDates, $row["date"]);
}

$query = "SELECT DISTINCT date FROM payment_transactions WHERE store_operator = '$store_operator' AND customer = '$customer'";
$result2 = mysqli_query($con, $query);
$paymentDates = array();
while($row = mysqli_fetch_assoc($result2)){
    array_push($paymentDates, $row["date"]);
}

$dates = array_unique(array_merge($creditDates, $paymentDates));

function sort_date($a, $b){
    return strtotime($b) - strtotime($a);
}

usort($dates, "sort_date");

for($i = 0; $i < sizeof($dates); $i++){
    // credits
    $query = "SELECT product, quantity, subtotal, comment, cart_size FROM credit_transactions WHERE store_operator = '$store_operator' AND customer = '$customer' AND date = '$dates[$i]'";
    $result3 = mysqli_query($con, $query);
    if (mysqli_num_rows($result3)){ // if there are credit transactions in current date
        $grandTotal = 0;
        $comment = "";
        $itemsLeft = -1;
    ?>
    <div class="history-item">
        <div class="history-item-list">
    <?php
        while($transactionInDate = mysqli_fetch_assoc($result3)){
            if ($itemsLeft == -1){
                $itemsLeft = $transactionInDate["cart_size"];
            }
            $qty = fmod($transactionInDate['quantity'], 1) ? $transactionInDate['quantity'] : floor($transactionInDate['quantity']);
            $grandTotal += $transactionInDate['subtotal'];
            if ($transactionInDate["comment"] != NULL){ $comment = $transactionInDate["comment"]; }
    ?>
            <span class="history-item-list-item"><?php echo $qty . " " . $transactionInDate['product']; ?></span>
    <?php
            $itemsLeft--;
            if (!$itemsLeft){
                $itemsLeft = -1;
    ?>
                <span class="history-item-comment"><?php echo $comment;?></span>
                <br>
    <?php
                $comment = "";
            }
        }
    ?>   
        </div>
        <div class="history-item-header">
            <span class="history-item-credit">CREDIT</span>
            <span class="history-item-date"><?php echo date("F j, Y", strtotime($dates[$i])); ?></span>
            <span class="history-item-total"><?php echo "₱ ". number_format(round($grandTotal, 2)); ?></span>
        </div>
    </div>
    <?php
    }
    // payments
    $query = "SELECT payment_type, date, cash, amount_paid, change_amount, comment FROM payment_transactions WHERE store_operator = '$store_operator' AND customer = '$customer' AND date = '$dates[$i]' ORDER BY id DESC";
    $result4 = mysqli_query($con, $query); 
    if (mysqli_num_rows($result4)){
        $grandTotal = 0;
        $comment = "";
    ?>
    <div class="history-item">
        <div class="history-item-list">
    <?php
        while ($transactionInDate = mysqli_fetch_assoc($result4)){
            $grandTotal += $transactionInDate["amount_paid"];
            if ($transactionInDate["comment"]){ $comment = $transactionInDate["comment"]; }
    ?> 
            <span class="history-item-list-item"><?php echo "₱ " . number_format(round($transactionInDate["amount_paid"], 2)) . " " . $transactionInDate["payment_type"]; ?></span>
            <span class="history-item-list-item">cash received: <?php echo "₱ " . number_format(round($transactionInDate["cash"])); ?></span>
            <span class="history-item-list-item">change: <?php echo "₱ " . number_format(round($transactionInDate["change_amount"])); ?></span>
            <span class="history-item-comment"><?php echo $comment;?></span>
            <br>
    <?php
            $comment = "";
        }
    ?>
        </div>
        <div class="history-item-header">
            <span class="history-item-payment">PAYMENT</span>
            <span class="history-item-date"><?php echo date("F j, Y", strtotime($dates[$i])); ?></span>
            <span class="history-item-total"><?php echo "₱ " . number_format(round($grandTotal, 2)); ?></span>
        </div>
    </div>
    <?php
    }   
}