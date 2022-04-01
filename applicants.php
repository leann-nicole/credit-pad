<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
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
    <p id="error" class="<?php if (!isset($_GET['error'])) {
          echo 'hidden-item';
      }?>">
        <?php if (isset($_GET['error'])) {
            echo $_GET['error'];
        }?>
    </p>      
    <header>
      <p id="sitename-header"><a href="applicants.php">CREDIT PAD</a></p>
      <a href="logout.php"><span id="account-icon" class="material-icons">account_circle</span></a>
    </header>
    <div id="content">
      <nav>
        <ul>
          <li class="selected-navbar-item"><a href="applicants.php">APPLICANTS</a></li>
          <li><a href="stores.php">STORES</a></li>
        </ul>
      </nav>
      <main>
        <div id="applicant-list">

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
      function focusSearchBar(){
        $("#search-field").focus();
      }

      function loadApplicants(){
        $.ajax({
          url: "load-applicants.php",
          success: function (data){
            $("#applicant-list").html(data);
          }
        });
      }

      $(document).ready(function () {
        fetchNotes();
        loadApplicants();
      });
      // shorthand for $(document).ready(); is $();
      // can also do $(window).on("load", function(){}); 
      // that is if you want code inside to run once entire page is ready, not just DOM


      function fetchNotes(){
        $.ajax({
          url: "update-note.php", 
          type: "POST",
          data: {admin: true}
        });        
      }

      function updateNotes(element){
        let notes = element.value;
        $.ajax({
          url: "update-note.php", 
          type: "POST",
          data: {admin: true, notes: notes}
        });        
      }

      function approveApplicant(element){
        let applicantItem = element.parentElement.parentElement.parentElement;
        let storeName = applicantItem.getElementsByClassName("applicant-business-name")[0].textContent;
        $.ajax({
          url: "approve-applicant.php",
          type: "POST",
          data: {storeName: storeName},
          success: function(data){
            loadApplicants();
          }
        });
      }

      function rejectApplicant(element){
        let applicantItem = element.parentElement.parentElement.parentElement;
        let storeName = applicantItem.getElementsByClassName("applicant-business-name")[0].textContent;
        let storeOperator = applicantItem.getElementsByClassName("applicant-name")[0].textContent;
        let storeLocation = applicantItem.getElementsByClassName("applicant-business-location")[0].textContent;
        let applicationDate = applicantItem.getElementsByClassName("applicant-date")[0].textContent;
        console.log(storeName, storeOperator, storeLocation, applicationDate);
        $.ajax({
          url: "reject-applicant.php",
          type: "POST",
          data: {storeName: storeName, storeOperator: storeOperator, storeLocation: storeLocation, applicationDate: applicationDate},
          success: function(data){
            loadApplicants();
          }
        });
      }
    </script>
  </body>
</html>
