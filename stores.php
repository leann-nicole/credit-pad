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
      <p id="sitename-header"><a href="applicants.php">Credit Pad</a></p>
      <div id="dropdown">
        <button type="button" id="dropdown-button" class="material-icons" onclick="toggleAccountOptions()">person<span class="material-icons">arrow_drop_down</span></button>
        <div id="dropdown-menu" class="hidden-item">
          <a href="profile.php">Profile</a>
          <a href="logout.php">Log out</a>
        </div>
      </div>
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
            <input type="date" id="start-date" class="field" title="start date" onchange="filterList()">
            <input type="date" id="end-date" class="field" title="end date" onchange="filterList()">
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
    <footer>
      <a href="applicants.php" id="footer-website-name">Credit Pad</a>
      <a href="index.php#about-section" class="guide-link">About</a>
      <a href="index.php#terms-of-use-section" class="guide-link">Terms of Use</a>
      <a href="index.php#privacy-policy-section" class="guide-link">Privacy Policy</a>
      <a href="index.php#contact-us-section" class="guide-link">Contact Us</a>
      <div id="external-social-links">
        <a href="#"><img src="images/facebook.png" alt=""></a>
        <a href="#"><img src="images/twitter.png" alt=""></a>
        <a href="#"><img src="images/github.png" alt=""></a>
        <a href="#"><img src="images/paypal.png" alt=""></a>
      </div>
      <p id="copyright"></p>
    </footer>
    <script type="text/javascript" src="jquery.js"></script>
    <script>
      // global variables
      let sortBy = "date_approved";
      let sortOrder = "DESC";

      function toggleAccountOptions(){
        $("#dropdown-menu").toggleClass("hidden-item");
        $("#dropdown-menu").toggleClass("container");
        let arrow = $("#dropdown-button span").text();
        (arrow == "arrow_drop_down")? $("#dropdown-button span").text("arrow_drop_up") : $("#dropdown-button span").text("arrow_drop_down");
      }

      function focusSearchBar(){
        $("#search-field").focus();
      }

      function filterList(){
        let storeList = document.querySelectorAll(".store-item"); // get applicants
        
        // filter by date first
        let startDate = $("#start-date").val();
        let endDate = $("#end-date").val();
        
        if (startDate == "" && endDate == ""){ // default, show all
          storeList.forEach(function (item) {
            item.style.display = "flex";
          })
        }
        else if ((startDate != "" && endDate == "") || (startDate == "" && endDate != "")){ // one date provided, exact match needed
          let dateToMatch = (startDate != "" && endDate == "")? startDate : endDate;
          storeList.forEach(function (item, index) {
            let itemDate = item.querySelector(".store-approval-date").getAttribute("data-date");
            if (itemDate == dateToMatch) item.style.display = "flex";
            else{
              item.style.display = "none";
            }
          })
        }
        else if (startDate == endDate ) { // one date provided, exact match needed
          let dateToMatch = startDate;
          storeList.forEach(function (item, index) {
            let itemDate = item.querySelector(".store-approval-date").getAttribute("data-date");
            if (itemDate == dateToMatch) item.style.display = "flex";
            else{
              item.style.display = "none";
            }
          })
        }
        else if (startDate != endDate) { // two dates provided, match within range
          let lowerDate = new Date(startDate);
          let higherDate = new Date(endDate);
          storeList.forEach(function (item, index){
            let itemDate = new Date(item.querySelector(".store-approval-date").getAttribute("data-date"));
            if (itemDate >= lowerDate && itemDate <= higherDate || itemDate <= lowerDate && itemDate >= higherDate) item.style.display = "flex";
            else {
              item.style.display = "none";
            }
          })
        }
        // then filter by search input
        if ($("table").attr("id") == "no-stores-banner") return;
        let searchInput = document.getElementById("search-field").value.toLowerCase(); // get search bar and value in it

        for (let i = 0; i < storeList.length; i++){ // loop through applicants
          // show item if it contains the search value and is also unfiltered by the date filter yet
          if ((storeList[i].style.display != "none") && (storeList[i].querySelector(".store-name").textContent.toLowerCase().indexOf(searchInput) > -1)) storeList[i].style.display = "";
          else storeList[i].style.display = "none";
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
        $("#copyright").html("Copyright " + "&copy; " + new Date().getFullYear() + " Credit Pad");
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
