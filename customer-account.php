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
    <p id="error" class="<?php if (!isset($_GET['error']) && !isset($_GET['response'])) { echo 'hidden-item'; }?>">
        <?php 
          if (isset($_GET['error'])) { echo $_GET['error']; }
          else if (isset($_GET['response'])) { echo $_GET['response']; }
        ?>
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
          <li class="selected-navbar-item"><a href="customers.php">CUSTOMERS</a></li>
          <li><a href="products.php">PRODUCTS</a></li>
          <li><a href="reports.php">REPORTS</a></li>
        </ul>
      </nav>
      <main>
        <iframe id="printFrameHistory" name="printFrameHistory" frameborder="0"></iframe>
        <iframe id="printFrameReport" name="printFrameReport" frameborder="0"></iframe>
        <div id="edit-form-div-c" class="<?php if (!isset($_GET['error'])) {
            echo 'hidden-item';
        } ?> container">
          <div class="form-name">EDIT CUSTOMER ACCOUNT</div>
          <form id="edit-form" autocomplete="off" action="validate-edit-account.php" method="post">
            <div class="form-column">
              <label for="cname" class="field-name">name</label>
              <input id="cname" class="field" type="text" name="username" maxlength="50" value="<?php if (
                  isset($_SESSION['cusername-edit'])
              ) {
                  echo $_SESSION['cusername-edit'];
              } ?>" required/>
              <label for="cbday" class="field-name">birthdate</label>
              <input id="cbday" class="field" type="date" name="birthdate" value="<?php if (
                  isset($_SESSION['cbirthdate-edit'])
              ) {
                  echo $_SESSION['cbirthdate-edit'];
              } ?>" required/>
              <label for="cgender" class="field-name">sex</label>
              <select id="cgender" name="sex" class="field">
                <option value="m" <?php if (
                    isset($_SESSION['csex-edit']) and
                    $_SESSION['csex-edit'] == 'm'
                ) {
                    echo 'selected';
                } ?>>male</option>
                <option value="f" <?php if (
                    isset($_SESSION['csex-edit']) and
                    $_SESSION['csex-edit'] == 'f'
                ) {
                    echo 'selected';
                } ?>>female</option>
              </select>
            </div>
            
            <div class="form-column">
              <label for="cphone" class="field-name">mobile number</label>
              <input id="cphone" class="field" type="text" name="mobile_no" maxlength="11" value="<?php if (
                  isset($_SESSION['cmobile_no-edit'])
              ) {
                  echo $_SESSION['cmobile_no-edit'];
              } ?>" required/>
              <label for="cmail" class="field-name">email address</label>
              <input id="cmail" class="field" type="email" name="email" maxlength="100" value="<?php if (
                  isset($_SESSION['cemail-edit'])
              ) {
                  echo $_SESSION['cemail-edit'];
              } ?>" placeholder="optional"/>
              <label for="chome" class="field-name">home address</label>
              <input id="chome" class="field" type="text" name="address" maxlength="100" value="<?php if (
                  isset($_SESSION['caddress-edit'])
              ) {
                  echo $_SESSION['caddress-edit'];
              } ?>" required/>
            </div>
            <div id="form-buttons-div">
              <button type="button" id="cancel" class="gray-button" onclick="toggleEditForm()">Cancel</button>
              <button type="submit" form="edit-form" id="save-form-button" class="button save-button" >Save</button>
            </div>

          </form>

          <div id="rating-div">
            <label for="rating" class="field-name" id="rating-field-name">rating</label>           
            <div id="rating">
              <input form="edit-form" type="radio" id="star5" class="star" name="rate" value="5" <?php if (
                  isset($_SESSION['crate-edit']) and
                  $_SESSION['crate-edit'] == 5
              ) {
                  echo 'checked';
              } ?>/>
              <label for="star5">&#128970;</label>
              <input form="edit-form" type="radio" id="star4" class="star" name="rate" value="4" <?php if (
                  isset($_SESSION['crate-edit']) and
                  $_SESSION['crate-edit'] == 4
              ) {
                  echo 'checked';
              } ?>/>
              <label for="star4">&#128970;</label>
              <input form="edit-form" type="radio" id="star3" class="star" name="rate" value="3" <?php if (
                  isset($_SESSION['crate-edit']) and
                  $_SESSION['crate-edit'] == 3
              ) {
                  echo 'checked';
              } ?>/>
              <label for="star3">&#128970;</label>
              <input form="edit-form" type="radio" id="star2" class="star" name="rate" value="2" <?php if (
                  isset($_SESSION['crate-edit']) and
                  $_SESSION['crate-edit'] == 2
              ) {
                  echo 'checked';
              } ?>/>
              <label for="star2">&#128970;</label>
              <input form="edit-form" type="radio" id="star1" class="star" name="rate" value="1" <?php if (
                  isset($_SESSION['crate-edit']) and
                      $_SESSION['crate-edit'] == 1 or
                  !isset($_SESSION['crate-edit'])
              ) {
                  echo 'checked';
              } ?>/>
              <label for="star1">&#128970;</label> 
            </div>
          </div>
          <input id="customer-name-copy" class="field hidden-item" type="text" form="edit-form" name="current_customer_name" value="<?php echo $_GET[
              'customer'
          ]; ?>">
          <span id="delete-item-clickable-text" onclick="toggleDeleteItem()">Delete this account</span>
          <div id="deletion-confirmation-popup" class="container hidden-item">
            <span>Are you sure you want to delete this account?</span>
            <span id="account-to-delete"></span>
            <span id="deletion-reminder">This will erase all payment and credit transactions made by this account.</span>
            <div id="popup-yes-no-div">
              <button id="no-button" class="gray-button" onclick="toggleDeleteItem()">Cancel</button>
              <button id="yes-button" class="button" onclick="deleteItem()">Delete</button>
            </div>
          </div>
        </div>
        <div id="customer-profile-info-div" class="container" data-name="<?php echo $_GET[
            'customer'
        ]; ?>">
          
        </div>
        <div id="tab-section">
            <div class="selected-navbar-item" onclick="switchTab(this)">CREDIT</div>
            <div onclick="switchTab(this)">PAYMENT</div>
            <div onclick="switchTab(this)">HISTORY</div>
            <div onclick="switchTab(this)">REPORTS</div>
        </div>
        <div id="tab-content" class="container">
          <div id="credit-div">
            <div id="cart-labels-div" class="cart-article field-name">
              <span id="product-name-label">Product</span>
              <span id="product-quantity-label">Quantity</span>
              <span id="product-price-label">Price</span>
              <span id="product-subtotal-label">Subtotal</span>
              <div id="dummy-add-button" class="button"></div>
            </div>
            <div id="cart-list-div">
              <div id="cart-list"></div>
              <div id="cart-input-div" class="cart-article">
                <input id="product-name-input" class="field" list="product-list" oninput="getPrice(this)">
                  <datalist id="product-list"></datalist>
                <input id="product-quantity-input"  class="field" type="number" min="0" oninput="calcPriceTotal(this)"> 
                <input id="product-price-input" class="field" type="number" oninput="calcQtyTotal(this)">
                <input id="product-subtotal-input" class="field" type="number" oninput="calcQtyPrice(this)">
                <button type="button" id="add-button" class="button material-icons" onclick="addCartItem()" title="add">add</button>
              </div>
            </div>
            <textarea id="credit-comment" class="field" placeholder="Write a comment here" spellcheck="false" maxlength="500"></textarea>

            <div id="dates-div">
              <label id="credit-date-label" for="credit-date">Credit date
                <input type="date" id="credit-date" class="field" value="<?php echo date('Y-m-d'); ?>" onchange="changeDueDate(this)">
              </label>
              <label id="due-date-label" for="credit-date">Due date
                <input type="date" id="due-date" class="field" value="<?php echo date('Y-m-d', strtotime('+1 month')); ?>">
              </label>
              <button type="button" id="save-credit-button" class="button save-button" onclick="saveCredit()">Save</button>
              <div id="grand-total"></div>
            </div>
            
          </div>
          <div id="payment-div">
            <div id="payment-types-div">
              <div id="full-payment" class="payment-type selected-payment-type" onclick="selectPayment(this)">
                <p class="payment-type-name">FULL PAYMENT</p>
                <div class="payment-calculation-div">
                  <label for="cash-received1">
                    <p class="field-name">cash received</p>
                    <input type="number" min="1" step="0.01" id="cash-received1" class="field" oninput="getChange(this)">
                  </label>
                </div>
                <p id="change1">change:</p>
              </div>   
              <div id="partial-payment" class="payment-type" onclick="selectPayment(this)">
                <p class="payment-type-name">PARTIAL PAYMENT</p>
                <div class="payment-calculation-div">
                  <label for="cash-received2">
                    <p class="field-name">cash received</p>
                    <input type="number" min="1" step="0.01" id="cash-received2" class="field" oninput="getChange(this)">
                  </label>
                  <label for="amount-paid2" class="test">
                    <p class="field-name">amount paid</p>
                    <input type="number" min="1" step="0.01" id="amount-paid" class="field" oninput="getChange(this)">
                  </label>
                </div>
                <p id="change2">change:</p>
              </div>
            </div>
            <textarea id="payment-comment" class="field" placeholder="Write a comment here" spellcheck="false"></textarea>              
            <div id="dates-div">
              <label id="payment-date-label" for="payment-date">Payment date<input type="date" id="payment-date" class="field" value="<?php echo date('Y-m-d'); ?>"></label>
              <button type="button" id="save-payment-button" class="button save-button" onclick="savePayment()">Save</button>
            </div>
          </div>
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
        <div id="notes-div">
          <div id="notes-header">NOTES</div>
          <textarea id="notes" class="field" placeholder="Write your quick notes here" onkeyup="updateNotes(this)" spellcheck="false"><?php if (
              !empty($_SESSION['notes'])
          ) {
              echo $_SESSION['notes'];
          } ?></textarea>
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
      // global variables
      let products = []; // array for storing textcontent of option elements
      let grandTotal = 0;
      let customer = "";
      let entryNo = 0;
      let historyOrder = "Most Recent First";

      function toggleContent(element){
        if (!element.style.borderBottom && element.nextElementSibling.nextElementSibling) element.style.borderBottom = "1px solid lightgray";
        else element.style.borderBottom = "";
        element.nextElementSibling.classList.toggle("hidden-item");
        if (element.children[1].textContent == "expand_more") element.children[1].textContent = "expand_less";
        else element.children[1].textContent = "expand_more";
      }

      function toggleAccountOptions(){
        document.getElementById("deletion-confirmation-popup").classList.add("hidden-item");
        if ($("#dropdown-menu").hasClass("hidden-item")) {
          $("#dropdown-menu").removeClass("hidden-item");
          $("#dropdown-menu").addClass("container");
        }
        else {
          $("#dropdown-menu").addClass("hidden-item");
          $("#dropdown-menu").removeClass("container");
        }
        let arrow = $("#dropdown-button span").text();
        (arrow == "arrow_drop_down")? $("#dropdown-button span").text("arrow_drop_up") : $("#dropdown-button span").text("arrow_drop_down");
      }

      function changeDueDate(element){
        let creditDate = new Date(element.value);
        let dueDate = new Date(creditDate.setMonth(creditDate.getMonth() + 1));
        $("#due-date").val(dueDate.toISOString().split("T")[0]);
      }

      function atMost2Dec(n){
        return Number(Math.round(n+ "e"+2)+"e-"+2); 
        // an accurate formula to avoid rounding errors learned from http://thenewcode.com/895/JavaScript-Rounding-Recipes
        // how it works: makes use of exponential numbers
        // 2e2 = 2*100 = 200
        // 2e-2 = 2/100 = .02
        // example floating point number: 2.1550 (2.16)
        // 2.1550e2 = 215.50
        // Math.round(215.50) = 216
        // 216e-2 = 2.16
        // voila! 
      }

      $(document).click(function(){
        document.getElementById("deletion-confirmation-popup").classList.add("hidden-item");
        document.getElementById("dropdown-menu").classList.add("hidden-item");
      });

      $("#dropdown-button").click(function(e){ // ignore clicks inside delete item popup
        e.stopPropagation();
      });
      
      $("#dropdown-menu a").click(function(e){ // ignore clicks inside delete item popup
        e.stopPropagation();
      });

      $("#delete-item-clickable-text").click(function(e){
        e.stopPropagation();
      });

      $("#deletion-confirmation-popup").click(function(e){
        e.stopPropagation();
      });

      function deleteItem(){
        let item = $("#account-to-delete").text();
        $.ajax({
          url: "delete-item.php",
          type: "POST", 
          data: {type: "customer", item: item},
          success: function(data){
            window.location.replace(data);
          }
        });
      }

      function toggleDeleteItem(){
        $("#dropdown-menu").addClass("hidden-item");
        $("#dropdown-menu").removeClass("container");
        document.getElementById("account-to-delete").textContent = document.getElementById("customer-name-copy").value;
        document.getElementById("deletion-confirmation-popup").classList.toggle("hidden-item");
        document.getElementById("error").classList.add("hidden-item");
      }

      function toggleEditForm(){
        document.getElementById("edit-form-div-c").classList.toggle("hidden-item");
        document.getElementById("deletion-confirmation-popup").classList.add("hidden-item");
        document.getElementById("error").classList.add("hidden-item"); 
            // current name
            $("#cname").val($("#customer-name").text());
            // current birthday 
            let current_birthday = new Date($("#customer-birthday").text()); // returns a date object that represent the datetime in a timezone based on current locale
            let current_birthday_string = new Date(current_birthday.getTime() - (current_birthday.getTimezoneOffset() * 60000)).toISOString().split("T")[0]; // makes date object correspond with UTC (standard time) by subtracting the time offset
            $("#cbday").val(current_birthday_string);
            // current gender
            let current_gender = $("#customer-gender").text();
            $("#cgender option").prop("selected", false);
            if (current_gender == "male"){
              $("#cgender option:first-of-type").prop("selected", true)
            }
            else {
              $("#cgender option:nth-of-type(2)").prop("selected", true)
            }
            // current mobile no
            $("#cphone").val($("#customer-mobile").text());
            // current email
            let current_email = $("#customer-email").text();
            if (current_email == "no email address"){
              $("#cmail").val("");
            }
            else {
              $("#cmail").val($("#customer-email").text());
            }
            // current address
            $("#chome").val($("#customer-address").text());
            // current rating
            $("#input[name='rate']:radio").prop("checked", false);
            let current_rating = $("#customer-rating").data("rating");
            if (current_rating == 1) $("#rating input[type='radio']:nth-of-type(5)").prop("checked", true);
            else if (current_rating == 2) $("#rating input[type='radio']:nth-of-type(4)").prop("checked", true);
            else if (current_rating == 3) $("#rating input[type='radio']:nth-of-type(3)").prop("checked", true);
            else if (current_rating == 4) $("#rating input[type='radio']:nth-of-type(2)").prop("checked", true);
            else if (current_rating == 5) $("#rating input[type='radio']:nth-of-type(1)").prop("checked", true);
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
          if (startDate != ""){
            historyItems.forEach(function (item, index) {
              let itemDate = item.querySelector(".history-item-date").getAttribute("data-date");
              if (itemDate >= startDate) item.style.display = "flex";
              else{
                item.style.display = "none";
                toFilterByType = toFilterByType.filter(e => e != index);
              }
            })
          }
          else {
            historyItems.forEach(function (item, index) {
              let itemDate = item.querySelector(".history-item-date").getAttribute("data-date");
              if (itemDate <= endDate) item.style.display = "flex";
              else{
                item.style.display = "none";
                toFilterByType = toFilterByType.filter(e => e != index);
              }
            })
          }
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

      function getChange(element){
        let currentCredit = Number(document.getElementById("customer-credit").textContent.substr(2).replace(/,/g, ''));
        if (!currentCredit){ return; }
        if (element.id == "cash-received1"){
          let cash = atMost2Dec(element.value);
          if (cash >= currentCredit){
            let change = atMost2Dec(cash - currentCredit);
            $("#change1").text("change: " + change);
          }
          else {
            $("#change1").text("change:");
          }
        }
        else {
          let cash = atMost2Dec($("#cash-received2").val());
          let amountPaid = atMost2Dec($("#amount-paid").val());
          if (cash && amountPaid){
            if (cash >= amountPaid){
              if (amountPaid >= currentCredit){
                $("#change2").text("Please choose full payment instead");
              }
              else {
                let change = atMost2Dec(cash - amountPaid);
                $("#change2").text("change: " + change);
              }
            }
            else {
              $("#change2").text("change:");
            }
          }
          else {
            $("#change2").text("change:");
          }
        }
      }

      function selectPayment(element){
        if (!element.classList.contains("selected-payment-type")){
          $("#payment-types-div input").val("");
          $("#change1").text("change:");
          $("#change2").text("change:");
          let paymentTypes = document.getElementsByClassName("payment-type");
          Array.from(paymentTypes).forEach(function(item){
            item.classList.remove("selected-payment-type");
          });
          element.classList.add("selected-payment-type");
        }
      }

      function saveCredit() {
        let transactionDate = $("#credit-date").val();
        let dueDate = $("#due-date").val();
        if (!grandTotal || transactionDate == "" || dueDate == "") return;
      
        // get entry number for transaction
        $.ajax({
          url: "fetch-entry-no.php",
          type: "POST",
          data: {customer: customer, transactionDate: transactionDate},
          success: function(data){
            entryNo = parseInt(data);

            let cartItems = document.getElementsByClassName("cart-item");
            let creditDate = $("#credit-date").val();
            let newCustomerCredit = atMost2Dec(Number(document.getElementById("customer-credit").textContent.substr(2).replace(/,/g, '')) + grandTotal); // replace() because Number() doesn't process commas. and using replace() with a regular expression /,/g ensures all occurences and not just the first is replaced
            let comment = $("#credit-comment").val();

            // go through cart, individually saving each item to the database
            Array.from(cartItems).forEach(function(item, index, arr){
              // get cart item information
              let product = item.querySelector("span:nth-of-type(1)").textContent;
              let quantity = Number(item.querySelector("span:nth-of-type(2)").textContent);
              let price = Number(item.querySelector("span:nth-of-type(3)").textContent);
              let subTotal = Number(item.querySelector("span:nth-of-type(4)").textContent);
    
              // save to database
              if (index == 0){
                $.ajax({
                  url: "save-credit.php",
                  type: "POST",
                  data: {customer: customer, product: product, quantity: quantity, price: price, subTotal: subTotal, grandTotal: grandTotal, creditDate: creditDate, dueDate: dueDate, entryNo: entryNo, comment: comment}
                });
              }
              else {
                $.ajax({
                  url: "save-credit.php",
                  type: "POST",
                  data: {customer: customer, product: product, quantity: quantity, price: price, subTotal: subTotal, grandTotal: 0, creditDate: creditDate, dueDate: dueDate, entryNo: entryNo}
                });
              }            
            });

            // empty cart
            let cart = document.getElementById("cart-list");
            while (cart.firstChild){
              cart.removeChild(cart.firstChild);
            }
            // refresh comment, customer credit, and grand total
            document.getElementById("credit-comment").value = "";
            document.getElementById("customer-credit").textContent = "₱\ " + newCustomerCredit.toLocaleString();
            grandTotal = 0;
            document.getElementById("grand-total").textContent = "₱\ " + grandTotal;
          }
        });              
      }

      function savePayment(){
        let transactionDate = $("#payment-date").val();
        if (transactionDate == "") return;
        $.ajax({
          url: "fetch-entry-no.php",
          type: "POST",
          data: {customer: customer, transactionDate: transactionDate},
          success: function(data){
            entryNo = parseInt(data);

            let choice = document.querySelector(".selected-payment-type");
            let currentCredit = Number(document.getElementById("customer-credit").textContent.substr(2).replace(/,/g, ''));

            if (currentCredit != 0 && choice.id == "full-payment"){
              let cash = choice.getElementsByTagName("input")[0].value;

              if (cash == "") return;
              cash = atMost2Dec(cash);
              let amountPaid = Number($("#customer-credit").text().substr(2).replace(/,/g, ""));
              if (cash < amountPaid){ return; }
              let change = atMost2Dec(cash - amountPaid);
              let paymentDate = $("#payment-date").val();
              let comment = $("#payment-comment").val();

              if (comment != ""){
                $.ajax({
                  url: "save-payment.php",
                  type: "POST",
                  data: {paymentType: "full payment", customer: customer, paymentDate: paymentDate, cash: cash, amountPaid: amountPaid, change: change, comment: comment, entryNo: entryNo},
                  success: function (data){
                    // clear inputs and comments & update current credit
                    $("#full-payment input").val("");
                    $("#payment-comment").val("");
                    $("#change1").text("change:");
                    let newCustomerCredit = atMost2Dec(currentCredit - amountPaid); 
                    document.getElementById("customer-credit").textContent = "₱\ " + newCustomerCredit.toLocaleString();
                  }
                });         
              }
              else {
                $.ajax({
                  url: "save-payment.php",
                  type: "POST",
                  data: {paymentType: "full payment", customer: customer, paymentDate: paymentDate, cash: cash, amountPaid: amountPaid, change: change, entryNo: entryNo},
                  success: function (data){
                    // clear inputs and comments & update current credit
                    $("#full-payment input").val("");
                    $("#payment-comment").val("");
                    $("#change1").text("change:");
                    let newCustomerCredit = atMost2Dec(currentCredit - amountPaid); 
                    document.getElementById("customer-credit").textContent = "₱\ " + newCustomerCredit.toLocaleString();
                  }
                });         
              }
            }
            else if (currentCredit != 0 && choice.id == "partial-payment"){
              let cash = choice.getElementsByTagName("input")[0].value;
              let amountPaid = choice.getElementsByTagName("input")[1].value;

              if (cash == "" || amountPaid == "") return;
              cash = atMost2Dec(cash);
              amountPaid = atMost2Dec(amountPaid);
              if (cash < amountPaid || currentCredit <= amountPaid){ return; }
              let change = atMost2Dec(cash - amountPaid);
              let paymentDate = $("#payment-date").val();
              let comment = $("#payment-comment").val();

              if (comment != ""){
                $.ajax({
                  url: "save-payment.php",
                  type: "POST",
                  data: {paymentType: "partial payment", customer: customer, paymentDate: paymentDate, cash: cash, amountPaid: amountPaid, change: change, comment: comment, entryNo: entryNo},
                  success: function (data){
                    $("#partial-payment input").val("");
                    $("#payment-comment").val("");
                    $("#change2").text("change:");
                    let newCustomerCredit = atMost2Dec(currentCredit - amountPaid);
                    document.getElementById("customer-credit").textContent = "₱\ " + newCustomerCredit.toLocaleString();
                  }
                });
              }
              else {
                $.ajax({
                  url: "save-payment.php",
                  type: "POST",
                  data: {paymentType: "partial payment", customer: customer, paymentDate: paymentDate, cash: cash, amountPaid: amountPaid, change: change, entryNo: entryNo},
                  success: function (data){
                    $("#partial-payment input").val("");
                    $("#payment-comment").val("");
                    $("#change2").text("change:");
                    let newCustomerCredit = atMost2Dec(currentCredit - amountPaid);
                    document.getElementById("customer-credit").textContent = "₱\ " + newCustomerCredit.toLocaleString();
                  }
                });
              }
            }
          }
        });
      }

      function calcPriceTotal(element){
        let quantity = element.value;
        if (quantity == ""){
          $("#product-subtotal-input").val("");
          return;
        }
        let price = $("#product-price-input").val();
        let total = $("#product-subtotal-input").val();

        if (price == "" && total == "") return;
        // calculate subtotal (not the price) when both fields are filled
        if (price != "" && total != "" || price != ""){
          let total = quantity * price;
          $("#product-subtotal-input").val(atMost2Dec(total));
        }
        else { // if only subtotal is filled, calculate price
          let price = total / quantity;
          $("#product-price-input").val(atMost2Dec(price));
        }
      }

      function calcQtyTotal(element){
        if (element == undefined){
          $("#product-subtotal-input").val(atMost2Dec($("#product-quantity-input").val() * $("#product-price-input").val()));
          return;
        }
        let price = element.value;
        if (price == ""){
          $("#product-subtotal-input").val("");
          return;
        }
        let quantity = $("#product-quantity-input").val();
        let total = $("#product-subtotal-input").val();

        if (quantity == "" && total == "") return;
        // calculate subtotal (not the quantity) when both fields are filled
        if (quantity != "" && total != "" || quantity != ""){
          let total = quantity * price;
          $("#product-subtotal-input").val(atMost2Dec(total));
        }
        else { // if only total is filled, calculate price
          let quantity = total / price;
          $("#product-quantity-input").val(atMost2Dec(quantity));
        }
      }

      function calcQtyPrice(element){
        let total = element.value;
        if (total == ""){
          $("#product-quantity-input").val("");
          return;
        }
        let quantity = $("#product-quantity-input").val();
        let price = $("#product-price-input").val();

        if (quantity == "" && price == "") return;
        // calculate quantity (not the price) when both fields are filled
        if (quantity != "" && price != "" || price != ""){
          let quantity = total / price;
          $("#product-quantity-input").val(atMost2Dec(quantity));
        }
        else { // if only quantity is filled, calculate price
          let price = total / quantity;
          $("#product-price-input").val(atMost2Dec(price));
        }
      }

      function getPrice(element){
        let product = element.value;
        if (product == ""){
          $("#product-quantity-input").val("");
          $("#product-price-input").val("");
          $("#product-subtotal-input").val("");
        }
        else {
          if ($("#product-quantity-input").val() == ""){ // default quantity if quantity not specified
            $("#product-quantity-input").val(1);
          }
          if (products.includes(product)){ // if product exists, query database for price
            $.ajax({
              url: "get-price.php",
              type: "POST", 
              data: {product: product},
              success: function(data){
              $("#product-price-input").val(data);
              calcQtyTotal();
              }
            });
          }
        }
      }

      function addCartItem(){
        // get input values
        let productName = $("#product-name-input").val();
        let productQty = $("#product-quantity-input").val();
        let productPrice = $("#product-price-input").val();
        let productSubTotal = $("#product-subtotal-input").val();

        if (productName != "" && productQty != "" && productPrice != "" && productSubTotal != "" && productSubTotal != 0){ // productSubTotal != 0, in here both operands are converted to numbers
          // limit values to 2 decimal places
          productQty = atMost2Dec($("#product-quantity-input").val());
          productPrice = atMost2Dec($("#product-price-input").val());
          productSubTotal = atMost2Dec($("#product-subtotal-input").val());

          // create cart item element
          let item = document.createElement("div");
          item.classList.add("cart-item");
          item.classList.add("cart-article");

          // create span element for each input value and append to cart item element
          let pName = document.createElement("span");
          pName.textContent = productName;
          item.appendChild(pName)
          let pQty = document.createElement("span");
          pQty.textContent = productQty;
          item.appendChild(pQty)
          let pPrice = document.createElement("span");
          pPrice.textContent = productPrice;
          item.appendChild(pPrice)
          let pSubT = document.createElement("span");
          pSubT.textContent = productSubTotal;
          item.appendChild(pSubT)

          // add del button to cart item element
          let rmButton = document.createElement("button");
          rmButton.type = "button";
          rmButton.id = "remove-button";
          rmButton.classList.add("button");
          rmButton.classList.add("material-icons");
          rmButton.textContent = "remove";
          rmButton.title = "remove"
          rmButton.onclick = function(){
            let cartItem = this.parentElement; // vs parentNode which returns Document node when no parent is found (ex. parent element of <html>), parentElement returns null in that case
            // remove subtotal from grand total
            grandTotal -= Number(cartItem.querySelector("span:nth-of-type(4)").textContent); // 4th span contains subtotal use Number() since textContent is NaN
            document.getElementById("grand-total").textContent = "₱\ " + atMost2Dec(grandTotal);
            // then finally remove cart item
            cartItem.remove();
          }
          item.appendChild(rmButton);

          // put cart item element in cart list element
          let cart = document.getElementById("cart-list");
          cart.appendChild(item);

          // clear fields, ready for next input
          $("#product-name-input").val("");
          $("#product-quantity-input").val("");
          $("#product-price-input").val("");
          $("#product-subtotal-input").val("");

          // add subtotal to grand total
          grandTotal += Number(productSubTotal); // textContent is NaN
          document.getElementById("grand-total").textContent = "₱\ " + atMost2Dec(grandTotal);
        }
      }

      function updateNotes(element){
        let notes = element.value;
        $.ajax({
          url: "update-note.php", 
          type: "POST",
          data: {notes: notes},
        });        
      }

      function fetchCustomerInfo(){
        $.ajax({
          url: "fetch-customer-info.php",
          type: "POST",
          data: {customer: customer},
          success: function(data){
            $("#customer-profile-info-div").html(data);
            document.getElementById("customer-credit").textContent = "₱\ " + document.getElementById("customer-credit").textContent;
          }
        });
      }

      function fetchNotes(){
        $.ajax({
          url: "update-note.php", 
          type: "POST"
        });        
      }

      function fetchProducts(){
        $.ajax({
          url: "fetch-products.php",
          success: function(data){
            $("#product-list").html(data);
            prepVarsAndDisplay();
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
        let currentCredit = Number(document.getElementById("customer-credit").textContent.substr(2).replace(/,/g, ''));
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
                .attr("y", function(d, i) { if (i == 2) return y((currentCredit > d)? d : currentCredit); else return y(d); })
                .attr("width", xSub.bandwidth())
                .attr("height", function(d, i) { if (i == 2) return graphHeight - y((currentCredit > d)? d : currentCredit); else return graphHeight - y(d); })
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

      function showTabContent() {
        let tab = $("#tab-section > .selected-navbar-item").text();
        if (tab == "CREDIT") {
          document.querySelector("#tab-content > div:nth-of-type(1)").style.display = "flex";
          document.querySelector("#tab-content > div:nth-of-type(2)").style.display = "none";
          document.querySelector("#tab-content > div:nth-of-type(3)").style.display = "none";
          document.querySelector("#tab-content > div:nth-of-type(4)").style.display = "none";
        }
        else if (tab == "PAYMENT"){
          document.querySelector("#tab-content > div:nth-of-type(1)").style.display = "none";
          document.querySelector("#tab-content > div:nth-of-type(2)").style.display = "flex";
          document.querySelector("#tab-content > div:nth-of-type(3)").style.display = "none"; 
          document.querySelector("#tab-content > div:nth-of-type(4)").style.display = "none";
        }
        else if (tab == "HISTORY") {
          fetchHistory();   
          document.querySelector("#tab-content > div:nth-of-type(1)").style.display = "none";
          document.querySelector("#tab-content > div:nth-of-type(2)").style.display = "none";
          document.querySelector("#tab-content > div:nth-of-type(3)").style.display = "flex";  
          document.querySelector("#tab-content > div:nth-of-type(4)").style.display = "none";   
        }
        else if (tab == "REPORTS") {
          generateReport();    
          document.querySelector("#tab-content > div:nth-of-type(1)").style.display = "none";
          document.querySelector("#tab-content > div:nth-of-type(2)").style.display = "none";
          document.querySelector("#tab-content > div:nth-of-type(3)").style.display = "none";  
          document.querySelector("#tab-content > div:nth-of-type(4)").style.display = "flex";  
        }
      }

      function switchTab(element){
        document.querySelector("#tab-section > .selected-navbar-item").classList.remove("selected-navbar-item");
        element.classList.add("selected-navbar-item");
        showTabContent();
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

      function prepVarsAndDisplay(){
        let productList = document.getElementById("product-list"); // get datalist element 
        let productListOptions = productList.getElementsByTagName("option"); // get option elements under datalist element
        for (let i = 0; i < productListOptions.length; i++){
          let p = productListOptions[i].textContent;
          products.push(p);
        }
        document.getElementById("grand-total").textContent = "₱\ " + grandTotal;
        showTabContent();
        fetchHistory();
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

      $(document).ready(function () {
        $("#copyright").html("Copyright " + "&copy; " + new Date().getFullYear() + " Credit Pad");
        customer = $("#customer-profile-info-div").data("name");
        fetchCustomerInfo();
        fetchNotes();
        fetchProducts();
        prepareFrames();
      });
    </script>
  </body>
</html>
