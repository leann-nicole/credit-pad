<?php
session_start();
if (!isset($_SESSION['customerLoggedIn'])) {
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
      <p id="sitename-header"><a href="customer-home.php">CREDIT PAD</a></p>
      <a href="logout.php"><span id="store-icon" class="material-icons">person</span></a>
    </header>
    <div id="content">
      <nav>
        
      </nav>
      <main id="customer-main-section">
        <div id="customer-profile-info-div" class="container" data-name="<?php echo $_SESSION['username']; ?>" data-store="<?php echo $_SESSION['business_name']; ?>"></div>
        <div id="tab-section">
          <div class="selected-navbar-item" onclick="switchTab(this)">HISTORY</div>
          <div onclick="switchTab(this)">REPORTS</div>
        </div>
        <div id="tab-content" class="container">
          <div id="history-div">
            <div id="history-tools">
              <span id="payments" class="button selected-history-type" onclick="filterHistory(this)">Payments</span>
              <span id="credits" class="button selected-history-type" onclick="filterHistory(this)">Credits</span>
              <button type="button" class="button print-button">Print</button>
              <button type="button" class="gray-button sort-button-order" onclick="fetchHistory(this)">Date<span id="sort-arrow" class="material-icons">arrow_downward</span></button>
              <div id="date-interval">
                <input type="date" id="start-date" class="field" title="start date" onchange="filterHistory(this)">
                <input type="date" id="end-date" class="field" title="end date" onchange="filterHistory(this)">
              </div>
            </div>
            <div id="history-list"></div>
          </div>
          <div id="reports-div"></div>
        </div>
      </main>
      <div id="extra">
      <div id="notifications-div">
        <div id="notifications-header">NOTIFICATIONS</div>
        <div id="notification-list"><p class="notification">No notifcations at the moment.</p></div>
      </div>
      </div>
    </div>
    <footer></footer>
    <script type="text/javascript" src="jquery.js"></script>
    <script>
      let customer = "";
      let store = "";
      let historyOrder = "Most Recent First";

      function focusSearchBar(){
        $("#search-field").focus();
      }

      function fetchCustomerInfo(){
        $.ajax({
          url: "fetch-customer-info.php",
          type: "POST",
          data: {customer: customer},
          success: function(data){
            $("#customer-profile-info-div").html(data);
            document.getElementById("customer-credit").textContent = "₱\ " + document.getElementById("customer-credit").textContent;
            $("#edit-button").hide();
          }
        });
      }

      function filterHistory(element){
        let historyItems = document.querySelectorAll(".history-item");
        let toFilterByType = [];
        for (let i = 0; i < historyItems.length; i++) toFilterByType.push(i);

        // two step process
        // filter by date first
        // this stage also resets previous filters by setting the display of any date matched item to flex
        // filter by type will take place right after
        let startDate = $("#start-date").val();
        let endDate = $("#end-date").val();
        
        if (startDate == "" && endDate == ""){ // default, show all
          historyItems.forEach(function (item) {
            item.style.display = "flex";
          })
        }
        else if ((startDate != "" && endDate == "") || (startDate == "" && endDate != "")){ // one date provided, exact match needed
          let dateToMatch = (startDate != "" && endDate == "")? startDate : endDate;
          historyItems.forEach(function (item, index) {
            let itemDate = item.querySelector(".history-item-date").getAttribute("data-date");
            if (itemDate == dateToMatch) item.style.display = "flex";
            else{
              item.style.display = "none";
              toFilterByType = toFilterByType.filter(e => e != index);
            }
          })
        }
        else if (startDate == endDate ) { // one date provided, exact match needed
          let dateToMatch = startDate;
          historyItems.forEach(function (item, index) {
            let itemDate = item.querySelector(".history-item-date").getAttribute("data-date");
            if (itemDate == dateToMatch) item.style.display = "flex";
            else{
              item.style.display = "none";
              toFilterByType = toFilterByType.filter(e => e != index);
            }
          })
        }
        else if (startDate != endDate) { // two dates provided, match within range
          let lowerDate = new Date(startDate);
          let higherDate = new Date(endDate);
          historyItems.forEach(function (item, index){
            let itemDate = new Date(item.querySelector(".history-item-date").getAttribute("data-date"));
            if (itemDate >= lowerDate && itemDate <= higherDate || itemDate <= lowerDate && itemDate >= higherDate) item.style.display = "flex";
            else {
              item.style.display = "none";
              toFilterByType = toFilterByType.filter(e => e != index);
            }
          })
        }

        // then filter remaining by type
        if (element != undefined && element.tagName == "SPAN") element.classList.toggle("selected-history-type");
        
        let paymentsSelected = document.getElementById("payments").classList.contains("selected-history-type");
        let creditsSelected = document.getElementById("credits").classList.contains("selected-history-type");

        if (!paymentsSelected || !creditsSelected){
          if (paymentsSelected){
            toFilterByType.forEach(index => {
              let item = historyItems.item(index);
              if (item.getAttribute("data-type") != "payment-history-item") item.style.display = "none";
            })
          }
          else if (creditsSelected){
            toFilterByType.forEach(index => {
              let item = historyItems.item(index);
              if (item.getAttribute("data-type") != "credit-history-item") item.style.display = "none";
            })
          }
          else {
            toFilterByType.forEach(index => {
              historyItems.item(index).style.display = "none";
            })
          }
        }        
      }

      function fetchHistory(element){
        if (element != undefined){ // if button was clicked (not switch tab)
          if (historyOrder == "Most Recent First"){
            historyOrder = "Least Recent First";
            $(".sort-button-order").html("Date<span id='sort-arrow' class='material-icons'>arrow_upward</span>");
          }
          else {
            historyOrder = "Most Recent First";
            $(".sort-button-order").html("Date<span id='sort-arrow' class='material-icons'>arrow_downward</span>");
          }
        }
        
        $.ajax({
          url: "fetch-history.php",
          type: "POST", 
          data: {customer: customer, historyOrder: historyOrder},
          success: function(data){
            $("#history-list").html(data);
            filterHistory();
          }
        });
      }

      function showTabContent() {
        let tab = $("#tab-section > .selected-navbar-item").text();
        if (tab == "HISTORY") {
          fetchHistory();
          document.querySelector("#tab-content > div:nth-of-type(1)").style.display = "flex";
          document.querySelector("#tab-content > div:nth-of-type(2)").style.display = "none";
        }
        else if (tab == "REPORTS"){
          document.querySelector("#tab-content > div:nth-of-type(1)").style.display = "none";
          document.querySelector("#tab-content > div:nth-of-type(2)").style.display = "flex";
        }
      }

      function switchTab(element){
        document.querySelector("#tab-section > .selected-navbar-item").classList.remove("selected-navbar-item");
        element.classList.add("selected-navbar-item");
        showTabContent();
      }

      function fetchNotifications(){
        $.ajax({
          url: "fetch-notifications.php",
          type: "POST",
          data: {customer: customer, store: store},
          success: function (data){
            if (data != ""){
              $("#notification-list").html(data);
            }                     
          }
        });
      }

      $(document).ready(function () {
        customer = $("#customer-profile-info-div").data("name");
        store = $("#customer-profile-info-div").data("store");
        fetchCustomerInfo();
        showTabContent();
        fetchHistory();
        fetchNotifications();
      });
      // shorthand for $(document).ready(); is $();
      // can also do $(window).on("load", function(){}); 
      // that is if you want code inside to run once entire page is ready, not just DOM
    </script>
  </body>
</html>
