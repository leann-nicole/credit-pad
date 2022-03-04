<?php
session_start();
include "connection.php";

// fetch customer information
$customer = $_POST["name"];
$query = "SELECT * FROM customers WHERE name = '$customer'";
$result = mysqli_query($con, $query);
$rows = mysqli_fetch_assoc($result);

// store into individually named variables
$birthday = $rows["birthdate"];
$sex = $rows["sex"] == "m" ? "male" : "female";
$number = $rows["mobile_no"];
$email = $rows["email"];
$address = $rows["address"];
$credit = $rows["current_debt"];
$rating = $rows["rating"];

?>



<button type="button" id="edit-button" class="material-icons button" title="edit">edit</button>
<div id="customer-img-rate-div">
    <div id="customer-image"></div>
    <div id="customer-rating" class="star"><?php while($rating){echo "&#128970;"; $rating--;}?></div>
</div>
<div id="customer-details">
    <div id="customer-header"><span id="customer-name"><?php echo $customer;?></span><span id="customer-credit"><?php if (fmod($credit, 1)){ echo number_format($credit, 2); } else { echo number_format($credit); } ?></span></div>

    <table>
        <tr>
            <td><p><?php echo "gender"?></p></td>
            <td><p><?php echo $sex;?></p></td>
        </tr>
        <tr>
            <td><p><?php echo "birthday"?></p></td>
            <td><p><?php echo date_format(date_create($birthday), "F d, Y");?></p></td>
        </tr>
        <tr>
            <td><p><?php echo "address"?></p></td>
            <td><p><?php echo $address;?></p></td>
        </tr>
        <tr>
            <td><p><?php echo "mobile number"?></p></td>
            <td><p><?php echo $number;?></p></td>
        </tr>
        <tr>
            <td><p><?php echo "email"?></p></td>
            <td><p><?php if ($email == NULL) { echo "no email address"; } else { echo $email; } ?></p></td>
        </tr>
    </table>
</div>