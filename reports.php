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
      <a href="logout.php"><span id="store-icon" class="material-icons">storefront</span></a>
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
    <footer></footer>
    <script type="text/javascript" src="jquery.js"></script>
    <script>
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
