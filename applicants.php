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
          <li class="selected-navbar-item"><a href="applicants.php">APPLICANTS</a></li>
          <li><a href="stores.php">STORES</a></li>
        </ul>
      </nav>
      <main>
        <div id="admin-list-tools">
          <button type="button" class="gray-button sort-button-order" onclick="loadApplicants('order')"><span id="sort-arrow" class="material-icons">arrow_downward</span></button>
          <button type="button" class="gray-button sort-button-by" onclick="loadApplicants('by')">Date</button>
          <div id="date-interval">
            <input type="date" id="start-date" class="field" title="start date" onchange="filterList()">
            <input type="date" id="end-date" class="field" title="end date" onchange="filterList()">
          </div>
          <div id="search-div">
            <input type="text" id="search-field" class="field" placeholder="Search" onkeyup="filterList()" spellcheck="false">
            <span id="search-icon" class="material-icons" onclick="focusSearchBar()">search</span>
          </div>
        </div>
        <div id="applicant-list">

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
    </footer>
    <script type="text/javascript" src="jquery.js"></script>
    <script>
      // global variables
      let sortBy = "application_date";
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
        let applicantList = document.querySelectorAll(".applicant-item"); // get applicants
        
        // filter by date first
        let startDate = $("#start-date").val();
        let endDate = $("#end-date").val();
        
        if (startDate == "" && endDate == ""){ // default, show all
          applicantList.forEach(function (item) {
            item.style.display = "flex";
          })
        }
        else if ((startDate != "" && endDate == "") || (startDate == "" && endDate != "")){ // one date provided, exact match needed
          let dateToMatch = (startDate != "" && endDate == "")? startDate : endDate;
          applicantList.forEach(function (item, index) {
            let itemDate = item.querySelector(".applicant-date").getAttribute("data-date");
            if (itemDate == dateToMatch) item.style.display = "flex";
            else{
              item.style.display = "none";
            }
          })
        }
        else if (startDate == endDate ) { // one date provided, exact match needed
          let dateToMatch = startDate;
          applicantList.forEach(function (item, index) {
            let itemDate = item.querySelector(".applicant-date").getAttribute("data-date");
            if (itemDate == dateToMatch) item.style.display = "flex";
            else{
              item.style.display = "none";
            }
          })
        }
        else if (startDate != endDate) { // two dates provided, match within range
          let lowerDate = new Date(startDate);
          let higherDate = new Date(endDate);
          applicantList.forEach(function (item, index){
            let itemDate = new Date(item.querySelector(".applicant-date").getAttribute("data-date"));
            if (itemDate >= lowerDate && itemDate <= higherDate || itemDate <= lowerDate && itemDate >= higherDate) item.style.display = "flex";
            else {
              item.style.display = "none";
            }
          })
        }
        // then filter by search input
        if ($("table").attr("id") == "no-applications-banner") return;
        let searchInput = document.getElementById("search-field").value.toLowerCase(); // get search bar and value in it

        for (let i = 0; i < applicantList.length; i++){ // loop through applicants
          // show item if it contains the search value and is also unfiltered by the date filter yet
          if ((applicantList[i].style.display != "none") && (applicantList[i].querySelector(".applicant-business-name").textContent.toLowerCase().indexOf(searchInput) > -1)) applicantList[i].style.display = "";
          else applicantList[i].style.display = "none";
        }
      }

      function loadApplicants(sort){
        if (sort == "by"){
          if ($(".sort-button-by").text() == "Name"){
            sortBy = "application_date";
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
          url: "load-applicants.php",
          type: "POST",
          data: {sortBy : sortBy, sortOrder : sortOrder},
          success: function (data){
            $("#applicant-list").html(data);
            filterList();
          }
        });
      }

      $(document).ready(function () {
        fetchNotes();
        loadApplicants("default");
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

      function approveApplicant(element){
        let applicantItem = element.parentElement.parentElement.parentElement;
        let storeName = applicantItem.getElementsByClassName("applicant-business-name")[0].textContent;
        $.ajax({
          url: "approve-applicant.php",
          type: "POST",
          data: {storeName: storeName},
          success: function(data){
            loadApplicants();
          }
        });
      }

      function rejectApplicant(element){
        let applicantItem = element.parentElement.parentElement.parentElement;
        let storeName = applicantItem.getElementsByClassName("applicant-business-name")[0].textContent;
        let storeOperator = applicantItem.getElementsByClassName("applicant-name")[0].textContent;
        let storeLocation = applicantItem.getElementsByClassName("applicant-business-location")[0].textContent;
        let applicationDate = applicantItem.getElementsByClassName("applicant-date")[0].textContent;
        console.log(storeName, storeOperator, storeLocation, applicationDate);
        $.ajax({
          url: "reject-applicant.php",
          type: "POST",
          data: {storeName: storeName, storeOperator: storeOperator, storeLocation: storeLocation, applicationDate: applicationDate},
          success: function(data){
            loadApplicants();
          }
        });
      }
    </script>
  </body>
</html>
