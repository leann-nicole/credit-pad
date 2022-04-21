<?php
session_start();
include "connection.php";

$store = mysqli_real_escape_string($con, $_SESSION["business_name"]);
$customer = mysqli_real_escape_string($con, $_POST["customer"]);
$period = $_POST["period"];

function printStats($transactionDate, $dueDate){
    global $store, $customer, $con;
    // get credit transactions occuring between current week
    $query1 = "SELECT * FROM credit_transactions WHERE business_name = '$store' AND customer = '$customer' AND " . $transactionDate;
    $result1 = mysqli_query($con, $query1);
    $totalCredit = 0;

    // get credit transactions occuring between current week
    $query2 = "SELECT amount_paid FROM payment_transactions WHERE business_name = '$store' AND customer = '$customer' AND " . $transactionDate;
    $result2 = mysqli_query($con, $query2);
    $totalPayment = 0;

    // get credit transactions occuring between current week
    $query3 = "SELECT * FROM credit_transactions WHERE business_name = '$store' AND customer = '$customer' AND (status = 'unpaid' OR status IS NULL) AND " . $dueDate;
    $result3 = mysqli_query($con, $query3);
    $totalDue = 0;
?>
<div id="stats">
<?php
    while ($row = mysqli_fetch_assoc($result1)) $totalCredit += $row["subtotal"];
    ?>
    <div id="period-totals">
        <p>Total Credit</p>
        <br>
        <p>₱ <?php if (fmod($totalCredit, 1)) echo number_format($totalCredit, 2); else echo number_format($totalCredit); ?><p>
    </div>
    <?php
    while ($row = mysqli_fetch_assoc($result2)) $totalPayment += $row["amount_paid"];
    ?>
    <div id="period-totaLs">
        <p>Total Payment</p>
        <br>
        <p>₱ <?php if (fmod($totalPayment, 1)) echo number_format($totalPayment, 2); else echo number_format($totalPayment); ?><p>
    </div>
    <?php
    while ($row = mysqli_fetch_assoc($result3)) $totalDue += $row["subtotal"];
    ?>
    <div id="period-totals">
        <p>Total Due</p>
        <br>
        <p>₱ <?php if (fmod($totalDue, 1)) echo number_format($totalDue, 2); else echo number_format($totalDue); ?><p>
    </div>
</div>
    <?php
}

if ($period == "week"){
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
    printStats($transactionDate, $dueDate);
}
else if ($period == "month"){
    $month = date("m");
    $year = date("Y");
    $transactionDate = "MONTH(date) = '$month' AND YEAR(date) = '$year'";
    $dueDate = "MONTH(due_date) = '$month' AND YEAR(due_date) = '$year'";
    printStats($transactionDate, $dueDate);
}
else if ($period == "year"){
    $year = date("Y");
    $transactionDate = "YEAR(date) = '$year'";
    $dueDate = "YEAR(due_date) = '$year'";
    printStats($transactionDate, $dueDate);
}