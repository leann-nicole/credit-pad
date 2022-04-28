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
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <p id="error" class="<?php if (
        !isset($_GET['error']) &&
        !isset($_GET['error-edit'])
    ) {
        echo 'hidden-item';
    } ?>">
        <?php if (isset($_GET['error'])) {
            echo $_GET['error'];
        } elseif (isset($_GET['error-edit'])) {
            echo $_GET['error-edit'];
        } ?>
    </p>        
    <header>
      <p id="sitename-header"><a href="customers.php">CREDIT PAD</a></p>
      <div id="dropdown">
        <button type="button" id="dropdown-button" class="material-icons" onclick="toggleAccountOptions()">storefront<span class="material-icons">arrow_drop_down</span></button>
        <div id="dropdown-menu" class="hidden-item">
          <a href="profile.php">Profile</a>
          <a href="logout.php">Log out</a>
        </div>
      </div>
    </header>
    <div id="content">
      <nav>
        <ul>
          <li><a href="customers.php">CUSTOMERS</a></li>
          <li class="selected-navbar-item"><a href="products.php">PRODUCTS</a></li>
          <li><a href="reports.php">REPORTS</a></li>
        </ul>
      </nav>
      <main>
        <div id="create-form-div-p" class="<?php if (
            !isset($_GET['error']) and !isset($_GET['success'])
        ) {
            echo 'hidden-item';
        } ?> container">
          <div class="form-name">ADD NEW PRODUCT</div>
          <form id="create-form" autocomplete="off" action="validate-new-product.php" method="post">
            <div class="form-column">
              <label for="product-name" class="field-name">name</label>
              <input id="product-name" class="field" type="text" name="product" maxlength="50" value="<?php if (
                  isset($_SESSION['product'])
              ) {
                  echo $_SESSION['product'];
              } ?>" required/>
              <label for="product-description" class="field-name">description</label>
              <textarea id="product-description" class="field" name="description" maxlength="250" spellcheck="false" placeholder="optional"><?php if (
                  isset($_SESSION['description'])
              ) {
                  echo $_SESSION['description'];
              } ?></textarea>

              <div id="category-price-div">
                <div id="cp-category">
                  <label for="category" class="field-name">category</label>
                    <input id="category" type="text" class="field" list="categories" maxlength="30" name="category" value="<?php if (
                        isset($_SESSION['fcategory'])
                    ) {
                        echo $_SESSION['fcategory'];
                    } ?>" placeholder="optional"/>
                      <datalist id="categories"></datalist>
                </div>
                <div id="cp-price">
                  <label for="price"class="field-name">price</label>
                  <input class="field" type="number" id="price" name="price" min="1" step="0.01" value="<?php if (
                      isset($_SESSION['fprice'])
                  ) {
                      echo $_SESSION['fprice'];
                  } ?>" required/>
                </div>                
              </div>
              
            </div>
            <div id="form-buttons-div">
              <button type="button" id="cancel" class="gray-button" onclick="toggleCreateForm()">Cancel</button>
              <button type="submit" form="create-form" id="save-form-button" class="button save-button">Save</button>
            </div>

          </form>
        </div>
