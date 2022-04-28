<?php
session_start();
include "connection.php";

$store = mysqli_real_escape_string($con, $_SESSION["business_name"]);
$customer = mysqli_real_escape_string($con, $_POST["customer"]);
$period = $_POST["period"];

function getRecords($transactionDate, $dueDate){
    $records = array();
    global $store, $customer, $con;
    // get credit transactions occuring between current week
    $query = "SELECT * FROM credit_transactions WHERE business_name = '$store' AND customer = '$customer' AND " . $transactionDate;
    $records[] = mysqli_query($con, $query);

    // get credit transactions occuring between current week
    $query = "SELECT * FROM payment_transactions WHERE business_name = '$store' AND customer = '$customer' AND " . $transactionDate;
    $records[] = mysqli_query($con, $query);

    // get due credit transactions occuring between current week
    $query = "SELECT * FROM credit_transactions WHERE business_name = '$store' AND customer = '$customer' AND (status = 'unpaid' OR status IS NULL) AND " . $dueDate;
    $records[] = mysqli_query($con, $query);

    return $records;
}

function displayStats($transactionDate, $dueDate){
    $totalCredit = 0;
    $totalPayment = 0;
    $totalDue = 0;
    $records = getRecords($transactionDate, $dueDate);
?>
<div id="stats">
<?php
    // print total credit
    while ($row = mysqli_fetch_assoc($records[0])) $totalCredit += $row["subtotal"];
    ?>
    <div id="period-totals">
        <p>Total Credit</p>
        <br>
        <p>₱ <?php if (fmod($totalCredit, 1)) echo number_format($totalCredit, 2); else echo number_format($totalCredit); ?><p>
    </div>
    <?php
    // print total payment
    while ($row = mysqli_fetch_assoc($records[1])) $totalPayment += $row["amount_paid"];
    ?>
    <div id="period-totaLs">
        <p>Total Payment</p>
        <br>
        <p>₱ <?php if (fmod($totalPayment, 1)) echo number_format($totalPayment, 2); else echo number_format($totalPayment); ?><p>
    </div>
    <?php
    // print total due
    while ($row = mysqli_fetch_assoc($records[2])) $totalDue += $row["subtotal"];
    ?>
    <div id="period-totals">
        <p>Total Due</p>
        <br>
        <p>₱ <?php if (fmod($totalDue, 1)) echo number_format($totalDue, 2); else echo number_format($totalDue); ?><p>
    </div>
</div>
    <?php
}

