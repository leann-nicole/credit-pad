<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
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
      <p id="sitename-header"><a href="applicants.php">CREDIT PAD</a></p>
      <a href="logout.php"><span id="account-icon" class="material-icons">account_circle</span></a>
    </header>
    <div id="content">
      <nav>
        <ul>
          <li><a href="applicants.php">APPLICANTS</a></li>
          <li class="selected-navbar-item"><a href="stores.php">STORES</a></li>
        </ul>
      </nav>
      <main>
        <div id="admin-list-tools">
          <button type="button" class="gray-button sort-button-order" onclick="loadStores('order')"><span id="sort-arrow" class="material-icons">arrow_downward</span></button>
          <button type="button" class="gray-button sort-button-by" onclick="loadStores('by')">Date</button>
          <div id="date-interval">
            <input type="date" id="start-date" class="field" title="start date" onchange="filterHistory(this)">
            <input type="date" id="end-date" class="field" title="end date" onchange="filterHistory(this)">
          </div>
          <div id="search-div">
            <input type="text" id="search-field" class="field" placeholder="Search" onkeyup="filterList()">
            <span id="search-icon" class="material-icons" onclick="focusSearchBar()">search</span>
          </div>
        </div>
        <div id="store-list">

        </div>
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
      // global variables
      let sortBy = "date_approved";
      let sortOrder = "DESC";

      function focusSearchBar(){
        $("#search-field").focus();
      }

      function filterList(){
        if ($("table").attr("id") == "no-stores-banner") return;
        let searchInput = document.getElementById("search-field").value.toLowerCase(); // get search bar and value in it
        let applicantList = document.getElementsByClassName("store-name"); // get applicants

        for (let i = 0; i < applicantList.length; i++){ // loop through applicants
          if (applicantList[i].textContent.toLowerCase().indexOf(searchInput) > -1) applicantList[i].parentElement.style.display = "";
          else applicantList[i].parentElement.style.display = "none";
        }
      }

      function loadStores(sort){
        if (sort == "by"){
          if ($(".sort-button-by").text() == "Name"){
            sortBy = "date_approved";
            $(".sort-button-by").text("Date");
          }
          else {
            sortBy = "business_name";
            $(".sort-button-by").text("Name");
          }
        }
        else if (sort == "order"){
          if ($("#sort-arrow").text() == "arrow_upward"){
            sortOrder = "DESC";
            $("#sort-arrow").text("arrow_downward");
          }
          else {
            sortOrder = "ASC";
            $("#sort-arrow").text("arrow_upward");
          }
        }
        $.ajax({
          url: "load-stores.php",
          type: "POST",
          data: {sortBy : sortBy, sortOrder : sortOrder},
          success: function (data){
            $("#store-list").html(data);
            filterList();
          }
        });
      }

      $(document).ready(function () {
        fetchNotes();
        loadStores("default");
      });
      // shorthand for $(document).ready(); is $();
      // can also do $(window).on("load", function(){}); 
      // that is if you want code inside to run once entire page is ready, not just DOM


      function fetchNotes(){
        $.ajax({
          url: "update-note.php", 
          type: "POST",
          data: {admin: true}
        });        
      }

      function updateNotes(element){
        let notes = element.value;
        $.ajax({
          url: "update-note.php", 
          type: "POST",
          data: {admin: true, notes: notes}
        });        
      }
    </script>
  </body>
</html>
