<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Credit Pad</title>
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
      <p>Log in</p>
    </div>
    <div id="login-form-div" class="container">
    <span class="div-about-header">Log in</span>
      <form id="login-form" action="validate-login.php" method="post"> <!-- method=post for sensitive information we don't want displayed in the URL -->
        <label for="login-as-select" class="field-name">account</label>  
        <select id="login-as-select" name="account-type" class="field" onchange="prepareForm()">
          <option value="store owner" <?php if (isset($_SESSION['account-type']) and $_SESSION['account-type'] == 'store owner') { echo 'selected';} ?>>store owner</option>
          <option value="customer" <?php if (isset($_SESSION['account-type']) and $_SESSION['account-type'] == 'customer') { echo 'selected';} ?>>customer</option>
          <option value="administrator" <?php if (isset($_SESSION['account-type']) and $_SESSION['account-type'] == 'administrator') { echo 'selected';} ?>>administrator</option>
        </select>
        <label for="uname" class="field-name">name</label>
        <input id="uname" type="text" class="field" name="username" value="<?php if (
            isset($_SESSION['username'])
        ) {
            echo $_SESSION['username'];
        } ?>" onkeyup="listStores()" required/>
        <label for="bname" class="field-name">store</label>
        <select id="bname" name="business_name" class="field">

        </select>
        <label for="pass" class="field-name">password</label>
        <input id="pass" type="password" class="field" name="password" required/>
      </form>
      <div class="login-signup-buttons">
        <a href="signup.php"><p class="switch-form-link">Sign up</p></a>
        <button type="submit" form="login-form" class="button" id="login-button">Log in</button>
      </div>
    </div>
    <script type="text/javascript" src="jquery.js"></script>
    <script>
      function closeError(){$("#error").addClass("hidden-item");} 

      function listStores(){
        let accountType = $("#login-as-select option:selected").text();
        let username = $("#uname").val();
        $.ajax({
          url: "list-stores.php",
          type: "POST",
          data: {accountType: accountType, username: username},
          success: function (data){
            $("#bname").html(data);
          }
        });
      }

      function prepareForm(){
        let loginAs = $("#login-as-select option:selected").val();
        if (loginAs == "customer" || loginAs == "store owner"){
          $("#uname").prop("required", true);
          $("#bname").prop("required", true);
          $("#uname").show();
          $("#bname").show();
          $("label:contains('name')").show();
          $("label:contains('store')").show();
          listStores();
        }
        else if (loginAs == "administrator"){
          $("#uname").prop("required", false);
          $("#bname").prop("required", false);
          $("#uname").hide();
          $("#bname").hide();
          $("label:contains('name')").hide();
          $("label:contains('store')").hide();
        }
      }

      $(document).ready(function(){
        prepareForm();
        listStores();
      });
    </script>
  </body>
</html>