function getEntryTable($weekday, $i){?>
    <td>
        <table class="transaction-table">
            <tr><?php
                if ($weekday[$i]["transaction-type"] == "payment"){?>
                <td class="paid-column">₱ <?php if(fmod($weekday[$i]["amount_paid"], 1)) echo number_format($weekday[$i]["amount_paid"], 2); else echo number_format($weekday[$i]["amount_paid"]); echo " " . $weekday[$i]["payment_type"]; ?></td>
                <td class="cash-column">₱ <?php if(fmod($weekday[$i]["cash"], 1)) echo number_format($weekday[$i]["cash"], 2); else echo number_format($weekday[$i]["cash"]); echo " cash"; ?></td>
                <td class="change-column">₱ <?php if(fmod($weekday[$i]["change_amount"], 1)) echo number_format($weekday[$i]["change_amount"], 2); else echo number_format($weekday[$i]["change_amount"]); echo " change"; ?></td>
                <?php }
                else if ($weekday[$i]["transaction-type"] == "credit"){?>
                <td class="subtotal-column">₱ <?php if(fmod($weekday[$i]["subtotal"], 1)) echo number_format($weekday[$i]["subtotal"], 2); else echo number_format($weekday[$i]["subtotal"]); ?></td>
                <td class="items-column"><?php if(fmod($weekday[$i]["quantity"], 1)) echo number_format($weekday[$i]["quantity"], 2); else echo number_format($weekday[$i]["quantity"]); echo " " . $weekday[$i]["product"]; ?></td>
                <?php }
                else if ($weekday[$i]["transaction-type"] == "due"){?>
                <td class="subtotal-column">₱ <?php if(fmod($weekday[$i]["subtotal"], 1)) echo number_format($weekday[$i]["subtotal"], 2); else echo number_format($weekday[$i]["subtotal"]); ?></td>
                <td class="items-column"><?php if(fmod($weekday[$i]["quantity"], 1)) echo number_format($weekday[$i]["quantity"], 2); else echo number_format($weekday[$i]["quantity"]); echo " " . $weekday[$i]["product"]; ?></td>
                <?php } ?>
            </tr><?php
        while ($i + 1 < count($weekday) && $weekday[$i + 1]["entry_no"] == $weekday[$i]["entry_no"]){ 
            $i++;?>
            <tr><?php
                if ($weekday[$i]["transaction-type"] == "payment"){?>
                <td class="paid-column">₱ <?php if(fmod($weekday[$i]["amount_paid"], 1)) echo number_format($weekday[$i]["amount_paid"], 2); else echo number_format($weekday[$i]["amount_paid"]); echo " " . $weekday[$i]["payment_type"]; ?></td>
                <td class="cash-column">₱ <?php if(fmod($weekday[$i]["cash"], 1)) echo number_format($weekday[$i]["cash"], 2); else echo number_format($weekday[$i]["cash"]); echo " cash"; ?></td>
                <td class="change-column">₱ <?php if(fmod($weekday[$i]["change_amount"], 1)) echo number_format($weekday[$i]["change_amount"], 2); else echo number_format($weekday[$i]["change_amount"]); echo " change"; ?></td>
                <?php }
                else if ($weekday[$i]["transaction-type"] == "credit"){?>
                <td class="subtotal-column">₱ <?php if(fmod($weekday[$i]["subtotal"], 1)) echo number_format($weekday[$i]["subtotal"], 2); else echo number_format($weekday[$i]["subtotal"]); ?></td>
                <td class="items-column"><?php if(fmod($weekday[$i]["quantity"], 1)) echo number_format($weekday[$i]["quantity"], 2); else echo number_format($weekday[$i]["quantity"]); echo " " . $weekday[$i]["product"]; ?></td>
                <?php }
                else if ($weekday[$i]["transaction-type"] == "due"){?>
                <td class="date-column"><?php echo date("M j", strtotime($weekday[$i]["due_date"])); ?></td>
                <td class="subtotal-column">₱ <?php if(fmod($weekday[$i]["subtotal"], 1)) echo number_format($weekday[$i]["subtotal"], 2); else echo number_format($weekday[$i]["subtotal"]); ?></td>
                <td class="items-column"><?php if(fmod($weekday[$i]["quantity"], 1)) echo number_format($weekday[$i]["quantity"], 2); else echo number_format($weekday[$i]["quantity"]); echo " " . $weekday[$i]["product"]; ?></td>
                <?php } ?>
            </tr><?php
        } ?>
        </table>
    </td><?php
    return $i;
}

function sortTransactions($a, $b){
    return ($a["entry_no"] > $b["entry_no"])? -1 : 1;
}

