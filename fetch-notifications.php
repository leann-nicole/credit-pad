<?php
session_start();
include "connection.php";

date_default_timezone_set('Asia/Manila');

$store = mysqli_real_escape_string($con, $_POST["store"]);
$customer = mysqli_real_escape_string($con, $_POST["customer"]);
$date = date('Y-m-d');

$query = "SELECT grand_total, date, due_date FROM credit_transactions WHERE business_name = '$store' AND customer = '$customer' AND status = 'unpaid' ORDER BY due_date";
$result = mysqli_query($con, $query);

while ($row = mysqli_fetch_assoc($result)){
    if ($date >= $row["due_date"]){ 
        ?>
    <div class="notification">
        <div class="notification-content">
            <span class="material-icons">circle_notifications</span>
            <span class="notification-message">
                <div><?php echo date_format(date_create($row["due_date"]), "F d, Y");?></div>
                <br>
                <div>Your ₱<?php if(fmod($row['grand_total'], 1)) echo number_format($row['grand_total'], 2); else echo number_format($row['grand_total']); ?> credit from <?php echo date_format(date_create($row["date"]), "F d, Y"); ?> is due.</div>
            </span>
        </div>
    </div>
        <?php
    }
}
