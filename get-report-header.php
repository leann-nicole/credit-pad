<?php 
session_start();
?>

<p id="report-header-sitename">Credit Pad</p>
<p><?php echo $_SESSION["business_name"]; ?></p>
<p><?php echo $_SESSION["username"]; ?></p>
<p><?php echo $_SESSION["business_location"]; ?></p>
<p id="report-period">
    <?php 
    $period = $_POST["period"];
    echo strtoupper($period[0]);
    echo substr($period,1) . "ly Report";
    ?>
</p>
<p id="report-date"><?php echo date("F j, Y"); ?></p>
