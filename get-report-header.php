<?php 
session_start();
?>

<p id="report-header-sitename">Credit Pad</p>
<p><?php echo $_SESSION["business_name"]; ?></p>
<p><?php echo $_SESSION["username"]; ?></p>
<p><?php echo $_SESSION["business_location"]; ?></p>
<?php if (isset($_POST["period"])) { ?>
<p id="page-title">
    <?php 
    $period = $_POST["period"];
    echo strtoupper($period[0]);
    echo substr($period,1) . "ly Report";
    ?>
</p>
<p id="report-date"><?php echo "Printed: " . date("F j, Y"); ?></p>
<?php }
else {?>
<p id="page-title">History</p>
<p id="report-date">
    <?php 
    if (!empty($_POST["startDate"]) && !empty($_POST["endDate"]))
      echo date("F j, Y", strtotime($_POST["startDate"])) . " - " . date("F j, Y", strtotime($_POST["endDate"])); 
    else if (!empty($_POST["endDate"]))
        echo date("F j, Y", strtotime($_POST["endDate"]));
    else if (!empty($_POST["startDate"]))
        echo date("F j, Y", strtotime($_POST["startDate"]));
    else echo "All time";
    ?>
</p>
<p id="history-type">
    <?php
    if (!empty($_POST["historyType"])){
        if ($_POST["historyType"] != "PaymentCredit") echo "Credit and payment transactions";
        else if ($_POST["historyType"] == "Payment") echo "Payment transactions only";
        else echo "Credit transactions only";
    }
    ?>
</p>
<p id="report-date"><?php echo "Printed: " . date("F j, Y"); ?></p>
<?php }
