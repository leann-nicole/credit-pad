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
        <div id="create-form-div-c" class="<?php if (
            !isset($_GET['error']) and !isset($_GET['success'])
        ) {
            echo 'hidden-item';
        } ?> container">
          <div class="form-name">CREATE CUSTOMER ACCOUNT</div>
          <form id="create-form" autocomplete="off" action="validate-new-account.php" method="post">
            <div class="form-column">
              <label for="cname" class="field-name">name</label>
              <input id="cname" class="field" type="text" name="username" maxlength="50" value="<?php if (
                  isset($_SESSION['cusername'])
              ) {
                  echo $_SESSION['cusername'];
              } ?>" required/>
              <label for="cbday" class="field-name">birthdate</label>
              <input id="cbday" class="field" type="date" name="birthdate" value="<?php if (
                  isset($_SESSION['cbirthdate'])
              ) {
                  echo $_SESSION['cbirthdate'];
              } ?>" required/>
              <label for="cgender" class="field-name">sex</label>
              <select id="cgender" name="sex" class="field">
                <option value="m" <?php if (
                    isset($_SESSION['csex']) and
                    $_SESSION['csex'] == 'm'
                ) {
                    echo 'selected';
                } ?>>male</option>
                <option value="f" <?php if (
                    isset($_SESSION['csex']) and
                    $_SESSION['csex'] == 'f'
                ) {
                    echo 'selected';
                } ?>>female</option>
              </select>
            </div>
            
            <div class="form-column">
              <label for="cphone" class="field-name">mobile number</label>
              <input id="cphone" class="field" type="text" name="mobile_no" maxlength="11" value="<?php if (
                  isset($_SESSION['cmobile_no'])
              ) {
                  echo $_SESSION['cmobile_no'];
              } ?>" required/>
              <label for="cmail" class="field-name">email address</label>
              <input id="cmail" class="field" type="email" name="email" maxlength="100" value="<?php if (
                  isset($_SESSION['cemail'])
              ) {
                  echo $_SESSION['cemail'];
              } ?>" placeholder="optional"/>
              <label for="chome" class="field-name">home address</label>
              <input id="chome" class="field" type="text" name="address" maxlength="100" value="<?php if (
                  isset($_SESSION['caddress'])
              ) {
                  echo $_SESSION['caddress'];
              } ?>" required/>
            </div>
            <div id="form-buttons-div">
              <button type="button" id="cancel" class="gray-button" onclick="showHide()">Cancel</button>
              <button type="submit" form="create-form" id="save-form-button" class="button save-button" >Save</button>
            </div>

          </form>

          <div id="rating-div">
            <label for="rating" class="field-name" id="rating-field-name">rating</label>           
            <div id="rating">
              <input form="create-form" type="radio" id="star5" class="star" name="rate" value="5" <?php if (
                  isset($_SESSION['crate']) and
                  $_SESSION['crate'] == 5
              ) {
                  echo 'checked';
              } ?>/>
              <label for="star5">&#128970;</label>
              <input form="create-form" type="radio" id="star4" class="star" name="rate" value="4" <?php if (
                  isset($_SESSION['crate']) and
                  $_SESSION['crate'] == 4
              ) {
                  echo 'checked';
              } ?>/>
              <label for="star4">&#128970;</label>
              <input form="create-form" type="radio" id="star3" class="star" name="rate" value="3" <?php if (
                  isset($_SESSION['crate']) and
                  $_SESSION['crate'] == 3
              ) {
                  echo 'checked';
              } ?>/>
              <label for="star3">&#128970;</label>
              <input form="create-form" type="radio" id="star2" class="star" name="rate" value="2" <?php if (
                  isset($_SESSION['crate']) and
                  $_SESSION['crate'] == 2
              ) {
                  echo 'checked';
              } ?>/>
              <label for="star2">&#128970;</label>
              <input form="create-form" type="radio" id="star1" class="star" name="rate" value="1" <?php if (
                  isset($_SESSION['crate']) and
                  $_SESSION['crate'] == 1 or !isset($_SESSION['crate'])
              ) {
                  echo 'checked';
              } ?>/>
              <label for="star1">&#128970;</label> 
            </div>
          </div>
        </div>
        <div id="list-div" class="container">
          <div id="tools">
            <button type="button" id="new-button" class="button create-button material-icons" onclick="showHide()">add<span>New</span></button>
            <div id="search-div">
              <input type="text" id="search-field" class="field" placeholder="Search" onkeyup="filterList()">
              <span id="search-icon" class="material-icons" onclick="focusSearchBar()">search</span>
            </div>
          </div>
          <div id="list-inner-div">
          
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
    <script type="text/javascript" src="jquery.js"></script>
    <script>

      $(document).click(function(){
        if (!$("#dropdown-menu").hasClass("hidden-item")) {
          document.getElementById("dropdown-menu").classList.add("hidden-item");
          $("#dropdown-button span").text("arrow_drop_down");
        }
      });

      $("#dropdown-button").click(function(e){ // ignore clicks inside delete item popup
        e.stopPropagation();
      });
      
      $("#dropdown-menu a").click(function(e){ // ignore clicks inside delete item popup
        e.stopPropagation();
      });

      function toggleAccountOptions(){
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

      function focusSearchBar(){
        $("#search-field").focus();
      }

      // show list of customers belonging to the current store owner
      function loadCustomers() {
            // will be using ajax here, ajax allows you to connect to a server in the background, that is without reloading the page
            // syntax: $.ajax({});
            // creates XMLHttpRequest object
            // response from server is processed by JavaScript
            // AJAX: Asynchronous JavaScript and XML
            $.ajax({
            url: "load-customers.php",
            type: "POST",
            success: function (data) {
                $("#list-inner-div").html(data);
                filterList();
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
              $("#list-inner-div").html(data);
              filterList();
              let selector = "#" + element.id + " span";
              let arrow = element.getElementsByTagName("span")[0].textContent;
              (arrow == "arrow_drop_down")? $(selector).text("arrow_drop_up") : $(selector).text("arrow_drop_down");
          }
        });
      }

      $(document).ready(function () {
        $("#copyright").html("Copyright " + "&copy; " + new Date().getFullYear() + " Credit Pad");
        loadCustomers();
        fetchNotes();
      });
      // shorthand for $(document).ready(); is $();
      // can also do $(window).on("load", function(){}); 
      // that is if you want code inside to run once entire page is ready, not just DOM

      // show or hide form
      function showHide(){
            document.getElementById("create-form-div-c").classList.toggle("hidden-item");
            document.getElementById("error").classList.add("hidden-item"); // can also do document.getElementById("error").setAttribute("style","visibility: hidden;"); but is considered bad practice since it will overwrite properties which may already be specified in the style attribute
            $("#create-form input[type='text']").val("");
            $("input[type='date']").val("");
            $("select[name='sex'] option").prop("selected", false);
            $("input[name='rate']:radio").prop("checked", false);
            $("#star1").prop("checked", true);
      }

      function filterList(){
        if ($("table").attr("id") == "no-record-header") return;
        let searchInput = document.getElementById("search-field").value.toLowerCase(); // get search bar and value in it
        let tableRows = document.getElementById("customer-list-table").getElementsByTagName("tr"); // get table and rows in it

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

      function selectCustomer(element){
        let customer = element.getElementsByTagName("td")[0].textContent;
        let url = "customer-account.php?customer=" + customer;
        window.location.href=url;
      }
    </script>
  </body>
</html>
