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
        </ul>
      </nav>
      <main>
        <div id="customer-profile-info-div" class="container" data-name="<?php echo $_GET['customer']; ?>">
          
        </div>
        <div id="tab-section">
            <div class="selected-navbar-item" onclick="switchTab(this)">CREDIT</div>
            <div onclick="switchTab(this)">PAYMENT</div>
            <div onclick="switchTab(this)">HISTORY</div>
            <div onclick="switchTab(this)">STATISTICS</div>
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
                <input id="product-quantity-input"  class="field" type="number" min="0" oninput="calcTotal()"> 
                <input id="product-price-input" class="field" type="number" oninput="calcTotal()">
                <input id="product-subtotal-input" class="field" type="number" oninput="calcQuantity(this)">
                <button type="button" id="add-button" class="button material-icons" onclick="addCartItem()" title="add">add</button>
              </div>
            </div>
            <div id="save-credit-div">
              <textarea id="credit-comment" class="field" placeholder="Write a comment here" maxlength="500"></textarea>
              <input type="date" id="credit-date" class="field" value="<?php echo date(
                  'Y-m-d'
              ); ?>">
              <button type="button" id="save-credit-button" class="button save-button" onclick="saveCredit()">SAVE</button>
              <div id="grand-total"></div>
            </div>
          </div>
          <div id="payment-div">
            <div id="payment-info-div">
              <p class="field-name">Choose type of payment</p>
              <div id="payment-types-div">
                <div id="full-payment" class="payment-type selected-payment-type" onclick="selectPayment(this)">
                  <div class="selection-indicator"></div>
                  <p class="payment-type-name">FULL PAYMENT</p>
                  <div class="payment-calculation-div">
                    <label for="cash-received1">
                      <p class="field-name">cash received</p>
                      <input type="number" min="1" step="0.01" id="cash-received1" class="field">
                    </label>
                  </div>
                  <p id="change1">change:</p>
                </div>   
                <div id="partial-payment" class="payment-type" onclick="selectPayment(this)">
                  <div class="selection-indicator"></div>
                  <p class="payment-type-name">PARTIAL PAYMENT</p>
                  <div class="payment-calculation-div">
                    <label for="cash-received2">
                      <p class="field-name">cash received</p>
                      <input type="number" min="1" step="0.01" id="cash-received2" class="field">
                    </label>
                    <label for="amount-paid2" class="test">
                      <p class="field-name">amount paid</p>
                      <input type="number" min="1" step="0.01" id="amount-paid" class="field">
                    </label>
                  </div>
                  <p id="change2">change:</p>
                </div>
              </div>
            </div>
            <div id="save-payment-div">
              <textarea id="payment-comment" class="field" placeholder="Write a comment here"></textarea>
              <input type="date" id="payment-date" class="field" value="<?php echo date(
                  'Y-m-d'
              ); ?>">
              <button type="button" id="save-payment-button" class="button save-button" onclick="savePayment()">SAVE</button>
              <div id="grand-total"></div>
            </div>
          </div>
          <div id="history-div"></div>
          <div id="statistics-div">statistics</div>
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

      function atLeast2Dec(n){
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

      function selectPayment(element){
        if (!element.classList.contains("selected-payment-type")){
          $("#payment-types-div input").val("");
          let paymentTypes = document.getElementsByClassName("payment-type");
          Array.from(paymentTypes).forEach(function(item){
            item.classList.remove("selected-payment-type");
          });
          element.classList.add("selected-payment-type");
        }
      }

      function saveCredit() {
        if (grandTotal) {
          let cartItems = document.getElementsByClassName("cart-item");
          let creditDate = $("#credit-date").val();
          let customer = $("#customer-profile-info-div").data("name");
          let newCustomerCredit = atLeast2Dec(Number(document.getElementById("customer-credit").textContent.substr(2).replace(/,/g, '')) + grandTotal); // replace() because Number() doesn't process commas. and using replace() with a regular expression /,/g ensures all occurences and not just the first is replaced
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
                data: {customer: customer, product: product, quantity: quantity, price: price, subTotal: subTotal, grandTotal: grandTotal, creditDate: creditDate, comment: comment}
              });
              commentSaved = true;
            }
            else {
              $.ajax({
                url: "save-credit.php",
                type: "POST",
                data: {customer: customer, product: product, quantity: quantity, price: price, subTotal: subTotal, grandTotal: grandTotal, creditDate: creditDate}
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
          document.getElementById("customer-credit").textContent = "₱\ " + newCustomerCredit;
          grandTotal = 0;
          document.getElementById("grand-total").textContent = "₱\ " + grandTotal;
        }
      }

      function savePayment(){}

      function calcQuantity(element){
        let total = element.value;
        let price = $("#product-price-input").val();
        let quantity = total/price;
        $("#product-quantity-input").val(atLeast2Dec(quantity));
      }

      function calcTotal(){
        let quantity = $("#product-quantity-input").val();
        let price = $("#product-price-input").val();
        if (quantity != "" && price != ""){
          let total = quantity * price;
          $("#product-subtotal-input").val(atLeast2Dec(total));
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
              calcTotal();
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

        if (productName != "" && productQty != "" && productPrice != "" && productSubTotal != ""){
          // limit values to 2 decimal places
          productQty = atLeast2Dec($("#product-quantity-input").val());
          productPrice = atLeast2Dec($("#product-price-input").val());
          productSubTotal = atLeast2Dec($("#product-subtotal-input").val());

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
            document.getElementById("grand-total").textContent = "₱\ " + atLeast2Dec(grandTotal);
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
          document.getElementById("grand-total").textContent = "₱\ " + atLeast2Dec(grandTotal);
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

      function fetchCustomerInfo(name){
        $.ajax({
          url: "fetch-customer-info.php",
          type: "POST",
          data: {name: name},
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
          let customer = $("#customer-profile-info-div").data("name");
          $.ajax({
            url: "fetch-history.php",
            type: "POST", 
            data: {customer: customer},
            success: function(data){
              document.getElementById("history-div").innerHTML = data;
            }
          });
        }
        else if (tab == "STATISTICS") {
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

      function prepVarsAndDisplay(){
        let productList = document.getElementById("product-list"); // get datalist element 
        let productListOptions = productList.getElementsByTagName("option"); // get option elements under datalist element
        for (let i = 0; i < productListOptions.length; i++){
          let p = productListOptions[i].textContent;
          products.push(p);
        }
        document.getElementById("grand-total").textContent = "₱\ " + grandTotal;
        showTabContent();
      }

      $(document).ready(function () {
        let name = $("#customer-profile-info-div").data("name");
        fetchCustomerInfo(name);
        fetchNotes();
        fetchProducts();
      });
    </script>
  </body>
</html>