function displayTable($transactionDate, $dueDate, $period){
    $records = getRecords($transactionDate, $dueDate);
    $weekdays = [[]]; //  0 sunday, 1 monday, 2 tuesday, ...
    if ($period == "week"){
        // distribute credit transactions to respective weekday
        while ($row = mysqli_fetch_assoc($records[0])){
            $row["transaction-type"] = "credit";
            $weekday = date("w", strtotime($row["date"]));
            $weekdays[$weekday][] = $row;
        }
        // distribute payment transactions to respective weekday
        while ($row = mysqli_fetch_assoc($records[1])){
            $row["transaction-type"] = "payment";
            $weekday = date("w", strtotime($row["date"]));
            $weekdays[$weekday][] = $row;
        }

        // arrange credit and payment transactions within each day of the week by entry no
        foreach($weekdays as $index => $value){
            if (count($weekdays[$index])) usort($weekdays[$index], "sortTransactions");
        }
        // distribute due credit transactions to respective weekday, they will be listed last in the table
        while ($row = mysqli_fetch_assoc($records[2])){
            $row["transaction-type"] = "due";
            $weekday = date("w", strtotime($row["due_date"]));
            $weekdays[$weekday][] = $row;
        } 
        // print the table
        foreach($weekdays as $weekday){
            if (count($weekday)){ // if there are transactions within the weekday ?> 
                <div class="weekday-header" onclick="toggleContent(this)">
                    <p><?php echo strtoupper(date("l", strtotime($weekday[0]["date"]))); ?></p>
                </div>
                <div class="weekday-content">
                    <table><?php
                    for($i = 0; $i < count($weekday); $i++){?>
                        <tr class="weekday-transaction"><?php
                        if ($weekday[$i]["transaction-type"] == "credit" && $weekday[$i]["status"] == "paid"){?>
                            <td class="credit-transaction-type"><p>C</p></td>
                            <td class="date-column"><?php echo date("M j", strtotime($weekday[$i]["date"])); ?></td><?php
                            $i = getEntryTable($weekday, $i);
                        }
                        else if ($weekday[$i]["transaction-type"] == "credit" && ($weekday[$i]["status"] == "unpaid" || $weekday[$i]["status"] == NULL)){?>
                            <td class="unpaid-credit-transaction-type"><p>C</p></td>
                            <td class="date-column"><?php echo date("M j", strtotime($weekday[$i]["date"])); ?></td><?php 
                            $i = getEntryTable($weekday, $i);
                        }
                        else if ($weekday[$i]["transaction-type"] == "payment"){?>
                            <td class="payment-transaction-type"><p>P</p></td>
                            <td class="date-column"><?php echo date("M j", strtotime($weekday[$i]["date"])); ?></td><?php 
                            $i = getEntryTable($weekday, $i);
                        }
                        else if ($weekday[$i]["transaction-type"] == "due"){?>
                            <td class="due-transaction-type"><p>D</p></td>
                            <td class="date-column"><?php echo date("M j", strtotime($weekday[$i]["due_date"])); ?></td><?php 
                            $i = getEntryTable($weekday, $i);
                        }?>
                        </tr><?php
                    }?>
                    </table>
                </div>
                <?php
            }
        }
    }
}

if ($period == "week"){
    global $period;
    // get sunday date and saturday date
    $dayOfWeek = date("w") + 1;
    if ($dayOfWeek == 2 or $dayOfWeek == 6){
        $before = "-" . ($dayOfWeek - 1) . " day";
        $after = "+" . (7 - $dayOfWeek) . " day";
    }
    else {
        $before = "-" . ($dayOfWeek - 1) . " days";
        $after = "+" . (7 - $dayOfWeek) . " days";
    }
    $startDate = date("Y-m-d", strtotime($before));
    $endDate = date("Y-m-d", strtotime($after));

    $transactionDate = "date BETWEEN '$startDate' AND '$endDate'";
    $dueDate = "due_date BETWEEN '$startDate' AND '$endDate'";
    displayStats($transactionDate, $dueDate);
    displayTable($transactionDate, $dueDate, $period);
}
else if ($period == "month"){
    global $period;
    $month = date("m");
    $year = date("Y");
    $transactionDate = "MONTH(date) = '$month' AND YEAR(date) = '$year'";
    $dueDate = "MONTH(due_date) = '$month' AND YEAR(due_date) = '$year'";
    displayStats($transactionDate, $dueDate);
    displayTable($transactionDate, $dueDate, $period);
}
else if ($period == "year"){
    global $period;
    $year = date("Y");
    $transactionDate = "YEAR(date) = '$year'";
    $dueDate = "YEAR(due_date) = '$year'";
    displayStats($transactionDate, $dueDate);
    displayTable($transactionDate, $dueDate, $period);
}