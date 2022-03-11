<?php
session_start(); // if you want to use the $_SESSION superglobal, for accessing data across pages
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
    <?php if (isset($_GET['error'])) { ?>
    <p id="error">
      <?php echo $_GET['error']; ?>
    </p>
    <?php } ?>
    <div id="login-signup-form-div" class="container">
      <p id="sitename">CREDIT PAD</p>      
      <form id="signup-form" action="validate-signup.php" autocomplete="off" method="post">

        <div class="form-column">
            <label for="uname" class="field-name">name</label>
            <input id="uname" type="text" class="field" name="username" maxlength="50" value="<?php if (
                isset($_SESSION['username'])
            ) {
                echo $_SESSION['username'];
            } ?>"/>
            <label for="bday" class="field-name">birthdate</label>
            <input id="bday" type="date" class="field" name="birthdate" value="<?php if (
                isset($_SESSION['birthdate'])
            ) {
                echo $_SESSION['birthdate'];
            } ?>"/>
            <label for="gender" class="field-name">sex</label>
            <select id="gender" name="sex" class="field">
            <option value="m" <?php if (
                isset($_SESSION['sex']) and
                $_SESSION['sex'] == 'm'
            ) {
                echo 'selected';
            } ?>>male</option>
            <option value="f" <?php if (
                isset($_SESSION['sex']) and
                $_SESSION['sex'] == 'f'
            ) {
                echo 'selected';
            } ?>>female</option>
            </select>
            <label for="phone" class="field-name">mobile number</label>
            <input id="phone" type="text" class="field" name="mobile_no" maxlength="11" value="<?php if (
                isset($_SESSION['mobile_no'])
            ) {
                echo $_SESSION['mobile_no'];
            } ?>"/>
        </div>

        <div class="form-column">
            <label for="mail" class="field-name">email address</label>
            <input id="mail" type="text" class="field" name="email" maxlength="100" value="<?php if (
                isset($_SESSION['email'])
            ) {
                echo $_SESSION['email'];
            } ?>"/>
            <label for="business" class="field-name">business name</label>
            <input id="business" type="text" class="field" name="business_name" maxlength="50" value="<?php if (
                isset($_SESSION['business_name'])
            ) {
                echo $_SESSION['business_name'];
            } ?>"/>
            <label for="baddress" class="field-name">business address</label>
            <input id="baddress" type="text" class="field" name="business_addr" maxlength="100" value="<?php if (
                isset($_SESSION['business_addr'])
            ) {
                echo $_SESSION['business_addr'];
            } ?>"/>
            <label for="pass" class="field-name">password</label>
            <input id="pass" type="password" class="field" name="password" maxlength="15" value="<?php if (
                isset($_SESSION['password'])
            ) {
                echo $_SESSION['password'];
            } ?>"/>
        </div>
        
      </form>

      <div id="login-signup-buttons">
        <a href="login.php"><p id="switch-form-link">LOG IN</p></a>
        <button type="submit" form="signup-form" class="button" id="submit-form-button">SIGN UP</button>
      </div>

    </div>
  </body>
</html>
