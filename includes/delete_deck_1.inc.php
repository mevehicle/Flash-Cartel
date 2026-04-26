<?php
session_start();

// Check that user is signed in
if (!array_key_exists("user_id", $_SESSION)) {
  header("location: index.php");
  exit();
}

// Check that form has been submitted
if (isset($_POST["submit"])) {
  // Check whether user has submitted the name of a deck
  if (!isset($_POST["deck_name"])) {
    header("location: ../delete_deck.php?error=emptyinput");
    exit();
  }

  $deck_name = htmlspecialchars($_POST["deck_name"]);
  $user_id = $_SESSION["user_id"];

  require "functions.inc.php";

  // set empty message for initial viewing of page

  // Check whether a deck with this name exists
  $row = findDeckId($deck_name, $user_id);

  // If the deck doesn't exist, return an error to delete_deck.php
  if (!$row) {
    header("location: ../view_deck.php?error=doesntexist&action=delete");
    exit();
  } else { // If deck does exist, grab its ID to delete it (2 decks could have same name)
    $deck_id = getDeckId($user_id, $deck_name);
  }
} else {
  header("Location: ../home.php?error=unknownerror");
  exit();
}
?>

<body class="fabric">
  <div class="tab">
    <a href="home.php">
      <img src="images/logo.jpg" alt="Flash Cartel logo" />
    </a>
    <a class="home-link" href="includes/logout.inc.php">Logout</a>
  </div>
  <br>
  <div>
    <p class="large">Are you sure you want to delete <?php echo $deck_name ?>?</p>
    <form action="" method="post">
      <input type="hidden" name="deck_id" value="<?php echo $deck_id; ?>">
      <input type="submit" name="submit" value="Yes" />
      <a href="../home.php">
        <p class="large">No</p><br>
  </div>
  <div>
    <a class="home-link" href="includes/logout.inc.php">Logout</a>
  </div>
  <?php
  include "footer.php";
