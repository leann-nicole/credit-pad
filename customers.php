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
        <div id="create-form-div-c" class="<?php if (
            !isset($_GET['error']) and !isset($_GET['success'])
        ) {
            echo 'hidden-item';
        } ?>">
          <div id="form-name">CREATE CUSTOMER ACCOUNT</div>
          <form id="create-form" autocomplete="off" action="validate-new-account.php" method="post">
            <div class="form-column">
              <p class="field-name">name</p>
              <input class="field" type="text" name="username" maxlength="50" value="<?php if (
                  isset($_SESSION['cusername'])
              ) {
                  echo $_SESSION['cusername'];
              } ?>"/>
              <p class="field-name">birthdate</p>
              <input class="field" type="date" name="birthdate" value="<?php if (
                  isset($_SESSION['cbirthdate'])
              ) {
                  echo $_SESSION['cbirthdate'];
              } ?>"/>
              <p class="field-name">sex</p>
              <select name="sex" class="field">
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
              <p class="field-name">mobile number</p>
              <input class="field" type="text" name="mobile_no" maxlength="11" value="<?php if (
                  isset($_SESSION['cmobile_no'])
              ) {
                  echo $_SESSION['cmobile_no'];
              } ?>"/>
              <p class="field-name">email address</p>
              <input class="field" type="text" name="email" maxlength="100" value="<?php if (
                  isset($_SESSION['cemail'])
              ) {
                  echo $_SESSION['cemail'];
              } ?>"/>
              <p class="field-name">home address</p>
              <input class="field" type="text" name="address" maxlength="100" value="<?php if (
                  isset($_SESSION['caddress'])
              ) {
                  echo $_SESSION['caddress'];
              } ?>"/>
            </div>
            <div id="form-buttons-div">
              <div id="cancel" class="button" onclick="showHide()">CANCEL</div>
              <button type="submit" form="create-form" id="save" class="button" >SAVE</button>
            </div>

          </form>

          <div id="rating-div">
            <p class="field-name" id="rating-field-name">rating</p>           
            <div id="rating">
              <input form="create-form" type="radio" id="star5" name="rate" value="5" <?php if (
                  isset($_SESSION['crate']) and
                  $_SESSION['crate'] == 5
              ) {
                  echo 'checked';
              } ?>/>
              <label for="star5">&#128970;</label>
              <input form="create-form" type="radio" id="star4" name="rate" value="4" <?php if (
                  isset($_SESSION['crate']) and
                  $_SESSION['crate'] == 4
              ) {
                  echo 'checked';
              } ?>/>
              <label for="star4">&#128970;</label>
              <input form="create-form" type="radio" id="star3" name="rate" value="3" <?php if (
                  isset($_SESSION['crate']) and
                  $_SESSION['crate'] == 3
              ) {
                  echo 'checked';
              } ?>/>
              <label for="star3">&#128970;</label>
              <input form="create-form" type="radio" id="star2" name="rate" value="2" <?php if (
                  isset($_SESSION['crate']) and
                  $_SESSION['crate'] == 2
              ) {
                  echo 'checked';
              } ?>/>
              <label for="star2">&#128970;</label>
              <input form="create-form" type="radio" id="star1" name="rate" value="1" <?php if (
                  isset($_SESSION['crate']) and
                  $_SESSION['crate'] == 1
              ) {
                  echo 'checked';
              } ?>/>
              <label for="star1">&#128970;</label> 
            </div>
          </div>
        </div>
        <div id="tools">
          <div id="add" class="button" onclick="showHide()"><p></p></div>
          <div id="search-div">
            <input type="text" id="search-field" class="field" placeholder="Search" onkeyup="filterList()">
            <div id="search-icon"></div>
          </div>
        </div>
        <div id="list-div">
          <div id="list-inner-div">
          
          </div>
        </div>
      </main>
      <div id="extra">
        <div id="notes-header">NOTES</div>
        <textarea id="notes" class="field" placeholder="Write your quick notes here" onkeyup="updateNotes(this)" spellcheck="false"><?php if(!empty($_SESSION["notes"])){echo $_SESSION["notes"];}?></textarea>
      </div>
    </div>
    <footer></footer>
    <script type="text/javascript" src="jquery.js"></script>
    <script>
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
              }
            });
      }

      $(document).ready(function () {
        loadCustomers();
        fetchNotes();
      });
      // shorthand for $(document).ready(); is $();
      // can also do $(window).on("load", function(){}); 
      // that is if you want code inside to run once entire page is ready, not just DOM

      // show or hide form
      function showHide(){
            document.getElementById("create-form-div-c").classList.toggle("hidden-item");
            document.getElementById("error").style.visibility = "hidden"; // can also do document.getElementById("error").setAttribute("style","visibility: hidden;"); but is considered bad practice since it will overwrite properties which may already be specified in the style attribute
            $("input").val("");
            $("select[name='sex'] option").prop("selected", false);
            $("input[name='rate']:radio").prop("checked", false);
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
        let url = "account.php?customer=" + customer;
        window.location.href=url;
      }
    </script>
  </body>
</html>