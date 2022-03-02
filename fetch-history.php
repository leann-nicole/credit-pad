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
        $values = array();
    ?>
    <div class="history-item">
        <table class="history-item-content">
    <?php
        while($transactionInDate = mysqli_fetch_assoc($result3)){
            if ($itemsLeft == -1){
                $itemsLeft = $transactionInDate["cart_size"];
                $values = array();
    ?>
            <tr>
                <td class="credit-items-column">
    <?php
            }
            $qty = fmod($transactionInDate['quantity'], 1) ? $transactionInDate['quantity'] : floor($transactionInDate['quantity']);
            $grandTotal += $transactionInDate['subtotal'];
            array_push($values, $transactionInDate['subtotal']);
            if ($transactionInDate["comment"] != NULL){ $comment = $transactionInDate["comment"]; }
    ?>
                    <div><p title="<?php echo $qty . " " . $transactionInDate['product']; ?>"><?php echo $qty . " " . $transactionInDate['product']; ?></p>
    <?php
            $itemsLeft--;
            if (!$itemsLeft){
                $itemsLeft = -1;
    ?>
                <br>
                </td>
                <td class="credit-values-column">
    <?php
                for($j = 0; $j < count($values); $j++){
    ?>
                    <P><?php echo "₱ " . number_format(round($values[$j])); ?></P>
    <?php
                }
    ?>
                </td>
                <td class="comment-column">
                    <p><?php echo $comment;?></p>
                </td>
            </tr>
    <?php
                $comment = "";
            }
        }
    ?>   
        </table>
        <div class="history-item-header">
            <p class="history-item-credit">CREDIT</p>
            <p class="history-item-date"><?php echo date("F j, Y", strtotime($dates[$i])); ?></p>
            <p class="history-item-total"><?php echo "₱ ". number_format(round($grandTotal, 2)); ?></p>
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
        <table class="history-item-content">
    <?php
        while ($transactionInDate = mysqli_fetch_assoc($result4)){
            $grandTotal += $transactionInDate["amount_paid"];
            if ($transactionInDate["comment"]){ $comment = $transactionInDate["comment"]; }
    ?> 
            <tr>
                <td class="payment-type-column">
                    <p><?php echo $transactionInDate["payment_type"];?></p>

                </td>
                <td class="payment-items-column">
                    <p>paid</p>
                    <p>cash</p>
                    <p>change</p>
                </td>
                <td class="payment-values-column">
                    <p><?php echo "₱ " . number_format(round($transactionInDate["amount_paid"], 2)); ?></p>
                    <p><?php echo "₱ " . number_format(round($transactionInDate["cash"])); ?></p>
                    <p><?php echo "₱ " . number_format(round($transactionInDate["change_amount"])); ?></p>
                    <br>
                </td>
                <td class="comment-column">
                    <p><?php echo $comment;?></p>
                </td>
            </tr>
    <?php
            $comment = "";
        }
    ?>
        </table>
        <div class="history-item-header">
            <p class="history-item-payment">PAYMENT</p>
            <p class="history-item-date"><?php echo date("F j, Y", strtotime($dates[$i])); ?></p>
            <p class="history-item-total"><?php echo "₱ " . number_format(round($grandTotal, 2)); ?></p>
        </div>
    </div>
    <?php
    }   
}