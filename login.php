<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
      <form id="login-form" action="validate-login.php" autocomplete="off" method="post"> <!-- method=post for sensitive information we don't want displayed in the URL -->
        <label for="uname" class="field-name">name</label>
        <input id="uname" type="text" class="field" name="username" value="<?php if (
            isset($_SESSION['username'])
        ) {
            echo $_SESSION['username'];
        } ?>"/>
        <label for="pass" class="field-name">password</label>
        <input id="pass" type="password" class="field" name="password" value="<?php if (
            isset($_SESSION['password'])
        ) {
            echo $_SESSION['password'];
        } ?>"/>
      </form>
      <div id="login-signup-buttons">
        <a href="signup.php"><p id="switch-form-link">SIGN UP</p></a>
        <button type="submit" form="login-form" class="button" id="submit-form-button">LOG IN</button>
      </div>
    </div>
  </body>
</html>
