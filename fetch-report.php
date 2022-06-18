<?php
session_start();
include "connection.php";

$store = mysqli_real_escape_string($con, $_SESSION["business_name"]);
$customer = "";
$outstandingDebt = 0;
if (isset($_POST["customer"])) {
    $customer = mysqli_real_escape_string($con, $_POST["customer"]);
    $query = "SELECT current_debt FROM customers WHERE name = '$customer'";
    $outstandingDebt = mysqli_fetch_assoc(mysqli_query($con, $query))["current_debt"];
}$period = $_POST["period"];

function getRecords($transactionFilter, $dueFilter){
    $records = array();
    global $store, $customer, $con, $outstandingDebt;
    // get credit transactions occuring between current week
    $query = "SELECT * FROM credit_transactions WHERE business_name = '$store' AND " . $transactionFilter;
    $records[] = mysqli_query($con, $query);

    // get credit transactions occuring between current week
    $query = "SELECT * FROM payment_transactions WHERE business_name = '$store' AND " . $transactionFilter;
    $records[] = mysqli_query($con, $query);

    // get due credit transactions occuring between current week
    $query = "SELECT * FROM credit_transactions WHERE business_name = '$store' AND " . $dueFilter;
    $records[] = mysqli_query($con, $query);

    if (!isset($_POST["customer"])){
        $query = "SELECT DISTINCT customer FROM credit_transactions WHERE business_name = '$store' AND" . $dueFilter;
        $result = mysqli_query($con, $query);
        $customers = "";
        while ($row = mysqli_fetch_assoc($result))
            $customers .= "'" . mysqli_real_escape_string($con, $row['customer']) . "', ";
        $customers = substr($customers, 0, -2);

        $query = "SELECT SUM(current_debt) AS totalOutstanding FROM customers WHERE name IN (" . $customers . ")";
        $result = mysqli_query($con, $query);
        $outstandingDebt = mysqli_fetch_assoc($result)["totalOutstanding"];
    }

    return $records;
}

function displayStats($transactionFilter, $dueFilter){
    $totalCredit = 0;
    $totalPayment = 0;
    $totalDue = 0;
    $records = getRecords($transactionFilter, $dueFilter);
    global $outstandingDebt;
?>
<div id="stats">
<?php
    // print total credit
    while ($row = mysqli_fetch_assoc($records[0])) $totalCredit += $row["subtotal"];
    ?>
    <div class="period-totals">
        <span>Total Credit</span>
        <span>₱ <?php if (fmod($totalCredit, 1)) echo number_format($totalCredit, 2); else echo number_format($totalCredit); ?><span>
    </div>
    <?php
    // print total payment
    while ($row = mysqli_fetch_assoc($records[1])) $totalPayment += $row["amount_paid"];
    ?>
    <div class="period-totals">
        <span>Total Payment</span>
        <span>₱ <?php if (fmod($totalPayment, 1)) echo number_format($totalPayment, 2); else echo number_format($totalPayment); ?><span>
    </div>
    <?php
    // print total due
    while ($row = mysqli_fetch_assoc($records[2])) $totalDue += $row["subtotal"];
    ?>
    <div class="period-totals">
        <span>Total Due</span>
        <span>₱ <?php 
            if ($outstandingDebt > $totalDue) { if (fmod($totalDue, 1)) echo number_format($totalDue, 2); else echo number_format($totalDue); }
            else { if (fmod($outstandingDebt, 1)) echo number_format($outstandingDebt, 2); else echo number_format($outstandingDebt); }
        ?><span>
    </div>
</div>
    <?php
}

function weekOfMonth($date) {
    $firstDay = intval(date("W", strtotime(date("Y-m-01", $date))));
    $currentDay = intval(date("W", $date));
    if ($firstDay == $currentDay) return 1;
    return $currentDay - $firstDay;

}

