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
      <p id="sitename-header"><a href="customers.php">Credit Pad</a></p>
      <div id="dropdown">
        <button type="button" id="dropdown-button" class="material-icons" onclick="toggleAccountOptions()">storefront<span class="material-icons">arrow_drop_down</span></button>
        <div id="dropdown-menu" class="hidden-item">
          <a href="#">Profile</a>
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
        <iframe id="printFrame" name="printFrame" frameborder="0"></iframe>
        <div id="reports-div" class="container overall-report-div">
          <div>
            <button type="button" class="button print-button" onclick="printDocument()">Print</button>
            <select name="period" id="period" class="field" onchange="generateReport()">
              <option value="week" data-period="week" selected>This week</option>
              <option value="month" data-period="month">This month</option>
              <option value="year" data-period="year">This year</option>
            </select>
          </div>
          <div id="graph-div">
            
          </div>
          <div id="table-div">

          </div>
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
      <a href="customers.php" id="footer-website-name">Credit Pad</a>
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
      function toggleAccountOptions(){
        $("#dropdown-menu").toggleClass("hidden-item");
        $("#dropdown-menu").toggleClass("container");
        let arrow = $("#dropdown-button span").text();
        (arrow == "arrow_drop_down")? $("#dropdown-button span").text("arrow_drop_up") : $("#dropdown-button span").text("arrow_drop_down");
      }

      function focusSearchBar(){
        $("#search-field").focus();
      }

      function printDocument(){
        let graphContent = document.getElementById("graph-div").innerHTML;
        let tableContent = document.getElementById("table-div").innerHTML;

        let period = $("#period option:selected").data("period");
        $.ajax({
          url: "get-report-header.php",
          type: "post",
          data: {period: period},
          success: function (data){
            let jQPrintFrame = $("#printFrame").contents();
            jQPrintFrame.find("#report-header").html(data);
            jQPrintFrame.find("#graph-div").html(graphContent);
            jQPrintFrame.find("#table-div").html(tableContent);

            window.frames["printFrame"].print();
          }
        });
      }

      function toggleContent(element){
        if (!element.style.borderBottom && element.nextElementSibling.nextElementSibling) element.style.borderBottom = "1px solid lightgray";
        else element.style.borderBottom = "";
        element.nextElementSibling.classList.toggle("hidden-item");
        if (element.children[1].textContent == "expand_more") element.children[1].textContent = "expand_less";
        else element.children[1].textContent = "expand_more";
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
          data: {period: period},
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
              biggestVal= 10;
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
          data: {period: period},
          success: function (data){
            $("#table-div").html(data);
          }
        });
      }

      function prepareFrame(){
        let printFrame = document.getElementById("printFrame");
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
              <div id="graph-div"></div>
              <div id="table-div"></div>
            </body>
          </html>
          `
        );
        frameDoc.document.close(); 
      }

      $(document).ready(function () {
        $("#copyright").html("Copyright " + "&copy; " + new Date().getFullYear() + " Credit Pad");
        fetchNotes();
        generateReport();
        prepareFrame();
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
