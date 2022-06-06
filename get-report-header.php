<?php 
session_start();
?>

<p id="report-header-sitename">Credit Pad</p>
<p id="business-name"><?php echo $_SESSION["business_name"]; ?></p><?php
if ($_SESSION['customerLoggedIn']){
    ?>
<p><?php echo $_SESSION["store_operator"]; ?></p>
    <?php
}
else if ($_SESSION['ownerLoggedIn']){
    ?>
<p><?php echo $_SESSION["username"]; ?></p>
    <?php
}?>
<p><?php echo $_SESSION["business_location"]; ?></p>
<?php if (isset($_POST["period"])) { ?>
<p id="page-title">
    <?php 
    $period = $_POST["period"];
    echo strtoupper($period[0]);
    echo substr($period,1) . "ly Report";
    ?>
</p>
<?php if (isset($_POST["customer"])) { echo "<p id='customer-printed'>" . $_POST["customer"] . "</p>";} ?>
<p id="report-date"><?php echo "Printed: " . date("F j, Y"); ?></p>
<?php }
else {?>
<p id="page-title">History</p>
<?php if (isset($_POST["customer"])) { echo "<p id='customer-printed'>" . $_POST["customer"] . "</p>";} ?>
<p id="report-date">
    <?php 
    if (!empty($_POST["startDate"]) && !empty($_POST["endDate"]))
      echo "from " . date("F j, Y", strtotime($_POST["startDate"])) . " until " . date("F j, Y", strtotime($_POST["endDate"])); 
    else if (!empty($_POST["endDate"]))
        echo "until " . date("F j, Y", strtotime($_POST["endDate"]));
    else if (!empty($_POST["startDate"]))
        echo "from " . date("F j, Y", strtotime($_POST["startDate"]));
    else echo "All time";
    ?>
</p>
<p id="history-type">
    <?php
    if (!empty($_POST["historyType"])){
        if ($_POST["historyType"] == "PaymentCredit") echo "Credit and payment transactions";
        else if ($_POST["historyType"] == "Payment") echo "Payment transactions only";
        else echo "Credit transactions only";
    }
    ?>
</p>
<p id="report-date"><?php echo "Printed: " . date("F j, Y"); ?></p>
<?php }
