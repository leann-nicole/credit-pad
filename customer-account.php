<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Listahan</title>
    <link rel="stylesheet" href="style.css"/>
  </head>
  <body>
    <p id="error" style="<?php if (isset($_GET['error'])) {
          echo 'visibility:visible';
      } else {
          echo 'visibility:hidden';
      } ?>">
        <?php if (isset($_GET['error'])) {
            echo $_GET['error'];
        } else {
            echo 'account created successfully';
        } ?>
    </p>        
    <header>
      <p id="sitename-header"><a href="customers.php">CREDIT PAD</a></p>
      <a href="logout.php"><span id="username"><?php echo $_SESSION["username"]; ?></span></a>
    </header>
    <div id="content">
      <nav>
        <ul>
          <li style="background-color: #d9d9d9;"><a href="customers.php">CUSTOMERS</a></li>
          <li><a href="products.php">PRODUCTS</a></li>
        </ul>
      </nav>
      <main>
        <div id="customer-info-div" class="container" data-name="<?php echo $_GET['customer'];?>">
          
        </div>
      </main>
      <div id="extra">
        <div id="notes-header">NOTES</div>
        <textarea id="notes" class="field" placeholder="Write your quick notes here" onkeyup="updateNotes(this)" spellcheck="false"><?php if(!empty($_SESSION["notes"])){echo $_SESSION["notes"];}?></textarea>
      </div>
    </div>
    <footer></footer>
    <script type="text/javascript" src="jquery.js"></script>
    <script>
      $(document).ready(function () {
        let name = $("#customer-info-div").data("name");
        fetchCustomerInfo(name);
        fetchNotes();
      });

      function fetchCustomerInfo(name){
        $.ajax({
          url: "fetch-customer-info.php",
          type: "POST",
          data: {name: name},
          success: function(data){
            $("#customer-info-div").html(data);
          }
        });
      }

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
