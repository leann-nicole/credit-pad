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

function weekOfMonth($date) {
    $firstDay = intval(date("W", strtotime(date("Y-m-01", $date))));
    $currentDay = intval(date("W", $date));
    if ($firstDay == $currentDay) return 1;
    return $currentDay - $firstDay;
}

function getPeriodData($transactionDate, $dueDate, $period){
    $records = getRecords($transactionDate, $dueDate);
    $days = [[]]; 
    $weeks = [[]];
    $months = [[]];

    if ($period == "week"){
        for ($r = 0; $r < 7; $r++)
            for ($c = 0; $c < 4; $c++)
                $days[$r][$c] = 0;
        for ($r = 0; $r < 7; $r++)
            for ($c = 0; $c < 3; $c++)
                $weekdays[$r][$c] = 0;
    }
    else if ($period == "month"){
        for ($r = 0; $r < 32; $r++)
            for ($c = 0; $c < 4; $c++)
                $days[$r][$c] = 0;
        for ($r = 0; $r < 6; $r++)
            for ($c = 0; $c < 3; $c++)
                $weeks[$r][$c] = 0;
    }
    else if ($period == "year"){
        for ($r = 0; $r < 366; $r++)
            for ($c = 0; $c < 4; $c++)
                $days[$r][$c] = 0;
        for ($r = 0; $r < 12; $r++)
            for ($c = 0; $c < 3; $c++)
                $months[$r][$c] = 0;
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

    if ($period == "week"){
        for ($i = 1; $i < 7; $i++){
            if ($days[$i][0]) {
                $weekdays[$i][0] += $days[$i][1];
                $weekdays[$i][1] += $days[$i][2];
                $weekdays[$i][2] += $days[$i][3];
            }
        }
        echo json_encode($weekdays);
        die();
    }
    else if ($period == "month"){
        for ($i = 1; $i < 32; $i++){
            if ($days[$i][0]) {
                $week = weekOfMonth($days[$i][0]);
                $weeks[$week][0] += $days[$i][1];
                $weeks[$week][1] += $days[$i][2];
                $weeks[$week][2] += $days[$i][3];
            }
        }
        echo json_encode($weeks);
        die();
    }
    else if ($period == "year"){
        for ($i = 0; $i < 366; $i++){
            if ($days[$i][0]) {
                $month = date("n", $days[$i][0]);
                $months[$month][0] += $days[$i][1];
                $months[$month][1] += $days[$i][2];
                $months[$month][2] += $days[$i][3];
            }
        }
        echo json_encode($months);
        die();
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
    getPeriodData($transactionDate, $dueDate, $period);
}
else if ($period == "month"){
    global $period;
    $month = date("m");
    $year = date("Y");
    $transactionDate = "MONTH(date) = '$month' AND YEAR(date) = '$year'";
    $dueDate = "MONTH(due_date) = '$month' AND YEAR(due_date) = '$year'";
    getPeriodData($transactionDate, $dueDate, $period);
}
else if ($period == "year"){
    global $period;
    $year = date("Y");
    $transactionDate = "YEAR(date) = '$year'";
    $dueDate = "YEAR(due_date) = '$year'";
    getPeriodData($transactionDate, $dueDate, $period);
}