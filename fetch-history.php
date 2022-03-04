<?php 
session_start();
include "connection.php";

$store_operator = mysqli_real_escape_string($con, $_SESSION["username"]);
$customer = $_POST["customer"];

$query = "SELECT DISTINCT date FROM credit_transactions WHERE store_operator = '$store_operator' AND customer = '$customer'";
$resultCreditDates = mysqli_query($con, $query);
$creditDates = array(); // convert mysqli_result object  to array of date strings
while($row = mysqli_fetch_assoc($resultCreditDates)){
    array_push($creditDates, $row["date"]);
}

$query = "SELECT DISTINCT date FROM payment_transactions WHERE store_operator = '$store_operator' AND customer = '$customer'";
$resultPaymentDates = mysqli_query($con, $query);
$paymentDates = array();
while($row = mysqli_fetch_assoc($resultPaymentDates)){
    array_push($paymentDates, $row["date"]);
}

$dates = array_unique(array_merge($creditDates, $paymentDates));

function sort_date($a, $b){
    return strtotime($b) - strtotime($a);
}

usort($dates, "sort_date");

for($i = 0; $i < sizeof($dates); $i++){
    // CREDIT
    $query = "SELECT * FROM credit_transactions WHERE store_operator = '$store_operator' AND customer = '$customer' AND date = '$dates[$i]'";
    $resultCreditsInDate = mysqli_query($con, $query);
    // check if there are credit transactions in the given date
    // get the number of entries that occured in that date
    // 1 entry no = 1 history item
    if (!mysqli_num_rows($resultCreditsInDate)) break;
    $query = "SELECT MAX(entry_no) as entries FROM credit_transactions WHERE store_operator = '$store_operator' AND customer = '$customer' AND date = '$dates[$i]'";
    $entries = mysqli_fetch_assoc(mysqli_query($con, $query))["entries"]; 
    for ($x = $entries; $x >= 1; $x--){ // traverse each entry no
        $query = "SELECT product, quantity, subtotal, comment FROM credit_transactions WHERE store_operator = '$store_operator' AND customer = '$customer' AND date = '$dates[$i]' AND entry_no = '$x'";
        $result = mysqli_query($con, $query);
        $comment = "";
        $grandTotal = 0;
        ?>
        <div class="history-item">
            <table class="history-item-content"> 
                <tr>
                    <td class="credit-content-column">
                        <table class="credit-content-table"><?php
        while ($creditTransaction = mysqli_fetch_assoc($result)){
            $comment .= $creditTransaction["comment"];
            $grandTotal += $creditTransaction["subtotal"];?>
                            <tr>
                                <td class="credit-items-column">
                                    <p><?php echo number_format(round($creditTransaction["quantity"])) . " " . $creditTransaction["product"]; ?></p>
                                </td>
                                <td class="credit-values-column">
                                    <p><?php echo "₱ " . number_format(round($creditTransaction["subtotal"])); ?></p>
                                </td>
                            </tr>
        <?php
        }
        ?>
                        </table>
                    </td>
                    <td class="comment-column">
                        <p><?php echo $comment; ?></p>
                    </td>
                </tr>
            </table>
            <div class="history-item-header">
                <p class="history-item-credit">CREDIT</p>
                <p class="history-item-date"><?php echo date("F j, Y", strtotime($dates[$i])); ?></p>
                <p class="history-item-total"><?php echo "₱ ". number_format(round($grandTotal)); ?></p>
            </div>
        </div><?php
    }
    // PAYMENT
    $query = "SELECT * FROM payment_transactions WHERE store_operator = '$store_operator' AND customer = '$customer' AND date = '$dates[$i]'";
    $resultPaymentsInDate = mysqli_query($con, $query);
    // check if there are payment transactions in the given date
    // get the number of entries that occured in that date
    // 1 entry no = 1 history item
    if (!mysqli_num_rows($resultPaymentsInDate)) break;
    $query = "SELECT MAX(entry_no) as entries FROM payment_transactions WHERE store_operator = '$store_operator' AND customer = '$customer' AND date = '$dates[$i]'";
    $entries = mysqli_fetch_assoc(mysqli_query($con, $query))["entries"]; 
    for ($x = $entries; $x >= 1; $x--){ // traverse each entry no
        $query = "SELECT payment_type, cash, amount_paid, change_amount, comment FROM payment_transactions WHERE store_operator = '$store_operator' AND customer = '$customer' AND date = '$dates[$i]' AND entry_no = '$x'";
        $result = mysqli_query($con, $query);
        $comment = "";
        $paid = 0;
        ?>
        <div class="history-item">
            <table class="history-item-content"> 
                <tr><?php
        while ($paymentTransaction = mysqli_fetch_assoc($result)){
            $comment .= $paymentTransaction["comment"];
            $paid = $paymentTransaction["cash"];?>
                    <td class="payment-type-column">
                        <p><?php echo $paymentTransaction["payment_type"]; ?></p>
                    </td>
                    <td class="payment-items-column">
                        <p><?php echo "cash"; ?></p>
                        <p><?php echo "paid"; ?></p>
                        <p><?php echo "change"; ?></p>
                    </td>
                    <td class="payment-values-column">
                        <p><?php echo "₱ " . number_format(round($paymentTransaction["cash"])); ?></p>
                        <p><?php echo "₱ " . number_format(round($paymentTransaction["amount_paid"])); ?></p>
                        <p><?php echo "₱ " . number_format(round($paymentTransaction["change_amount"])); ?></p>
                    </td>
        <?php
        }
        ?>
                    <td class="comment-column">
                        <p><?php echo $comment; ?></p>
                    </td>
                </tr>
            </table>
            <div class="history-item-header">
                <p class="history-item-payment">PAYMENT</p>
                <p class="history-item-date"><?php echo date("F j, Y", strtotime($dates[$i])); ?></p>
                <p class="history-item-total"><?php echo "₱ ". number_format(round($paid)); ?></p>
            </div>
        </div><?php
    }
}
