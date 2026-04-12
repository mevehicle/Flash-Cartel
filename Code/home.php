<?php
$pageTitle = "Home";
include "header.php";

// Check that user is signed in
if (!array_key_exists("user_id", $_SESSION)) {
  header("location: index.php");
  exit();
}
?>

<body class="fabric">
  <div class="navbar">
    <img src="images/logo.jpg" alt="Flash Cartel logo" />
    <h1 class="home-title">Welcome to <em>Flash Cartel</em>,
      <?php echo $_SESSION["username"]; ?>!</h1>
    <br>
    <a href="includes/logout.inc.php">Logout</a>
    <br>
    <a href="edit_account.php">Edit Account</a>
  </div>

  <div class="tab">
    <br>

    <ul>
      <li><a class="home-link" href="create_deck.php">Create deck</a></li>
      <!-- Link to View Cards initially leads to page to select a deck -->
      <li><a class="home-link" href="view_deck.php">View cards</a></li>
      <li><a class="home-link" href="create_card.php">Create card</a></li>
      <li><a class="home-link" href="edit_card.php">Edit card</a></li>
      <li><a class="home-link" href="delete_card.php">Delete card</a></li>
      <li><a class="home-link" href="test_yourself.php">Test yourself</a></li>
    </ul>


  </div>
  <?php
  include "footer.php";