<!-- edit form -->
        <div id="edit-form-div-p" class="<?php if (
            !isset($_GET['error-edit'])
        ) {
            echo 'hidden-item';
        } ?> container">
          <div class="form-name">EDIT PRODUCT</div>
          <form id="edit-form" autocomplete="off" action="validate-edit-product.php" method="post">
            <input id="product-name-copy" class="field hidden-item" type="text" form="edit-form" name="current_product_name" value="<?php if (
                isset($_GET['product'])
            ) {
                echo $_GET['product'];
            } ?>">
            <div class="form-column">
              <label for="product-name-edit" class="field-name">name</label>
              <input id="product-name-edit" class="field" type="text" name="product" maxlength="50" value="<?php if (
                  isset($_SESSION['product-edit'])
              ) {
                  echo $_SESSION['product-edit'];
              } ?>" required/>
              <label for="product-description-edit" class="field-name">description</label>
              <textarea id="product-description-edit" class="field" name="description" maxlength="250" spellcheck="false" placeholder="optional"><?php if (
                  isset($_SESSION['description-edit'])
              ) {
                  echo $_SESSION['description-edit'];
              } ?></textarea>

              <div id="category-price-div-edit">
                <div id="cp-category-edit">
                  <label for="category-edit" class="field-name">category</label>
                    <input id="category-edit" type="text" class="field" list="categories-edit" maxlength="30" name="category" value="<?php if (
                        isset($_SESSION['category-edit'])
                    ) {
                        echo $_SESSION['category-edit'];
                    } ?>" placeholder="optional"/>
                      <datalist id="categories-edit"></datalist>
                </div>
                <div id="cp-price-edit">
                  <label for="price-edit"class="field-name">price</label>
                  <input class="field" type="number" id="price-edit" name="price" min="1" step="0.01" value="<?php if (
                      isset($_SESSION['price-edit'])
                  ) {
                      echo $_SESSION['price-edit'];
                  } ?>" required/>
                </div>                
              </div>
              
            </div>
            <div id="form-buttons-div-edit">
              <button type="button" id="cancel-edit" class="gray-button" onclick="toggleEditForm(this)">Cancel</button>
              <button type="submit" form="edit-form" id="save-form-button-edit" class="button save-button">Save</button>
            </div>

          </form>
          <span id="delete-item-clickable-text" onclick="toggleDeleteItem()">Delete this product</span>
          <div id="deletion-confirmation-popup" class="container hidden-item">
            <span>Are you sure you want to delete this product?</span>
            <span id="product-to-delete"></span>
            <div id="popup-yes-no-div">
              <button id="no-button" class="gray-button" onclick="toggleDeleteItem()">Cancel</button>
              <button id="yes-button" class="button" onclick="deleteItem()">Delete</button>
            </div>
          </div>
        </div>

        <div id="tools">
          <button type="button" id="new-button" class="button create-button material-icons" onclick="toggleCreateForm()">add<span>New</span></button>
          <div id="search-div">
            <input type="text" id="search-field" class="field" placeholder="Search" onkeyup="filterList()">
            <span id="search-icon" class="material-icons" onclick="focusSearchBar()">search</span>
          </div>
        </div>
        <div id="list-div" class="container">
          <div id="list-inner-div">
          
          </div>
          <div id="product-information-popup-div" class="container hidden-item"></div>
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
    <footer><a href="customers.php" id="footer-website-name">Credit Pad</a></footer>
    <script type="text/javascript" src="jquery.js"></script>
    <script>
      // hide product info popup when user clicks anywhere outside it
      $(document).click(function(){
        document.getElementById("product-information-popup-div").classList.add("hidden-item");
        document.getElementById("deletion-confirmation-popup").classList.add("hidden-item");
      });

      $("#product-information-popup-div").click(function(e){ // ignore clicks inside product info popup
        e.stopPropagation(); 
      });

      $("#deletion-confirmation-popup").click(function(e){ // ignore clicks inside delete item popup
        e.stopPropagation();
      });

      $("#delete-item-clickable-text").click(function(e){ // ignore clicks on delete product text
        e.stopPropagation();
      });

      function toggleAccountOptions(){
        $("#dropdown-menu").toggleClass("hidden-item");
        $("#dropdown-menu").toggleClass("container");
        let arrow = $("#dropdown-button span").text();
        (arrow == "arrow_drop_down")? $("#dropdown-button span").text("arrow_drop_up") : $("#dropdown-button span").text("arrow_drop_down");
      }

      function deleteItem(){
        let item = $("#product-to-delete").text();
        $.ajax({
          url: "delete-item.php",
          type: "POST", 
          data: {type: "product", item: item},
          success: function(data){
            if (data == "success"){
              toggleDeleteItem();
              toggleEditForm();
              loadProducts();
            }
            else {
              toggleDeleteItem();
              $("#error").text("failed to delete product");
              document.getElementById("error").classList.remove("hidden-item");
            }
          }
        });
      }

      function toggleDeleteItem(){
        document.getElementById("product-information-popup-div").classList.add("hidden-item"); // open only one popup at a time
        document.getElementById("product-to-delete").textContent = document.getElementById("product-name-copy").value;
        document.getElementById("deletion-confirmation-popup").classList.toggle("hidden-item");
      }

      function toggleCreateForm(){
        document.getElementById("deletion-confirmation-popup").classList.add("hidden-item"); // hide other forms and popups first
        document.getElementById("product-information-popup-div").classList.add("hidden-item");
        document.getElementById("edit-form-div-p").classList.add("hidden-item"); 
        document.getElementById("create-form-div-p").classList.toggle("hidden-item");
        document.getElementById("error").classList.add("hidden-item");
        $("#create-form input").val("");
        $("textarea[id='product-description']").val("");
      }

      function toggleEditForm(element){
        document.getElementById("product-information-popup-div").classList.add("hidden-item");
        document.getElementById("create-form-div-p").classList.add("hidden-item");

        if (element == undefined || element.textContent == "Cancel")
          document.getElementById("edit-form-div-p").classList.add("hidden-item");
        else if (element.textContent == "edit")
          document.getElementById("edit-form-div-p").classList.remove("hidden-item");
        document.getElementById("error").classList.add("hidden-item");
        // current product name
        let currentProductName = $("#product-name-info").text();
        $("#product-name-edit").val(currentProductName);
        $("#product-name-copy").val(currentProductName);
        // current product description
        let currentProductDesc = $("#product-description-info").text().substr(11);
        $("#product-description-edit").val(currentProductDesc);
        // current product category
        let currentProductCategory = $("#product-category-info").text().substr(8);
        $("#category-edit").val(currentProductCategory);
        // current product price
        let currentProductPrice = $("#product-price-info").text().substr(7).replace(/,/g,'');
        $("#price-edit").val(currentProductPrice);
      }

      function focusSearchBar(){
        $("#search-field").focus();
      }

      // show list of products under user
      function loadProducts() { 
        $.ajax({
          url: "load-products.php",
          type: "POST",
          success: function (data) {
              $("#list-inner-div").html(data);
              filterList();
          }
        });
      }

      function sortProducts(element) {
        let pcolname = element.getAttribute("data-colname");
        $.ajax({
          url: "load-products.php",
          type: "POST",
          data: {pcolname: pcolname},
          success: function (data) {
              $("#list-inner-div").html(data);
              filterList();
              let selector = "#" + element.id + " span";
              let arrow = element.getElementsByTagName("span")[0].textContent;
              (arrow == "arrow_drop_down")? $(selector).text("arrow_drop_up") : $(selector).text("arrow_drop_down");
          }
        });
      }

      function loadCategories(){
        $.ajax({
          url: "load-categories.php",
          success: function (data) {
            $("#categories").html(data);
            $("#categories-edit").html(data);
          }
        });
      }

      $(document).ready(function () {
        loadProducts();
        fetchNotes();
        loadCategories();
      });

      function filterList(){
        let searchInput = document.getElementById("search-field").value.toLowerCase(); // get search bar and value in it
        let tableRows = document.getElementById("product-list-table").getElementsByTagName("tr"); // get table and rows in it

        for (let i = 1; i < tableRows.length; i++){ // loop through rows  
          let columns = tableRows[i].getElementsByTagName("td"); // get the items in each row
          let showRow = false; // hide row by default
          for (let j = 0; j < columns.length; j++){ // loop through items in each row to check if there is a match with the searchInput value 
            let item = columns[j].textContent;
            if (item.toLowerCase().indexOf(searchInput) > -1){
              showRow = true;
              break;
            }
          }
          if (!showRow){tableRows[i].style.display = "none";}
          else {tableRows[i].style.display = "";}
        }
      }

      function fetchNotes(){
        $.ajax({
          url: "update-note.php"
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

      function selectProduct(element){
        // show pop-up dialog for editing product details
        let productName = element.getElementsByTagName("td")[0].innerText;
        $.ajax({
          url: "fetch-product-details.php",
          type: "POST",
          data: {productName: productName},
          success: function (data){
            $("#product-information-popup-div").html(data);
            document.getElementById("product-information-popup-div").classList.toggle("hidden-item");
            document.getElementById("deletion-confirmation-popup").classList.add("hidden-item");
          }
        });
      }
    </script>
  </body>
</html>
