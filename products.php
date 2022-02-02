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
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <header>
      <p id="sitename-header"><a href="customers.php">CREDIT PAD</a></p>
      <a href="logout.php"><div id="logoutIcon"></div></a>
    </header>
    <div id="content">
      <nav>
        <ul>
          <li style="background-color: <?php if (
              basename($_SERVER['PHP_SELF']) == 'customers.php'
          ) {
              echo '#505050';
          } ?>"><a href="customers.php" style="color: <?php if (
    basename($_SERVER['PHP_SELF']) == 'customers.php'
) {
    echo '#ffff7d';
} ?>">CUSTOMERS</a></li>
          <li style="background-color: <?php if (
              basename($_SERVER['PHP_SELF']) == 'products.php'
          ) {
              echo '#505050';
          } ?>"><a href="products.php" style="color: <?php if (
    basename($_SERVER['PHP_SELF']) == 'products.php'
) {
    echo '#ffff7d';
} ?>">PRODUCTS</a></li>
        </ul>
      </nav>
      <main>
        <div id="create-form-div-p" class="<?php if (
            !isset($_GET['error']) and !isset($_GET['success'])
        ) {
            echo 'hidden-item';
        } ?>">
          <div id="form-name">ADD NEW PRODUCT</div>
          <p id="error" style="<?php if (isset($_GET['error'])) {
              echo 'visibility:visible';
          } else {
              echo 'visibility:hidden';
          } ?>">
            <?php if (isset($_GET['error'])) {
                echo $_GET['error'];
            } else {
                echo 'product registered successfully';
            } ?>
          </p>        
          <form id="create-form" autocomplete="off" action="validate-new-product.php" method="post">
            <div class="form-column">
              <p class="field-name">name</p>
              <input class="field" type="text" name="product" maxlength="50" value="<?php if (
                  isset($_SESSION['product'])
              ) {
                  echo $_SESSION['product'];
              } ?>"/>
              <p class="field-name">description</p>
              <textarea id="product-description" class="field" name="description" maxlength="200"><?php if (isset($_SESSION['description'])) { echo $_SESSION['description']; } ?></textarea>

              <div id="category-price-div">
                <div id="cp-category">
                  <p class="field-name">category</p>
                    <input type="text" class="field" id="category" list="categories" maxlength="30" name="category" value="<?php if (
                        isset($_SESSION['category'])
                    ) {
                        echo $_SESSION['category'];
                    } ?>"/>
                      <datalist id="categories"></datalist>
                </div>
                <div id="cp-price">
                  <p class="field-name">price</p>
                  <input class="field" type="number" id="price" name="price" min="1" title="" value="<?php if (
                      isset($_SESSION['price'])
                  ) {
                      echo $_SESSION['price'];
                  } ?>"/>
                </div>                
              </div>
              
            </div>
            <div id="form-buttons-div">
              <div id="cancel" class="button" onclick="showHide()">CANCEL</div>
              <button type="submit" form="create-form" id="save" class="button">SAVE</button>
            </div>

          </form>
        </div>
        <div id="tools">
          <div id="add" class="button" onclick="showHide()"><p></p></div>
          <div id="search-div">
            <input type="text" id="search-field" class="field" onkeyup="filterList()">
            <div id="search-icon"></div>
          </div>
        </div>
        <div id="list"></div>
      </main>
    </div>
    <footer></footer>
    <script type="text/javascript" src="jquery.js"></script>
    <script>
      // show list of customers under user
      function loadProducts() { 
            $.ajax({
            url: "load-products.php",
            type: "POST",
            success: function (data) {
                $("#list").html(data);
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
                  $("#list").html(data);
              }
            });
      }

      function loadCategories(){
          $.ajax({
            url: "load-categories.php",
            type: "POST",
            success: function (data) {
              $("#categories").html(data);
            }
          });
        }

      $(document).ready(function () {
        loadProducts();
        loadCategories();
      });

      // show or hide form
      function showHide(){
            document.getElementById("create-form-div-p").classList.toggle("hidden-item");
            document.getElementById("error").style.visibility = "hidden";
            $("input").val("");
            $("textarea").val("");
      }

      function filterList(){
        let searchInput = document.getElementById("search-field").value.toLowerCase(); // get search bar and value in it
        let tableRows = document.getElementById("list-table").getElementsByTagName("tr"); // get table and rows in it

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
    </script>
  </body>
</html>
