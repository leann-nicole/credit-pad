<?php
session_start(); // if you want to use the $_SESSION superglobal, for accessing data across pages
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Credit Pad</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
  </head>
  <body id="flex-body">
    <p id="error" class="<?php if (!isset($_GET['error'])) {
          echo 'hidden-item';
      }?>" onclick="closeError()">
        <?php if (isset($_GET['error'])) {
            echo $_GET['error'];
        }?>
    </p>       
    <div id="website-name">
      <p>Credit Pad</p>
      <p>Sign up</p>
    </div>
    <!--SELECT ACCOUNT TYPE-->
    <div class="container" id="account-type-div">
        <span class="div-about-header">Select account type</span>
        <input id="store-owner-radio" name="account-type" type="radio" <?php if(isset($_SESSION['account-type-signup']) && $_SESSION['account-type-signup'] == "store owner") echo "checked"; ?>>
        <label id="store-owner-label" for="store-owner-radio"><span class="material-icons">storefront</span>store owner</label>
        <input id="customer-radio" name="account-type" type="radio" <?php if(isset($_SESSION['account-type-signup']) && $_SESSION['account-type-signup'] == "customer") echo "checked"; ?>>
        <label id="customer-label" for="customer-radio"><span class="material-icons">person</span> customer</label>
        <div class="login-signup-buttons">
            <a href="login.php"><p class="switch-form-link">Log in</p></a>
            <button type="button" class="button next-button" onclick="startSignUp()">Next</button>
        </div>
    </div>
    <!--CUSTOMER SIGNUP FORM-->
    <div type="button" id="signup-back-arrow1" class="hidden-item"><span class="material-icons button" onclick="selectAccount()">arrow_back</span></div>
    <div class="container hidden-item" id="customer-signup-form-div">
        <span class="div-about-header">Sign up as customer</span>
        <form id="customer-signup-form" action="validate-signup-customer.php" method="post"> <!-- method=post for sensitive information we don't want displayed in the URL -->
            <label for="customer-su-name" class="field-name">name</label>
            <input id="customer-su-name" type="text" class="field" name="customer-su-name" value="<?php if (
                isset($_SESSION['customer-su-name'])
            ) {
                echo $_SESSION['customer-su-name'];
            } ?>" required/>
            <label for="customer-su-store" class="field-name">store</label>
            <input id="bname" type="text" class="field" name="customer-su-store" value="<?php if (
                isset($_SESSION['customer-su-store'])
            ) {
                echo $_SESSION['customer-su-store'];
            } ?>" required/>        
            <label for="customer-su-password" class="field-name">password</label>
            <input id="customer-su-password" type="password" class="field" name="customer-su-password" required/>
        </form>       
        <div class="login-signup-buttons">
            <a href="login.php"><p class="switch-form-link">Log in</p></a>
            <button type="submit" form="customer-signup-form" class="button signup-button">Sign up</button>
      </div>
    </div>
    <!--CHECK BUSINESS INFORMATION-->
    <div class="container hidden-item" id="business-info-div">
        <span class="div-about-header">Sign up as store owner</span>
        <label for="store-su-name" class="field-name">store</label>
        <input type="text" id="store-su-name" class="field" name="store-su-name" value="<?php if(isset($_SESSION['store-su-name'])) echo $_SESSION['store-su-name']; ?>" required>
        <label for="store-su-location" class="field-name">location</label>
        <input type="text" id="store-su-location" class="field" name="store-su-location" value="<?php if(isset($_SESSION['store-su-location'])) echo $_SESSION['store-su-location']; ?>" required>
        <div class="login-signup-buttons">
            <a href="login.php"><p class="switch-form-link">Log in</p></a>
            <button type="button" class="button next-button" onclick="isStoreUnique()">Next</button>
        </div>
    </div>
    <!--STORE OWNER SIGNUP FORM-->
    <div type="button" id="signup-back-arrow2" class="hidden-item"><span class="material-icons button" onclick="startSignUp()">arrow_back</span></div>
    <div class="container hidden-item" id="store-owner-signup-form-div">
        <span class="div-about-header">Sign up as store owner</span>
        <form id="store-owner-signup-form" action="validate-signup-store.php" method="post">
            <label for="so-su-name" class="field-name">name</label>
            <input type="text" name="so-su-name" id="so-su-name" class="field" value="<?php if(isset($_SESSION['so-su-name'])) echo $_SESSION['so-su-name']; ?>" required>
            <label for="so-su-sex" class="field-name">sex</label>
            <select name="so-su-sex" id="so-su-sex" class="field" required>
                <option value="m" <?php if (isset($_SESSION['so-su-sex']) && $_SESSION['so-su-sex'] == "m") echo "selected"; ?> >male</option>
                <option value="f" <?php if (isset($_SESSION['so-su-sex']) && $_SESSION['so-su-sex'] == "f") echo "selected"; ?> >female</option>
            </select>
            <label for="so-su-birthday" class="field-name">birthday</label>
            <input type="date" name="so-su-birthday" id="so-su-birthday" class="field" value="<?php if(isset($_SESSION['so-su-birthday'])) echo $_SESSION['so-su-birthday']; ?>" required>
            <label for="so-su-mobile" class="field-name">mobile number</label>
            <input type="text" name="so-su-mobile" maxlength="11" id="so-su-mobile" class="field" value="<?php if(isset($_SESSION['so-su-mobile'])) echo $_SESSION['so-su-mobile']; ?>" required>
            <label for="so-su-email" class="field-name">email address</label>
            <input type="email" name="so-su-email" id="so-su-email" class="field" value="<?php if(isset($_SESSION['so-su-email'])) echo $_SESSION['so-su-email']; ?>" required>
            <label for="so-su-password" class="field-name">password</label>
            <input type="password" name="so-su-password" id="so-su-password" class="field" value="<?php if(isset($_SESSION['so-su-password'])) echo $_SESSION['so-su-password']; ?>" required>
        </form>
        <div class="login-signup-buttons">
            <a href="login.php"><p class="switch-form-link">Log in</p></a>
            <button type="submit" form="store-owner-signup-form" class="button signup-button">Sign up</button>
        </div>
    </div>
    <script type="text/javascript" src="jquery.js"></script>
    <script>
        function closeError(){$("#error").addClass("hidden-item");} 

        function selectAccount(){
            window.location.href="signup.php";
        }

        function startSignUp(){
            let accountType = $("#account-type-div input:checked + label span").text();
            if (accountType == "" && !(window.location.href.indexOf("account=store") > -1) && !(window.location.href.indexOf("account=customer") > -1)) return;
            $("#account-type-div").addClass("hidden-item");
            $("#signup-back-arrow2").addClass("hidden-item");
            $("#signup-back-arrow1").removeClass("hidden-item");
            $("#store-owner-signup-form-div").addClass("hidden-item");
            if (accountType == "storefront" || window.location.href.indexOf("account=store") > -1){
                $.ajax({
                    url: "validate-signup-customer.php",
                    type: "POST",
                    data: {accountType: "store owner"}
                });
                $("#business-info-div").removeClass("hidden-item");
            }
            else if (accountType == "person" || window.location.href.indexOf("account=customer") > -1){
                $.ajax({
                    url: "validate-signup-customer.php",
                    type: "POST",
                    data: {accountType: "customer"}
                });
                $("#customer-signup-form-div").removeClass("hidden-item");
            } 
        }

        function isStoreUnique(){
            $("#account-type-div").addClass("hidden-item");
            let storeName = $("#store-su-name").val();
            let storeLocation = $("#store-su-location").val();
            if (storeName == "" || storeLocation == "") return;
            $.ajax({
                url: "validate-signup-store.php",
                type: "POST",
                data: {storeName: storeName, storeLocation: storeLocation},
                success: function(result){
                    if (result == "yes"){
                        $("#business-info-div").addClass("hidden-item");
                        $("#signup-back-arrow1").addClass("hidden-item");
                        $("#store-owner-signup-form-div").removeClass("hidden-item");
                        $("#signup-back-arrow2").removeClass("hidden-item");
                    }
                    else {
                        $("#error").removeClass("hidden-item");
                        $("#error").text("store is already registered");
                    }
                }
            });
        }

        $(document).ready(function (){
            if (window.location.href.indexOf("account=customer") > -1) startSignUp();
            else if (window.location.href.indexOf("account=store") > -1) isStoreUnique();
        });
    </script>
  </body>
</html>
