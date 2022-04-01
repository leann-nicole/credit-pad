<?php
session_start();
include "connection.php";

$query = "SELECT * FROM stores ORDER BY date_approved DESC";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) == 0){
    ?>
<div id="no-applications-banner" class="container">
    <div>THERE ARE NO APPLICATIONS AT THE MOMENT.</div>
</div>
    <?php
}
else {
    while($row = mysqli_fetch_assoc($result)){
        $query1 = "SELECT * FROM store_operators WHERE username = '{$row['store_operator']}'";
        $result1 = mysqli_query($con, $query1);
        $row1 = mysqli_fetch_assoc($result1);
        ?>
    <div class="container store-item">
        <div class="store-approval-date"><?php if ($row['date_approved'] != NULL) echo date_format(date_create($row['date_approved']), "F d, Y"); ?></div>
        <p class="store-name"><?php echo $row['business_name']; ?></p>
        <p class="store-location"><?php echo $row['business_addr']; ?></p>
        <div class="store-details">
            <table>
                <tr>
                    <td>store operator</td>
                    <td><?php echo $row1['username']; ?></td>
                </tr>
                <tr>
                    <td>sex</td>
                    <td><?php echo ($row1['sex'] == "m")? "male" : "female"; ?></td>
                </tr>
                <tr>
                    <td>birthday</td>
                    <td><?php echo date_format(date_create($row1['birthdate']), "F d, Y"); ?></td>
                </tr>
                <tr>
                    <td>mobile number</td>
                    <td><?php echo $row1['mobile_no']; ?></td>
                </tr>
                <tr>
                    <td>email address</td>
                    <td><?php echo $row1['email']; ?></td>
                </tr>
            </table>
        </div>
    </div>
        <?php
    }
}