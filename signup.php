<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Listahan</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <div id="login-signup-form">
      <p id="sitename">LISTAHAN</p>

      <?php
        if (isset($_GET['error'])){ ?>
        <p id="error">
          <?php 
          echo $_GET['error'];
          ?>
        </p>
      <?php } ?>
      
      <form action="validate_signup.php" autocomplete="off" method="post" id="signupform">
        <p class="field-name">name</p>
        <input type="text" class="field" name="username" maxlength="50" value="<?php if(isset($_SESSION['username'])){echo $_SESSION['username'];}?>"/>
        <p class="field-name">birthdate</p>
        <input type="date" class="field" name="birthdate" value="<?php if(isset($_SESSION['birthdate'])){echo $_SESSION['birthdate'];}?>"/>
        <p class="field-name">sex</p>
        <select name="sex" class="field">
          <option value="m" <?php if(isset($_SESSION['sex']) and $_SESSION['sex'] == 'm'){ echo "selected"; } ?>>male</option>
          <option value="f" <?php if(isset($_SESSION['sex']) and $_SESSION['sex'] == 'f'){ echo "selected"; } ?>>female</option>
        </select>
        <p class="field-name">mobile number</p>
        <input type="text" class="field" name="mobile_no" maxlength="11" value="<?php if(isset($_SESSION['mobile_no'])){echo $_SESSION['mobile_no'];}?>"/>
        <p class="field-name">email address</p>
        <input type="text" class="field" name="email" maxlength="100" value="<?php if(isset($_SESSION['email'])){echo $_SESSION['email'];}?>"/>
        <p class="field-name">business name</p>
        <input type="text" class="field" name="business_name" maxlength="50" value="<?php if(isset($_SESSION['business_name'])){echo $_SESSION['business_name'];}?>"/>
        <p class="field-name">business address</p>
        <input type="text" class="field" name="business_addr" maxlength="100" value="<?php if(isset($_SESSION['business_addr'])){echo $_SESSION['business_addr'];}?>"/>
        <p class="field-name">password</p>
        <input type="password" class="field" name="password" maxlength="15" value="<?php if(isset($_SESSION['password'])){echo $_SESSION['password'];}?>"/>
        <button type="submit" class="button" id="submit-form-button">SIGN UP</button>
        <a href="login.php"><p id="switch-form-link">LOG IN</p></a>
      </form>
    </div>
  </body>
</html>
