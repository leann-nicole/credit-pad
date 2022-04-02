<?php
session_start();
include "connection.php";

if ($_POST['sortBy'] == "date_approved") $query = "SELECT * FROM stores ORDER BY date_approved {$_POST['sortOrder']}, business_name ASC"; // store with similar approval dates are sorted alphabetically (a-z)
else $query = "SELECT * FROM stores ORDER BY {$_POST['sortBy']} {$_POST['sortOrder']}";

$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) == 0){
    ?>
<div id="no-stores-banner" class="container">
    <div>No stores yet.</div>
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
        <div class="store-approval-date" data-date="<?php echo date("Y-m-d", strtotime($row['date_approved'])); ?>"><?php if ($row['date_approved'] != NULL) echo date_format(date_create($row['date_approved']), "F d, Y"); ?></div>
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