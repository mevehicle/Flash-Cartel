<?php
$pageTitle = "Create Deck";
include "header.php";

// Check that user is signed in
if (!array_key_exists("user_id", $_SESSION)) {
  header("location: index.php");
  exit();
}

if (isset($_POST["submit"])) {
  header("location: create_deck.inc.php");
  exit();
} else if (isset($_GET["error"])) {
  if ($_GET["error"] === "emptyinput") {
    echo "<p class=\"large\"> Please fill in the form carefully! </p>";
  } else if ($_GET["error"] === "existsalready")
    echo "<p class=\"large\"> There is already a deck with that name! </p>";
}
?>

<body class="fabric">
  <div class="navbar">
    <a href="home.php">
      <img src="images/logo.jpg" alt="Flash Cartel logo" />
    </a>
    <br>
    <a class="home-link" href="includes/logout.inc.php">Logout</a>
  </div>
  <br>
  <div class="tab">
    <h1 class="home-title">Create a new deck</h1>
    <form action="includes/create_deck.inc.php" method="post">
      <input type="text" name="deck_name" placeholder="Deck Name" required><br>
      <input type="submit" name="submit" value="Create Deck">
    </form>
  </div>

  <?php
  include "footer.php";
