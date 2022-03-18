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
      <a href="logout.php"><span id="username"><?php echo $_SESSION[
          'username'
      ]; ?></span></a>
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
        <div id="customer-profile-info-div" class="container" data-name="<?php echo $_GET['customer']; ?>">
          
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
            <div id="save-credit-div">
              <textarea id="credit-comment" class="field" placeholder="Write a comment here" spellcheck="false" maxlength="500"></textarea>
              <input type="date" id="credit-date" class="field" value="<?php echo date(
                  'Y-m-d'
              ); ?>">
              <button type="button" id="save-credit-button" class="button save-button" onclick="saveCredit()">Save</button>
              <div id="grand-total"></div>
            </div>
          </div>
          <div id="payment-div">
              <div id="payment-types-div">
                <div id="full-payment" class="payment-type selected-payment-type" onclick="selectPayment(this)">
                  <span class="material-icons payment-check-mark">check_circle</span>
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
                  <span class="material-icons payment-check-mark">check_circle</span>
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
            <div id="save-payment-div">
              <textarea id="payment-comment" class="field" placeholder="Write a comment here" spellcheck="false"></textarea>
              <input type="date" id="payment-date" class="field" value="<?php echo date(
                  'Y-m-d'
              ); ?>">
              <button type="button" id="save-payment-button" class="button save-button" onclick="savePayment()">Save</button>
            </div>
          </div>
          <div id="history-div">
            <div id="history-tools">
              <div id="payments" class="button selected-history-type" onclick="filterHistory(this)">Payments</div>
              <div id="credits" class="button selected-history-type" onclick="filterHistory(this)">Credits</div>
              <button type="button" id="sort-history-button" class="button" onclick="fetchHistory(this)">Most Recent First</button>
              <input type="date" id="endDate" class="field" title="end date" onchange="filterHistory(this)">
              <p>~</p>
              <input type="date" id="startDate" class="field" title="start date" onchange="filterHistory(this)">
            </div>
            <div id="history-list"></div>
          </div>
          <div id="reports-div">reports</div>
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
    <footer></footer>
    <script type="text/javascript" src="jquery.js"></script>
    <script>
      // global variables
      let products = []; // array for storing textcontent of option elements
      let grandTotal = 0;
      let customer = "";
      let entryNo = 0;
      let historyOrder = "Most Recent First";

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

      function filterHistory(element){
        let historyItems = document.querySelectorAll(".history-item");
        let toFilterByType = [];
        for (let i = 0; i < historyItems.length; i++) toFilterByType.push(i);

        // to step process
        // filter by date first
        // this stage also resets previous filters by setting the display of any date matched item to flex
        // filter by type will take place right after
        let startDate = $("#startDate").val();
        let endDate = $("#endDate").val();
        
        if (startDate == "" && endDate == ""){ // default, show all
          historyItems.forEach(function (item) {
            item.style.display = "flex";
          })
        }
        else if (startDate != "" && endDate == ""){ // one date provided, exact match needed
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
        else if (startDate == "" && endDate != ""){ // one date provided, exact match needed
          let dateToMatch = endDate;
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
        if (element != undefined && element.tagName == "DIV") element.classList.toggle("selected-history-type");
        
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
        if (!grandTotal || transactionDate == "") { return; }
      
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
            let commentSaved = false;

            // go through cart, individually saving each item to the database
            Array.from(cartItems).forEach(function(item, index, arr){
              // get cart item information
              let product = item.querySelector("span:nth-of-type(1)").textContent;
              let quantity = Number(item.querySelector("span:nth-of-type(2)").textContent);
              let price = Number(item.querySelector("span:nth-of-type(3)").textContent);
              let subTotal = Number(item.querySelector("span:nth-of-type(4)").textContent);
    
              // save to database
              if (!commentSaved){
                $.ajax({
                  url: "save-credit.php",
                  type: "POST",
                  data: {customer: customer, product: product, quantity: quantity, price: price, subTotal: subTotal, grandTotal: grandTotal, creditDate: creditDate, entryNo: entryNo, comment: comment}
                });
                commentSaved = true;
              }
              else {
                $.ajax({
                  url: "save-credit.php",
                  type: "POST",
                  data: {customer: customer, product: product, quantity: quantity, price: price, subTotal: subTotal, grandTotal: grandTotal, creditDate: creditDate, entryNo: entryNo}
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
              cash = atMost2Dec(cash)
              let amountPaid = Number($("#customer-credit").text().substr(2).replace(/,/g, ""));
              if (cash < amountPaid){ return; }
              let change = atMost2Dec(cash - amountPaid);
              let paymentDate = $("#payment-date").val();
              let comment = $("#payment-comment").val();

              $.ajax({
              url: "save-payment.php",
              type: "POST",
              data: {paymentType: "full payment", customer: customer, paymentDate: paymentDate, cash: cash, amountPaid: amountPaid, change: change, comment: comment, entryNo: entryNo}
              });         

              // clear inputs and comments & update current credit
              $("#full-payment input").val("");
              $("#payment-comment").val("");
              $("#change1").text("change:");
              let newCustomerCredit = atMost2Dec(currentCredit - amountPaid); 
              document.getElementById("customer-credit").textContent = "₱\ " + newCustomerCredit.toLocaleString();
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

              $.ajax({
              url: "save-payment.php",
              type: "POST",
              data: {paymentType: "partial payment", customer: customer, paymentDate: paymentDate, cash: cash, amountPaid: amountPaid, change: change, comment: comment, entryNo: entryNo}
              });

              $("#partial-payment input").val("");
              $("#payment-comment").val("");
              $("#change2").text("change:");
              let newCustomerCredit = atMost2Dec(currentCredit - amountPaid);
              document.getElementById("customer-credit").textContent = "₱\ " + newCustomerCredit.toLocaleString();
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
          document.querySelector("#tab-content > div:nth-of-type(1)").style.display = "none";
          document.querySelector("#tab-content > div:nth-of-type(2)").style.display = "none";
          document.querySelector("#tab-content > div:nth-of-type(3)").style.display = "flex";  
          document.querySelector("#tab-content > div:nth-of-type(4)").style.display = "none";   
          fetchHistory();   
        }
        else if (tab == "REPORTS") {
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
            $("#sort-history-button").text("Least Recent First");
          }
          else {
            historyOrder = "Most Recent First";
            $("#sort-history-button").text("Most Recent First");
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

      $(document).ready(function () {
        customer = $("#customer-profile-info-div").data("name");
        fetchCustomerInfo();
        fetchNotes();
        fetchProducts();
      });
    </script>
  </body>
</html>
