<?php
session_start();
include "connection.php";

$store = mysqli_real_escape_string($con, $_SESSION["business_name"]);

// fetch customer information
$customer = mysqli_real_escape_string($con, $_POST["customer"]);
$query = "SELECT * FROM customers WHERE name = '$customer' AND business_name = '$store'";
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
$customer = $_POST["customer"];


?>



<button type="button" id="edit-button" class="material-icons button" onclick="toggleEditForm()" title="edit">edit</button>
<div id="customer-img-rate-div">
    <div id="customer-image"></div>
    <div id="customer-rating" class="star" data-rating="<?php echo $rating; ?>"><?php while($rating){echo "&#128970;"; $rating--;}?></div>
</div>
<div id="customer-details">
    <div id="customer-header"><span id="customer-name"><?php echo $customer;?></span><span id="customer-credit"><?php if (fmod($credit, 1)){ echo number_format($credit, 2); } else { echo number_format($credit); } ?></span></div>

    <table>
        <tr>
            <td><p><?php echo "gender"?></p></td>
            <td><p id="customer-gender"><?php echo $sex;?></p></td>
        </tr>
        <tr>
            <td><p><?php echo "birthday"?></p></td>
            <td><p id="customer-birthday"><?php echo date_format(date_create($birthday), "F d, Y");?></p></td>
        </tr>
        <tr>
            <td><p><?php echo "address"?></p></td>
            <td><p id="customer-address"><?php echo $address;?></p></td>
        </tr>
        <tr>
            <td><p><?php echo "mobile number"?></p></td>
            <td><p id="customer-mobile"><?php echo $number;?></p></td>
        </tr>
        <tr>
            <td><p><?php echo "email"?></p></td>
            <td><p id="customer-email"><?php if ($email == NULL) { echo "no email address"; } else { echo $email; } ?></p></td>
        </tr>
    </table>
</div>