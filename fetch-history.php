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
    $query = "SELECT MAX(entry_no) FROM credit_transactions WHERE store_operator = '$store_operator' AND customer = '$customer' AND date = '$dates[$i]'";
    $latestCreditEntryNo = mysqli_fetch_row(mysqli_query($con, $query))[0];

    $query = "SELECT MAX(entry_no) FROM payment_transactions WHERE store_operator = '$store_operator' AND customer = '$customer' AND date = '$dates[$i]'";
    $latestPaymentEntryNo = mysqli_fetch_row(mysqli_query($con, $query))[0];

    // get the number of entries for current date
    $entries = max($latestCreditEntryNo, $latestPaymentEntryNo);

    for ($entryNo = $entries; $entryNo >= 1; $entryNo--){
        $query = "SELECT product, quantity, subtotal, comment FROM credit_transactions WHERE store_operator = '$store_operator' AND customer = '$customer' AND date = '$dates[$i]' AND entry_no = '$entryNo'";
        $resultCredit = mysqli_query($con, $query);
        if (mysqli_num_rows($resultCredit)){ // if there are records with the date and the entry no exist in the CREDITS table
            $comment = "";
            $grandTotal = 0;
            ?>
            <div class="history-item">
                <table class="history-item-content"> 
                    <tr>
                        <td class="credit-content-column">
                            <table class="credit-content-table"><?php
            while ($creditTransaction = mysqli_fetch_assoc($resultCredit)){
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
        else { // else they exist in the PAYMENTS table
            $query = "SELECT payment_type, cash, amount_paid, change_amount, comment FROM payment_transactions WHERE store_operator = '$store_operator' AND customer = '$customer' AND date = '$dates[$i]' AND entry_no = '$entryNo'";
            $resultPayment = mysqli_query($con, $query);
            $comment = "";
            $paid = 0;
            ?>
            <div class="history-item">
                <table class="history-item-content"> 
                    <tr><?php
            while ($paymentTransaction = mysqli_fetch_assoc($resultPayment)){
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
}