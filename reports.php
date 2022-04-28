<?php
session_start();
if (!isset($_SESSION['ownerLoggedIn'])) {
    header('Location: login.php');
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Credit Pad</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <link rel="stylesheet" href="style.css"/>
  </head>
  <body>
    <p id="error" class="<?php if (!isset($_GET['error'])) {
          echo 'hidden-item';
      }?>">
        <?php if (isset($_GET['error'])) {
            echo $_GET['error'];
        }?>
    </p>      
    <header>
      <p id="sitename-header"><a href="customers.php">CREDIT PAD</a></p>
      <div id="dropdown">
        <button type="button" id="dropdown-button" class="material-icons" onclick="toggleAccountOptions()">storefront<span class="material-icons">arrow_drop_down</span></button>
        <div id="dropdown-menu" class="hidden-item">
          <a href="profile.php">Profile</a>
          <a href="logout.php">Log out</a>
        </div>
      </div>
    </header>
    <div id="content">
      <nav>
        <ul>
          <li><a href="customers.php">CUSTOMERS</a></li>
          <li><a href="products.php">PRODUCTS</a></li>
          <li class="selected-navbar-item"><a href="reports.php">REPORTS</a></li>
        </ul>
      </nav>
      <main>
        
      </main>
      <div id="extra">
        <div id="notes-div">
          <div id="notes-header">NOTES</div>
          <textarea id="notes" class="field" placeholder="Write your quick notes here" onkeyup="updateNotes(this)" spellcheck="false"><?php if(!empty($_SESSION["notes"])){echo $_SESSION["notes"];}?></textarea>
        </div>
      </div>
    </div>
    <footer><a href="customers.php" id="footer-website-name">Credit Pad</a></footer>
    <script type="text/javascript" src="jquery.js"></script>
    <script>
      
      function toggleAccountOptions(){
        $("#dropdown-menu").toggleClass("hidden-item");
        $("#dropdown-menu").toggleClass("container");
        let arrow = $("#dropdown-button span").text();
        (arrow == "arrow_drop_down")? $("#dropdown-button span").text("arrow_drop_up") : $("#dropdown-button span").text("arrow_drop_down");
      }

      function focusSearchBar(){
        $("#search-field").focus();
      }

      $(document).ready(function () {
        fetchNotes();
      });
      // shorthand for $(document).ready(); is $();
      // can also do $(window).on("load", function(){}); 
      // that is if you want code inside to run once entire page is ready, not just DOM


      function fetchNotes(){
        $.ajax({
          url: "update-note.php", 
          type: "POST"
        });        
      }

      function updateNotes(element){
        let notes = element.value;
        $.ajax({
          url: "update-note.php", 
          type: "POST",
          data: {notes: notes}
        });        
      }
    </script>
  </body>
</html>