function displayTable($transactionFilter, $dueFilter, $period){
    global $outstandingDebt;
    $records = getRecords($transactionFilter, $dueFilter);
    $days = [[]]; 
    $weeks = [[]];
    $months = [[]];
    $transcDetails = [[]];

    if ($period == "week"){
        for ($r = 0; $r < 7; $r++)
            for ($c = 0; $c < 4; $c++){
                $days[$r][$c] = 0;
                $transcDetails[$r][$c] = [];
            }
    }
    else if ($period == "month"){
        for ($r = 0; $r < 32; $r++)
            for ($c = 0; $c < 4; $c++){
                $days[$r][$c] = 0;
                $transcDetails[$r][$c] = [];
            }
        for ($r = 0; $r < 6; $r++)
            $weeks[$r] = [];
    }
    else if ($period == "year"){
        for ($r = 0; $r < 366; $r++)
            for ($c = 0; $c < 4; $c++){
                $days[$r][$c] = 0;
                $transcDetails[$r][$c] = [];
            }
        for ($r = 0; $r < 12; $r++)
            $months[$r] = [];
    }
    
    while ($row = mysqli_fetch_assoc($records[0])){
        if ($period == "week") $d = date("w", strtotime($row["date"]));
        else if ($period == "month") $d = date("j", strtotime($row["date"]));
        else if ($period == "year") $d = date("z", strtotime($row["date"]));
        $days[$d][0] = strtotime($row["date"]);    
        $days[$d][1] += $row["subtotal"];  

        if (array_key_exists($row["customer"], $transcDetails[$d][1])) $transcDetails[$d][1][$row["customer"]] += $row["subtotal"];
        else $transcDetails[$d][1][$row["customer"]] = $row["subtotal"];
    }

    while ($row = mysqli_fetch_assoc($records[1])){
        if ($period == "week") $d = date("w", strtotime($row["date"]));
        else if ($period == "month") $d = date("j", strtotime($row["date"]));
        else if ($period == "year") $d = date("z", strtotime($row["date"]));
        $days[$d][0] = strtotime($row["date"]);    
        $days[$d][2] += $row["amount_paid"];  
        
        if (array_key_exists($row["customer"], $transcDetails[$d][2])) $transcDetails[$d][2][$row["customer"]] += $row["amount_paid"];
        else $transcDetails[$d][2][$row["customer"]] = $row["amount_paid"];
    }
    while ($row = mysqli_fetch_assoc($records[2])){
        if ($period == "week") $d = date("w", strtotime($row["due_date"]));
        else if ($period == "month") $d = date("j", strtotime($row["due_date"]));
        else if ($period == "year") $d = date("z", strtotime($row["due_date"]));
        $days[$d][0] = strtotime($row["due_date"]);    
        $days[$d][3] += $row["subtotal"];   
        
        if (array_key_exists($row["customer"], $transcDetails[$d][3])) $transcDetails[$d][3][$row["customer"]] += $row["subtotal"];
        else $transcDetails[$d][3][$row["customer"]] = $row["subtotal"];
    }

    if ($period == "year"){
        for ($i = 0; $i < 366; $i++){
            if ($days[$i][0]) {
                $month = date("n", $days[$i][0]) - 1;
                $months[$month][] = [$days[$i][0], $days[$i][1], $days[$i][2], $days[$i][3], $transcDetails[$i][1], $transcDetails[$i][2], $transcDetails[$i][3]];
            }
        }

        foreach($months as $index => $m){
            if (count($m)){ // if day has transactions ?>
                <div class="period-header" onclick="toggleContent(this)">
                    <span><?php echo DateTime::createFromFormat("!m", $index + 1)->format("F"); ?></span>
                    <span class="material-icons expand-arrow">expand_less</span>
                </div>
                <div class="weekday-content">
                    <table><?php
                        foreach($m as $key => $d){ ?>
                        <tr class="period-transaction">
                            <td class="date-column"><?php echo date("F j", $d[0]); ?></td>
                            <td class="transaction-type credit-transaction-type"><?php if ($d[1]){?> <span>C</span>₱ <?php 
                                if (fmod($d[1],1)) echo number_format($d[1],2); else echo number_format($d[1]); 
                                if (!isset($_POST["customer"]))
                                    foreach($d[4] as $ak => $portion){
                                        $portion = (fmod($portion,1))? number_format($portion,2) : number_format($portion);
                                        echo "<p class='transaction-details'><span>" . $ak . "</span><span>" . $portion. "</span></p>";
                                    } 
                            }?></td>
                            <td class="transaction-type payment-transaction-type"><?php if ($d[2]){?> <span>P</span>₱ <?php 
                                if (fmod($d[2],1)) echo number_format($d[2],2); else echo number_format($d[2]); 
                                if (!isset($_POST["customer"]))
                                    foreach($d[5] as $ak => $portion){
                                        $portion = (fmod($portion,1))? number_format($portion,2) : number_format($portion);
                                        echo "<p class='transaction-details'><span>" . $ak . "</span><span>" . $portion. "</span></p>";
                                    } 
                            }?></td>
                            <td class="transaction-type due-transaction-type"><?php if ($d[3]){?> <span>D</span>₱ <?php
                                if ($outstandingDebt > $d[3]) { if (fmod($d[3], 1)) echo number_format($d[3], 2); else echo number_format($d[3]); }
                                else { if (fmod($outstandingDebt,1)) echo number_format($outstandingDebt,2); else echo number_format($outstandingDebt); }
                                if (!isset($_POST["customer"]))
                                    foreach($d[6] as $ak => $portion){
                                        $portion = (fmod($portion,1))? number_format($portion,2) : number_format($portion);
                                        echo "<p class='transaction-details'><span>" . $ak . "</span><span>" . $portion. "</span></p>";
                                    } 
                            }?></td>
                        </tr><?php
                        }?>
                    </table>
                </div><?php
            }
        }
    }

    else if ($period == "month"){
        for ($i = 0; $i < 32; $i++){
            if ($days[$i][0]) {
                $week = weekOfMonth($days[$i][0]);
                $weeks[$week][] = [$days[$i][0], $days[$i][1], $days[$i][2], $days[$i][3], $transcDetails[$i][1], $transcDetails[$i][2], $transcDetails[$i][3]];
            }
        }

        foreach($weeks as $index => $w){
            if (count($w)){ // if day has transactions ?>
                <div class="period-header" onclick="toggleContent(this)">
                    <span><?php echo "WEEK " . $index; ?></span>
                    <span class="material-icons expand-arrow">expand_less</span>
                </div>
                <div class="weekday-content">
                <table><?php
                        foreach($w as $key => $d){ ?>
                        <tr class="period-transaction">
                            <td class="date-column"><?php echo date("F j", $d[0]); ?></td>
                            <td class="transaction-type credit-transaction-type"><?php if ($d[1]){?> <span>C</span>₱ <?php 
                                if (fmod($d[1],1)) echo number_format($d[1],2); else echo number_format($d[1]); 
                                if (!isset($_POST["customer"]))
                                    foreach($d[4] as $ak => $portion){
                                        $portion = (fmod($portion,1))? number_format($portion,2) : number_format($portion);
                                        echo "<p class='transaction-details'><span>" . $ak . "</span><span>" . $portion. "</span></p>";
                                    } 
                            }?></td>
                            <td class="transaction-type payment-transaction-type"><?php if ($d[2]){?> <span>P</span>₱ <?php 
                                if (fmod($d[2],1)) echo number_format($d[2],2); else echo number_format($d[2]); 
                                if (!isset($_POST["customer"]))
                                    foreach($d[5] as $ak => $portion){
                                        $portion = (fmod($portion,1))? number_format($portion,2) : number_format($portion);
                                        echo "<p class='transaction-details'><span>" . $ak . "</span><span>" . $portion. "</span></p>";
                                    } 
                            }?></td>
                            <td class="transaction-type due-transaction-type"><?php if ($d[3]){?> <span>D</span>₱ <?php
                                if ($outstandingDebt > $d[3]) { if (fmod($d[3], 1)) echo number_format($d[3], 2); else echo number_format($d[3]); }
                                else { if (fmod($outstandingDebt,1)) echo number_format($outstandingDebt,2); else echo number_format($outstandingDebt); }
                                if (!isset($_POST["customer"]))
                                    foreach($d[6] as $ak => $portion){
                                        $portion = (fmod($portion,1))? number_format($portion,2) : number_format($portion);
                                        echo "<p class='transaction-details'><span>" . $ak . "</span><span>" . $portion. "</span></p>";
                                    } 
                            }?></td>
                        </tr><?php
                        }?>
                    </table>
                </div><?php
            }
        }
    }

    else if ($period == "week"){
        foreach($days as $key => $d){
            if ($d[0]){ // if day has transactions ?>
                <div class="period-header" onclick="toggleContent(this)">
                    <span><?php echo strtoupper(date("l", $d[0])); ?></span>
                    <span class="material-icons expand-arrow">expand_less</span>
                </div>
                <div class="weekday-content">
                    <table>
                        <tr class="period-transaction">
                            <td class="date-column"><?php echo date("F j", $d[0]); ?></td>
                            <td class="transaction-type credit-transaction-type"><?php if ($d[1]){?> <span>C</span>₱ <?php 
                                if (fmod($d[1],1)) echo number_format($d[1],2); else echo number_format($d[1]); 
                                if (!isset($_POST["customer"]))
                                    foreach($transcDetails[$key][1] as $ak => $portion){
                                        $portion = (fmod($portion,1))? number_format($portion,2) : number_format($portion);
                                        echo "<p class='transaction-details'><span>" . $ak . "</span><span>" . $portion. "</span></p>";
                                    } 
                            }?></td>
                            <td class="transaction-type payment-transaction-type"><?php if ($d[2]){?> <span>P</span>₱ <?php 
                                if (fmod($d[2],1)) echo number_format($d[2],2); else echo number_format($d[2]); 
                                if (!isset($_POST["customer"]))
                                    foreach($transcDetails[$key][2] as $ak => $portion){
                                        $portion = (fmod($portion,1))? number_format($portion,2) : number_format($portion);
                                        echo "<p class='transaction-details'><span>" . $ak . "</span><span>" . $portion. "</span></p>";
                                    } 
                            }?></td>
                            <td class="transaction-type due-transaction-type"><?php if ($d[3]){?> <span>D</span>₱ <?php
                                if ($outstandingDebt > $d[3]) { if (fmod($d[3], 1)) echo number_format($d[3], 2); else echo number_format($d[3]); }
                                else { if (fmod($outstandingDebt,1)) echo number_format($outstandingDebt,2); else echo number_format($outstandingDebt); }
                                if (!isset($_POST["customer"]))
                                    foreach($transcDetails[$key][3] as $ak => $portion){
                                        $portion = (fmod($portion,1))? number_format($portion,2) : number_format($portion);
                                        echo "<p class='transaction-details'><span>" . $ak . "</span><span>" . $portion. "</span></p>";
                                    } 
                            }?></td>
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

    $transactionFilter = "";
    $dueFilter = "";

    if (!empty($customer)){
        $transactionFilter = "customer = '$customer' AND date BETWEEN '$startDate' AND '$endDate'";
        $dueFilter = "customer = '$customer' AND (status = 'unpaid' OR status IS NULL) AND due_date BETWEEN '$startDate' AND '$endDate'";    
    }
    else {
        $transactionFilter = "date BETWEEN '$startDate' AND '$endDate'";
        $dueFilter = "(status = 'unpaid' OR status IS NULL) AND due_date BETWEEN '$startDate' AND '$endDate'";
    }
    displayStats($transactionFilter, $dueFilter);
    displayTable($transactionFilter, $dueFilter, $period);
}
else if ($period == "month"){
    global $period;
    $month = date("m");
    $year = date("Y");

    $transactionFilter = "";
    $dueFilter = "";

    if (!empty($customer)){
        $transactionFilter = "customer = '$customer' AND MONTH(date) = '$month' AND YEAR(date) = '$year'";
        $dueFilter = "customer = '$customer' AND (status = 'unpaid' OR status IS NULL) AND MONTH(due_date) = '$month' AND YEAR(due_date) = '$year'";    
    }
    else {
        $transactionFilter = "MONTH(date) = '$month' AND YEAR(date) = '$year'";
        $dueFilter = "(status = 'unpaid' OR status IS NULL) AND MONTH(due_date) = '$month' AND YEAR(due_date) = '$year'";    
    }
    displayStats($transactionFilter, $dueFilter);
    displayTable($transactionFilter, $dueFilter, $period);
}
else if ($period == "year"){
    global $period;
    $year = date("Y");

    $transactionFilter = "";
    $dueFilter = "";

    if (!empty($customer)){
        $transactionFilter = "customer = '$customer' AND YEAR(date) = '$year'";
        $dueFilter = "customer = '$customer' AND (status = 'unpaid' OR status IS NULL) AND YEAR(due_date) = '$year'";    
    }
    else {
        $transactionFilter = "YEAR(date) = '$year'";
        $dueFilter = "(status = 'unpaid' OR status IS NULL) AND YEAR(due_date) = '$year'";    
    }
    displayStats($transactionFilter, $dueFilter);
    displayTable($transactionFilter, $dueFilter, $period);
}