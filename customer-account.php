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
        <div id="customer-profile-info-div" class="container" data-name="<?php echo $_GET[
            'customer'
        ]; ?>">
          
        </div>
        <div id="tab-section">
            <div class="selected-navbar-item">CREDIT</div>
            <div>HISTORY</div>
            <div>STATISTICS</div>
        </div>
        <div id="tab-content" class="container">
          <div id="credit-div">
            <div id="cart-labels-div" class="cart-article">
              <span id="product-name-label">PRODUCT</span>
              <span id="product-quantity-label">QUANTITY</span>
              <span id="product-price-label">PRICE</span>
              <span id="product-subtotal-label">SUBTOTAL</span>
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
            <div id="save-transaction-div">
              <input type="date" id="transaction-date" class="field" value="<?php echo date(
                  'Y-m-d'
              ); ?>">
              <button type="button" id="save-transaction-button" class="button save-button" onclick="saveCredit()">SAVE</button>
              <div id="grand-total"></div>
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

      function saveCredit() {
        let cartItems = document.getElementsByClassName("cart-item");
        let transactionDate = $("#transaction-date").val();

        // go through cart, individually saving each item to the database
        Array.from(cartItems).forEach(function(item){
          // get cart item information
          let product = item.querySelector("span:nth-of-type(1)").textContent;
          let quantity = item.querySelector("span:nth-of-type(2)").textContent;
          let price = item.querySelector("span:nth-of-type(3)").textContent;
          let subtotal = item.querySelector("span:nth-of-type(4)").textContent;

          // save to database
          $.ajax({
            url: "save-credit.php",
            type: "POST",
            data: {product: product, quantity: quantity, price: price, transactiondDate: transactionDate}
          });
        });

        // empty cart
        let cart = document.getElementById("cart-list");
        while (cart.firstChild){
          cart.removeChild(cart.firstChild);
        }
        // refresh grand total
        grandTotal = 0;
        document.getElementById("grand-total").textContent = grandTotal;
      }

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
        let productQty = atLeast2Dec($("#product-quantity-input").val());
        let productPrice = atLeast2Dec($("#product-price-input").val());
        let productSubTotal = atLeast2Dec($("#product-subtotal-input").val());

        if (productName != "" && productQty != "" && productPrice != "" && productSubTotal != ""){
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
            document.getElementById("grand-total").textContent = atLeast2Dec(grandTotal);
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
          document.getElementById("grand-total").textContent = atLeast2Dec(grandTotal);
        }
      }

      function fetchCustomerInfo(name){
        $.ajax({
          url: "fetch-customer-info.php",
          type: "POST",
          data: {name: name},
          success: function(data){
            $("#customer-profile-info-div").html(data);
          }
        });
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

      function fetchProducts(){
        $.ajax({
          url: "fetch-products.php",
          success: function(data){
            $("#product-list").html(data);
            prepareGlobalVars();
          }
        });
      }

      function prepareGlobalVars(){
        let productList = document.getElementById("product-list"); // get datalist element 
        let productListOptions = productList.getElementsByTagName("option"); // get option elements under datalist element
        for (let i = 0; i < productListOptions.length; i++){
          let p = productListOptions[i].textContent;
          products.push(p);
        }
        document.getElementById("grand-total").textContent = grandTotal;
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
