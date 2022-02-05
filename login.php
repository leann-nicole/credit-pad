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
    <div id="login-signup-form">
      <p id="sitename">CREDIT PAD</p>
      <form action="validate-login.php" autocomplete="off" method="post"> <!-- method=post for sensitive information we don't want displayed in the URL -->
        <p class="field-name">name</p>
        <input type="text" class="field" name="username" value="<?php if (
            isset($_SESSION['username'])
        ) {
            echo $_SESSION['username'];
        } ?>"/>
        <p class="field-name">password</p>
        <input type="password" class="field" name="password" value="<?php if (
            isset($_SESSION['password'])
        ) {
            echo $_SESSION['password'];
        } ?>"/>
        <button type="submit" class="button" id="submit-form-button">LOG IN</button> <!-- if button is outside of form tags, we can add the form attribute and give it the id of the form -->
        <a href="signup.php"><p id="switch-form-link">SIGN UP</p></a>
      </form>
    </div>
  </body>
</html>