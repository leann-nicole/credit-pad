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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
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

      // show list of customers belonging to the current store owner
      function loadCustomers() {
            // will be using ajax here, ajax allows you to connect to a server in the background, that is without reloading the page
            // syntax: $.ajax({});
            // creates XMLHttpRequest object
            // response from server is processed by JavaScript
            // AJAX: Asynchronous JavaScript and XML
            $.ajax({
            url: "load-customers.php",
            type: "POST",
            success: function (data) {
                $("#list-inner-div").html(data);
            }
            });
      }

      function sortCustomers(element) {
          let ccolname = element.getAttribute("data-colname");
          $.ajax({
            url: "load-customers.php",
            type: "POST",
            data: {ccolname: ccolname},
            success: function (data) {
                $("#list-inner-div").html(data);
                filterList();
            }
          });
      }

      $(document).ready(function () {
        loadCustomers();
        fetchNotes();
      });
      // shorthand for $(document).ready(); is $();
      // can also do $(window).on("load", function(){}); 
      // that is if you want code inside to run once entire page is ready, not just DOM

      // show or hide form
      function showHide(){
            document.getElementById("create-form-div-c").classList.toggle("hidden-item");
            document.getElementById("error").style.visibility = "hidden"; // can also do document.getElementById("error").setAttribute("style","visibility: hidden;"); but is considered bad practice since it will overwrite properties which may already be specified in the style attribute
            $("input[type='text']").val("");
            $("input[type='date']").val("");
            $("select[name='sex'] option").prop("selected", false);
            $("input[name='rate']:radio").prop("checked", false);
            $("#star1").prop("checked", true);
      }

      function filterList(){
        let searchInput = document.getElementById("search-field").value.toLowerCase(); // get search bar and value in it
        let tableRows = document.getElementById("customer-list-table").getElementsByTagName("tr"); // get table and rows in it

        for (let i = 1; i < tableRows.length; i++){ // loop through rows  
          let columns = tableRows[i].getElementsByTagName("td"); // get the items in each row
          let showRow = false; // hide row by default
          for (let j = 0; j < columns.length; j++){ // loop through items in each row to check if there is a match with the searchInput value 
            let item = columns[j].textContent;
            if (item.toLowerCase().indexOf(searchInput) > -1){
              showRow = true;
              break;
            }
          }
          if (!showRow){tableRows[i].style.display = "none";}
          else {tableRows[i].style.display = "";}
        }
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

      function selectCustomer(element){
        let customer = element.getElementsByTagName("td")[0].textContent;
        let url = "customer-account.php?customer=" + customer;
        window.location.href=url;
      }
    </script>
  </body>
</html>
