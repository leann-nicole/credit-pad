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
      <a href="logout.php"><span id="username"><?php echo $_SESSION["username"]; ?></span></a>
    </header>
    <div id="content">
      <nav>
        <ul>
          <li style="background-color: #d9d9d9;"><a href="customers.php">CUSTOMERS</a></li>
          <li><a href="products.php">PRODUCTS</a></li>
        </ul>
      </nav>
      <main>
        <div id="customer-profile-info-div" class="container" data-name="<?php echo $_GET['customer'];?>">
          
        </div>
        <div id="tab-section">
            <div>CREDIT</div>
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
              <div id="dummy-ok-button" class="button"></div>
            </div>
            <div id="cart-list-div">
              <div id="cart-list"></div>
              <div id="cart-input-div" class="cart-article">
                <input id="product-name" class="field" list="product-list" oninput="getPrice(this)">
                  <datalist id="product-list"></datalist>
                <input id="product-quantity"  class="field" type="number" min="0" oninput="calcTotal()"> 
                <input id="product-price" class="field" type="number" oninput="calcTotal()">
                <input id="product-subtotal" class="field" type="number" oninput="calcQuantity(this)">
                <div id="ok-button" class="button material-icons" onclick="addCartItem()">add</div>
              </div>
            </div>
            <div id="save-transaction-div">
              <input type="date" id="transaction-date" class="field" value="<?php echo date('Y-m-d'); ?>">
              <div id="save-transaction-button" class="button">SAVE</div>
              <div id="grand-total"></div>
            </div>
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
    <footer></footer>
    <script type="text/javascript" src="jquery.js"></script>
    <script>
      // global variables
      let products = []; // array for storing textcontent of option elements
      let grandTotal = 0;
      function calcQuantity(element){
        let total = element.value;
        let price = $("#product-price").val();
        $("#product-quantity").val(total/price);
      }

      function calcTotal(){
        let quantity = $("#product-quantity").val();
        let price = $("#product-price").val();
        if (quantity != "" && price != ""){
          let total = quantity * price;
          $("#product-subtotal").val(total);
        }
      }

      function getPrice(element){
        let product = element.value;
        if (product == ""){
          $("#product-quantity").val("");
          $("#product-price").val("");
          $("#product-subtotal").val("");
        }
        else {
          if ($("#product-quantity").val() == ""){ // default quantity if quantity not specified
            $("#product-quantity").val(1);
          }
          if (products.includes(product)){ // if product exists, query database for price
            $.ajax({
              url: "get-price.php",
              type: "POST", 
              data: {product: product},
              success: function(data){
              $("#product-price").val(data);
              calcTotal();
              }
            });
          }
        }
      }


      function addCartItem(){
        // get input values
        let productName = $("#product-name").val();
        let productQty = $("#product-quantity").val();
        let productPrice = $("#product-price").val();
        let productSubTotal = $("#product-subtotal").val();

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
          let rmButton = document.createElement("div");
          rmButton.id = "remove-button";
          rmButton.classList.add("button");
          rmButton.classList.add("material-icons");
          rmButton.textContent = "remove";
          rmButton.title = "remove"
          rmButton.onclick = function(){
            let cartItem = this.parentElement; // vs parentNode which returns Document node when no parent is found (ex. parent element of <html>), parentElement returns null in that case
            document.getElementById("cart-list").removeChild(cartItem);
          }
          item.appendChild(rmButton);

          // put cart item element in cart list element
          let cart = document.getElementById("cart-list");
          cart.appendChild(item);

          // clear fields, ready for next input
          $("#product-name").val("");
          $("#product-quantity").val("");
          $("#product-price").val("");
          $("#product-subtotal").val("");

          // add subtotal to grand total
          grandTotal += Number(productSubTotal);
          document.getElementById("grand-total").textContent = grandTotal;
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
