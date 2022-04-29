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

function weekOfMonth($date) {
    $firstOfMonth = date("Y-m-01", $date);
    return intval(date("W", $date)) - intval(date("W", strtotime($firstOfMonth))) + 1;

}

function displayTable($transactionDate, $dueDate, $period){
    $records = getRecords($transactionDate, $dueDate);
    $days = [[]]; 
    $weeks = [[]];
    $months = [[]];

    if ($period == "week"){
        for ($r = 0; $r < 7; $r++)
            for ($c = 0; $c < 4; $c++)
                $days[$r][$c] = 0;
    }
    else if ($period == "month"){
        for ($r = 0; $r < 31; $r++)
            for ($c = 0; $c < 4; $c++)
                $days[$r][$c] = 0;
        for ($r = 0; $r < 5; $r++)
            $weeks[$r] = [];
    }
    else if ($period == "year"){
        for ($r = 0; $r < 366; $r++)
            for ($c = 0; $c < 4; $c++)
                $days[$r][$c] = 0;
        for ($r = 0; $r < 12; $r++)
            $months[$r] = [];
    }
    
    while ($row = mysqli_fetch_assoc($records[0])){
        if ($period == "week") $d = date("w", strtotime($row["date"]));
        else if ($period == "month") $d = date("j", strtotime($row["date"]));
        else if ($period == "year") $d = date("z", strtotime($row["date"]));
        $days[$d][0] = strtotime($row["date"]);    
        $days[$d][1] += $row["subtotal"];  
    }
    while ($row = mysqli_fetch_assoc($records[1])){
        if ($period == "week") $d = date("w", strtotime($row["date"]));
        else if ($period == "month") $d = date("j", strtotime($row["date"]));
        else if ($period == "year") $d = date("z", strtotime($row["date"]));
        $days[$d][0] = strtotime($row["date"]);    
        $days[$d][2] += $row["amount_paid"];    
    }
    while ($row = mysqli_fetch_assoc($records[2])){
        if ($period == "week") $d = date("w", strtotime($row["due_date"]));
        else if ($period == "month") $d = date("j", strtotime($row["due_date"]));
        else if ($period == "year") $d = date("z", strtotime($row["due_date"]));
        $days[$d][0] = strtotime($row["due_date"]);    
        $days[$d][3] += $row["subtotal"];    
    }

    if ($period == "year"){
        for ($i = 0; $i < 366; $i++){
            if ($days[$i][0]) {
                if ($days[$i][1]){
                    $month = date("n", $days[$i][0]);
                    $months[$month][]= ["credit", $days[$i][0], $days[$i][1]];
                }
                if ($days[$i][2]){
                    $month = date("n", $days[$i][0]);
                    $months[$month][]= ["payment", $days[$i][0], $days[$i][2]];
                }
                if ($days[$i][3]){
                    $month = date("n", $days[$i][0]);
                    $months[$month][]= ["due", $days[$i][0], $days[$i][3]];
                }
            }
        }

        foreach($months as $index => $m){
            if (count($m)){ // if day has transactions ?>
                <div class="period-header" onclick="toggleContent(this)">
                    <p><?php echo DateTime::createFromFormat("!m", $index)->format("F"); ?></p>
                </div>
                <div class="weekday-content">
                    <table><?php
                        foreach($m as $d){ ?>
                        <tr class="weekday-transaction"><?php
                        if ($d[0] == "credit"){?>
                            <td class="credit-transaction-type"><p>C</p></td>
                            <td class="date-column"><?php echo date("M j", $d[1]); ?></td>
                            <td class="amount-column">₱ <?php if (fmod($d[2],1)) echo number_format($d[2],2); else echo number_format($d[2]); ?></td><?php
                        }
                        else if ($d[0] == "payment"){?>
                            <td class="payment-transaction-type"><p>P</p></td>
                            <td class="date-column"><?php echo date("M j", $d[1]); ?></td>
                            <td class="amount-column">₱ <?php if (fmod($d[2],1)) echo number_format($d[2],2); else echo number_format($d[2]); ?></td><?php
                        }
                        else if ($d[0] == "due"){?>
                            <td class="due-transaction-type"><p>D</p></td>
                            <td class="date-column"><?php echo date("M j", $d[1]); ?></td>
                            <td class="amount-column">₱ <?php if (fmod($d[2],1)) echo number_format($d[2],2); else echo number_format($d[2]); ?></td><?php
                        }?>
                        </tr><?php
                        }?>
                    </table>
                </div><?php
            }
        }
    }

    else if ($period == "month"){
        for ($i = 0; $i < 31; $i++){
            if ($days[$i][0]) {
                if ($days[$i][1]){
                    $week = weekOfMonth($days[$i][0]);
                    $weeks[$week][] = ["credit", $days[$i][0], $days[$i][1]];
                }
                if ($days[$i][2]){
                    $week = weekOfMonth($days[$i][0]);
                    $weeks[$week][] = ["payment", $days[$i][0], $days[$i][2]];
                }
                if ($days[$i][3]){
                    $week = weekOfMonth($days[$i][0]);
                    $weeks[$week][] = ["due", $days[$i][0], $days[$i][3]];
                }
            }
        }

        foreach($weeks as $index => $w){
            if (count($w)){ // if day has transactions ?>
                <div class="period-header" onclick="toggleContent(this)">
                    <p><?php echo "WEEK " . $index; ?></p>
                </div>
                <div class="weekday-content">
                    <table><?php
                        foreach($w as $d){ ?>
                        <tr class="weekday-transaction"><?php
                        if ($d[0] == "credit"){?>
                            <td class="credit-transaction-type"><p>C</p></td>
                            <td class="date-column"><?php echo date("M j", $d[1]); ?></td>
                            <td class="amount-column">₱ <?php if (fmod($d[2],1)) echo number_format($d[2],2); else echo number_format($d[2]); ?></td><?php
                        }
                        else if ($d[0] == "payment"){?>
                            <td class="payment-transaction-type"><p>P</p></td>
                            <td class="date-column"><?php echo date("M j", $d[1]); ?></td>
                            <td class="amount-column">₱ <?php if (fmod($d[2],1)) echo number_format($d[2],2); else echo number_format($d[2]); ?></td><?php
                        }
                        else if ($d[0] == "due"){?>
                            <td class="due-transaction-type"><p>D</p></td>
                            <td class="date-column"><?php echo date("M j", $d[1]); ?></td>
                            <td class="amount-column">₱ <?php if (fmod($d[2],1)) echo number_format($d[2],2); else echo number_format($d[2]); ?></td><?php
                        }?>
                        </tr><?php
                        }?>
                    </table>
                </div><?php
            }
        }
    }

    else if ($period == "week"){
        foreach($days as $d){
            if ($d[0]){ // if day has transactions ?>
                <div class="period-header" onclick="toggleContent(this)">
                    <p><?php echo strtoupper(date("l", $d[0])); ?></p>
                </div>
                <div class="weekday-content">
                    <table>
                        <tr class="weekday-transaction"><?php
                        if ($d[1]){?>
                            <td class="credit-transaction-type"><p>C</p></td>
                            <td class="date-column"><?php echo date("M j", $d[0]); ?></td>
                            <td class="amount-column">₱ <?php if (fmod($d[1],1)) echo number_format($d[1],2); else echo number_format($d[1]); ?></td><?php
                        }?>
                        </tr>
                        <tr class="weekday-transaction"><?php
                        if ($d[2]){?>
                            <td class="payment-transaction-type"><p>P</p></td>
                            <td class="date-column"><?php echo date("M j", $d[0]); ?></td>
                            <td class="amount-column">₱ <?php if (fmod($d[2],1)) echo number_format($d[2],2); else echo number_format($d[2]); ?></td><?php
                        }?>
                        </tr>
                        <tr class="weekday-transaction"><?php
                        if ($d[3]){?>
                            <td class="due-transaction-type"><p>D</p></td>
                            <td class="date-column"><?php echo date("M j", $d[0]); ?></td>
                            <td class="amount-column">₱ <?php if (fmod($d[3],1)) echo number_format($d[3],2); else echo number_format($d[3]); ?></td><?php
                        }?>
                        </tr>
                    </table>
                </div><?php
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