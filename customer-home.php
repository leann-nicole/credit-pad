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
      <p id="sitename-header"><a href="customer-home.php">Credit Pad</a></p>
      <div id="dropdown">
        <button type="button" id="dropdown-button" class="material-icons" onclick="toggleAccountOptions()">person<span class="material-icons">arrow_drop_down</span></button>
        <div id="dropdown-menu" class="hidden-item">
          <a href="#">Profile</a>
          <a href="logout.php">Log out</a>
        </div>
      </div>
    </header>
    <div id="content">
      <nav>
        
      </nav>
      <main id="customer-main-section">
        <iframe id="printFrameHistory" name="printFrameHistory" frameborder="0"></iframe>
        <iframe id="printFrameReport" name="printFrameReport" frameborder="0"></iframe>
        <div id="customer-profile-info-div" class="container" data-name="<?php echo $_SESSION['username']; ?>" data-store="<?php echo $_SESSION['business_name']; ?>"></div>
        <div id="tab-section">
          <div class="selected-navbar-item" onclick="switchTab(this)">HISTORY</div>
          <div onclick="switchTab(this)">REPORTS</div>
        </div>
        <div id="tab-content" class="container">
          <div id="history-div">
            <div id="history-tools">
              <span id="payments" class="button selected-history-type" onclick="filterHistory(this)">Payment</span>
              <span id="credits" class="button selected-history-type" onclick="filterHistory(this)">Credit</span>
              <button type="button" class="button print-button" onclick="printDocumentHistory()">Print</button>
              <button type="button" class="gray-button sort-button-order" onclick="fetchHistory(this)">Date<span id="sort-arrow" class="material-icons">arrow_downward</span></button>
              <div id="date-interval">
                <input type="date" id="start-date" class="field" title="start date" onchange="filterHistory(this)">
                <input type="date" id="end-date" class="field" title="end date" onchange="filterHistory(this)">
              </div>
            </div>
            <div id="history-list"></div>
          </div>
          <div id="reports-div">
            <div>
                <button type="button" class="button print-button" onclick="printDocumentReport()">Print</button>
                <select name="period" id="period" class="field" onchange="generateReport()">
                  <option value="week" data-period="week" selected>This week</option>
                  <option value="month" data-period="month">This month</option>
                  <option value="year" data-period="year">This year</option>
                </select>
              </div>
              <div id="graph-div"></div>
              <div id="table-div">

              </div>
            </div>
        </div>
      </main>
      <div id="extra">
      <div id="notifications-div">
        <div id="notifications-header">NOTIFICATIONS</div>
        <div id="notification-list"><p class="notification">No notifcations at the moment.</p></div>
      </div>
      </div>
    </div>
    <footer>
      <a href="customer-home.php" id="footer-website-name">Credit Pad</a>
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
    <script src="https://d3js.org/d3.v7.min.js" defer></script>
    <script type="text/javascript" src="jquery.js"></script>
    <script>
      let customer = "";
      let store = "";
      let historyOrder = "Most Recent First";

      function toggleAccountOptions(){
        $("#dropdown-menu").toggleClass("hidden-item");
        $("#dropdown-menu").toggleClass("container");
        let arrow = $("#dropdown-button span").text();
        (arrow == "arrow_drop_down")? $("#dropdown-button span").text("arrow_drop_up") : $("#dropdown-button span").text("arrow_drop_down");
      }

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
          generateReport();
          document.querySelector("#tab-content > div:nth-of-type(1)").style.display = "none";
          document.querySelector("#tab-content > div:nth-of-type(2)").style.display = "flex";
        }
      }

      function switchTab(element){
        document.querySelector("#tab-section > .selected-navbar-item").classList.remove("selected-navbar-item");
        element.classList.add("selected-navbar-item");
        showTabContent();
      }

      function printDocumentHistory(){
        let historyContent = document.getElementById("history-list").innerHTML;

        let startDate = $("#start-date").val();
        let endDate = $("#end-date").val();
        let historyType = $(".selected-history-type").text();

        $.ajax({
          url: "get-report-header.php",
          type: "post",
          data: {startDate : startDate, endDate : endDate, historyType : historyType, customer: customer},
          success: function (data){
            let jQPrintFrame = $("#printFrameHistory").contents();
            jQPrintFrame.find("#report-header").html(data);
            jQPrintFrame.find("#history-list-div").html(historyContent);

            window.frames["printFrameHistory"].print();
          }
        });
      }


      function printDocumentReport(){
        let graphContent = document.getElementById("graph-div").innerHTML;
        let tableContent = document.getElementById("table-div").innerHTML;

        let period = $("#period option:selected").data("period");
        $.ajax({
          url: "get-report-header.php",
          type: "post",
          data: {period: period, page: "report", customer: customer},
          success: function (data){
            let jQPrintFrame = $("#printFrameReport").contents();
            jQPrintFrame.find("#report-header").html(data);
            jQPrintFrame.find("#graph-div").html(graphContent);
            jQPrintFrame.find("#table-div").html(tableContent);

            window.frames["printFrameReport"].print();
          }
        });
      }

      let margin = {top: 10, right: 30, bottom: 20, left: 50};
      let graphWidth = 600 - margin.left - margin.right;
      let graphHeight = 300 - margin.top - margin.bottom;
      let days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
      let weeks = ["Week 1", "Week 2", "Week 3", "Week 4", "Week 5"];
      let months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

      function generateReport(){
        let period = $("#period option:selected").data("period");
        $("#graph-div").html("");

        let tooltip = d3.select("#graph-div")
          .append("div")
          .classed("tooltip", true)
          
        $.ajax({
          url: "get-graph-data.php",
          type: "POST", 
          data: {customer: customer, period: period},
          dataType: "json",
          success: function(data){
            let subPeriods = "";
            let xValues = [];
            if (period == "month"){
              subPeriods = data.splice(1);
              xValues = weeks;
            }
            else {
              subPeriods = data;
              if (period == "week") xValues = days;
              else xValues = months;
            }
            // determine by how much height of graph needs to be increased
            let biggestVal = subPeriods.map(function(sp){ // use map to get array of biggest numbers among all subperiods
              return sp.reduce(function(a,b){
                return Math.max(a,b);
              });
            }).reduce(function(a,b){return Math.max(a,b);}); // use reduce to get the biggest among the biggest :)
            
            let noData = false;
            if (biggestVal != 0){
              // set graph biggest possible value on Y axis based on biggest value
              let q = biggestVal;
              let m = 1;
              while (q > 10){ // ex. q = 32, m = 10, biggest possible value on Y axis = round up (q/m) * m = 40
                m *= 10;
                q = Math.ceil(q/10);
              }
              biggestVal = Math.ceil(biggestVal/m) * m;
            }
            else {
              noData = true;
              biggestVal = 10;
            } 

            // set up svg element
            let svg = d3.select("#graph-div")
              .append("svg")
                .attr("width", graphWidth + margin.left + margin.right)
                .attr("height", graphHeight + margin.top + margin.bottom)
              .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")")
            // add X axis
            let x = d3.scaleBand()
              .domain(xValues)
              .range([0, graphWidth])
              .padding([0.2])
            svg.append("g")
              .attr("transform", "translate(0," + graphHeight + ")")
              .call(d3.axisBottom(x).tickSize(0))
            // add Y axis
            let y = d3.scaleLinear()
              .domain([0, biggestVal])
              .range([graphHeight, 0])
            svg.append("g")
              .call(d3.axisLeft(y))

            let bars = ["credit", "payment", "due"];

            // add credit, payment, due
            let xSub = d3.scaleBand()
              .domain(bars)
              .range([0, x.bandwidth()])
              .padding([0.1])
            // define colors
            let color = d3.scaleOrdinal()
              .domain(bars)
              .range(["#508CB0", "#53b050", "#FC9D00"])
            //
            svg.append("g")
              .selectAll("g")
              .data(subPeriods) 
              .enter()
              .append("g")
                .attr("transform", function (d, i) { return "translate(" + x(xValues[i]) + ",0)";})   
              .selectAll("rect")
              .data(function(d) { return d; })
              .enter()
              .append("rect")
                .attr("x", function(d, i) { return xSub(bars[i]); })
                .attr("y", function(d) { return y(d); })
                .attr("width", xSub.bandwidth())
                .attr("height", function(d) { return graphHeight - y(d); })
                .attr("fill", function(d, i) { return color(bars[i]); })
                .classed("bar", true)
                
            d3.selectAll(".bar") 
              .on("mouseover", function (event, d) { tooltip.style("opacity", 1) })
              .on("mousemove", function (event, d) { 
                tooltip.html("₱ " + d)
                  .style("left", (event.offsetX) + 10 + "px")
                  .style("top", (event.offsetY) - 30 + "px") })
              .on("mouseleave", function (event, d) { tooltip.style("opacity", 0) })

            if (noData){
              d3.select("#graph-div")
                .append("div")
                .classed("no-data", true)
                .text("No data available")
            }
          }
        });

        $.ajax({
          url: "fetch-report.php",
          type: "POST", 
          data: {customer: customer, period: period},
          success: function (data){
            $("#table-div").html(data);
          }
        });
      }

      function prepareFrames(){
        // history frame
        let printFrame = document.getElementById("printFrameHistory");
        let frameDoc = (printFrame.contentWindow) ? printFrame.contentWindow : (printFrame.contentDocument.document) ? printFrame.contentDocument.document : printFrame.contentDocument;
        
        frameDoc.document.open();
        frameDoc.document.writeln(
          `<!DOCTYPE html>
          <html>
            <head>
            <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
            <link rel="stylesheet" href="style.css"/>
            </head>
            <body id="printframe-body">
              <div id="report-header"></div>
              <div id="history-list-div"></div>
            </body>
          </html>
          `
        );
        frameDoc.document.close(); 

        // report iframe
        printFrame = document.getElementById("printFrameReport");
        frameDoc = (printFrame.contentWindow) ? printFrame.contentWindow : (printFrame.contentDocument.document) ? printFrame.contentDocument.document : printFrame.contentDocument;

        frameDoc.document.open();
        frameDoc.document.writeln(
          `<!DOCTYPE html>
          <html>
            <head>
            <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
            <link rel="stylesheet" href="style.css"/>
            </head>
            <body id="printframe-body">
              <div id="report-header"></div>
              <div id="graph-div"></div>
              <div id="table-div"></div>
            </body>
          </html>
          `
        );
        frameDoc.document.close(); 
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
        $("#copyright").html("Copyright " + "&copy; " + new Date().getFullYear() + " Credit Pad");
        customer = $("#customer-profile-info-div").data("name");
        store = $("#customer-profile-info-div").data("store");
        fetchCustomerInfo();
        showTabContent();
        fetchHistory();
        fetchNotifications();
        prepareFrames();
      });
      // shorthand for $(document).ready(); is $();
      // can also do $(window).on("load", function(){}); 
      // that is if you want code inside to run once entire page is ready, not just DOM
    </script>
  </body>
</html>
