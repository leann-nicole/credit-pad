<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Listahan</title>
    <link rel="stylesheet" href="style.css"/>
  </head>
  <body>
    <header>
      <p id="sitename-header"><a href="customers.php">LISTAHAN</a></p>
      <a href="logout.php"><div id="logoutIcon"></div></a>
    </header>
    <div id="content">
      <nav>
        <ul>
          <li style="background-color: <?php if(basename($_SERVER['PHP_SELF']) == 'customers.php') {echo '#505050';} ?>"><a href="customers.php" style="color: <?php if(basename($_SERVER['PHP_SELF']) == 'customers.php') {echo '#ffff7d';} ?>">CUSTOMERS</a></li>
          <li style="background-color: <?php if(basename($_SERVER['PHP_SELF']) == 'products.php') {echo '#505050';} ?>"><a href="products.php" style="color: <?php if(basename($_SERVER['PHP_SELF']) == 'products.php') {echo '#ffff7d';} ?>">PRODUCTS</a></li>
        </ul>
      </nav>
      <main>
        <div id="create-form-div-c" class="<?php if(!isset($_GET['error']) and !isset($_GET['success'])){echo "hidden-item";}?>">
          <div id="form-name"">CREATE CUSTOMER ACCOUNT</div>
          <p id="error" style="<?php if (isset($_GET['error'])){ echo "visibility:visible";}else { echo "visibility:hidden";}?>">
            <?php 
              if (isset($_GET['error'])){ echo $_GET['error']; } else { echo "account created successfully"; }
            ?>
          </p>        
          <form id="create-form" autocomplete="off" action="validate_new_account.php" method="post">
            <div class="form-column">
              <p class="field-name">name</p>
              <input class="field" type="text" name="username" maxlength="50" value="<?php if(isset($_SESSION['cusername'])){echo $_SESSION['cusername'];}?>"/>
              <p class="field-name">birthdate</p>
              <input class="field" type="date" name="birthdate" value="<?php if(isset($_SESSION['cbirthdate'])){echo $_SESSION['cbirthdate'];}?>"/>
              <p class="field-name">sex</p>
              <select name="sex" class="field">
                <option value="m" <?php if(isset($_SESSION['csex']) and $_SESSION['csex'] == 'm'){ echo "selected";} ?>>male</option>
                <option value="f" <?php if(isset($_SESSION['csex']) and $_SESSION['csex'] == 'f'){ echo "selected";} ?>>female</option>
              </select>
            </div>
            
            <div class="form-column">
              <p class="field-name">mobile number</p>
              <input class="field" type="text" name="mobile_no" maxlength="11" value="<?php if(isset($_SESSION['cmobile_no'])){echo $_SESSION['cmobile_no'];}?>"/>
              <p class="field-name">email address</p>
              <input class="field" type="text" name="email" maxlength="100" value="<?php if(isset($_SESSION['cemail'])){echo $_SESSION['cemail'];}?>"/>
              <p class="field-name">home address</p>
              <input class="field" type="text" name="address" maxlength="100" value="<?php if(isset($_SESSION['caddress'])){echo $_SESSION['caddress'];}?>"/>
            </div>
            <div id="form-buttons-div">
              <div id="cancel" class="button" onclick="showHide()">CANCEL</div>
              <button type="submit" form="create-form" id="save" class="button" >SAVE</button>
            </div>

          </form>

          <div id="rating-div">
            <p class="field-name" id="rating-field-name">rating</p>           
            <div id="rating">
              <input form="create-form" type="radio" id="star5" name="rate" value="5" <?php if(isset($_SESSION['crate']) and $_SESSION['crate'] == 5){ echo "checked";}?>/>
              <label for="star5">&#128970;</label>
              <input form="create-form" type="radio" id="star4" name="rate" value="4" <?php if(isset($_SESSION['crate']) and $_SESSION['crate'] == 4){ echo "checked";}?>/>
              <label for="star4">&#128970;</label>
              <input form="create-form" type="radio" id="star3" name="rate" value="3" <?php if(isset($_SESSION['crate']) and $_SESSION['crate'] == 3){ echo "checked";}?>/>
              <label for="star3">&#128970;</label>
              <input form="create-form" type="radio" id="star2" name="rate" value="2" <?php if(isset($_SESSION['crate']) and $_SESSION['crate'] == 2){ echo "checked";}?>/>
              <label for="star2">&#128970;</label>
              <input form="create-form" type="radio" id="star1" name="rate" value="1" <?php if(isset($_SESSION['crate']) and $_SESSION['crate'] == 1){ echo "checked";}?>/>
              <label for="star1">&#128970;</label> 
            </div>
          </div>
        </div>
        <div id="tools">
          <div id="add" class="button" onclick="showHide()"><p></p></div>
          <div id="search-div">
            <input type="text" name="search-value" id="search-field" class="field" onkeyup="filterList()">
            <div id="search-icon"></div>
          </div>
        </div>
        <div id="list"></div>
      </main>
    </div>
    <footer></footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
      // show list of customers under user
      function loadCustomers() {
            $.ajax({
            url: "load-customers.php",
            type: "POST",
            success: function (data) {
                $("#list").html(data);
            }
            });
        }

        function sortCustomers(element) {
            let ccolname = element.getAttribute("data-colname");
            $.ajax({
              url: "load-customers.php",
              type: "POST",
              data: {ccolname: ccolname},
              success: function (data) {
                  $("#list").html(data);
              }
            });
      }


      $(document).ready(function () {
        loadCustomers();
      });

      // show or hide form
      function showHide(){
            document.getElementById("create-form-div-c").classList.toggle("hidden-item");
            document.getElementById("error").style.visibility = "hidden";
            $("input").val("");
            $("textarea").val("");
      }

      function filterList(){
        let input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("search-field");
        filter = input.value.toLowerCase();
        table = document.getElementById("list-table");
        tr = table.getElementsByTagName("tr");

        for (i = 0; i < tr.length; i++){
          td = tr[i].getElementsByTagName("td")[0];
          if (td){
            textValue = td.textContent || td.innerText;
            if (textValue.toLowerCase().indexOf(filter) > -1){
              tr[i].style.display = "";
            }
            else {
              tr[i].style.display = "none";
            }            
          }
        }
      }
    </script>
  </body>
</html>
