<?php
$pageTitle = "Home";
include "header.php";

if (!array_key_exists("user_id", $_SESSION)) {
  header("location: index.php");
  exit();
}
?>

<body class="fabric">
  <div class="navbar">
    <img src="images/logo.jpg" alt="Flash Cartel logo" />
    <h1 class="home-title">Welcome to <em>Flash Cartel</em>, <?php echo $_SESSION["username"]; ?>!</h1>
    <a href="includes/logout.inc.php" align="right">Logout</a>
  </div>

  <div class="tab">
    <br>

    <ul>
      <li><a class="home-link" href="view_cards.php">View cards</a></li>
      <li><a class="home-link" href="create_card.php">Create card</a></li>
      <li><a class="home-link" href="edit_card.php">Edit card</a></li>
      <li><a class="home-link" href="delete_card.php">Delete card</a></li>
      <li><a class="home-link" href="test_yourself.php">Test yourself</a></li>
    </ul>


  </div>
  <?php
  include "footer.php";
