<?php
session_start();
include "connection.php";

if ($_POST['sortBy'] == "application_date") $query = "SELECT * FROM applicants ORDER BY application_date {$_POST['sortOrder']}, business_name ASC"; // applicants who applied on the same day are to be sorted alphabetically (a-z)
else $query = "SELECT * FROM applicants ORDER BY {$_POST['sortBy']} {$_POST['sortOrder']}";

$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) == 0){
    ?>
<div id="no-applications-banner" class="container">
    <div>No applications yet.</div>
</div>
    <?php
}
else {
    while($row = mysqli_fetch_assoc($result)){
        ?>
    <div class="container applicant-item">
        <div class="applicant-date"><?php if ($row['application_date'] != NULL) echo date_format(date_create($row['application_date']), "F d, Y"); ?></div>
        <p class="applicant-business-name"><?php echo $row['business_name']; ?></p>
        <p class="applicant-business-location"><?php echo $row['business_addr']; ?></p>
        <div class="applicant-details">
            <table>
                <tr>
                    <td>store operator</td>
                    <td class="applicant-name"><?php echo $row['store_operator']; ?></td>
                </tr>
                <tr>
                    <td>sex</td>
                    <td><?php echo ($row['sex'] == "m")? "male" : "female"; ?></td>
                </tr>
                <tr>
                    <td>birthday</td>
                    <td><?php echo date_format(date_create($row['birthdate']), "F d, Y"); ?></td>
                </tr>
                <tr>
                    <td>mobile number</td>
                    <td><?php echo $row['mobile_no']; ?></td>
                </tr>
                <tr>
                    <td>email address</td>
                    <td><?php echo $row['email']; ?></td>
                </tr>
            </table>
            <div class="applicant-buttons">
                <button type="button" class="button approve-button" onclick="approveApplicant(this)">Approve</button>
                <button type="button" class="gray-button reject-button" onclick="rejectApplicant(this)">Reject</button>
            </div>
        </div>
    </div>
        <?php
    }
}