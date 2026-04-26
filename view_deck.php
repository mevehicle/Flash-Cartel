<?php
require 'includes/functions.inc.php';
$pageTitle = "View Deck";
include "header.php";

// Check that user is signed in
if (!array_key_exists("user_id", $_SESSION)) {
  header("location: index.php");
  exit();
}

if (isset($_GET["error"])) {
  if ($_GET["error"] === "emptyinput") {
    echo "<p class=\"large\"> You didn't select a deck ! </p>";
  } else if ($_GET["error"] === "doesntexist") {
    echo "<p class=\"large\"> You haven't created that deck yet ! </p>";
  } else if ($_GET["error"] === "emptydeck") {
    echo "<p class=\"large\"> That deck has no cards in it! </p>";
  }
}

// check that the reason why the deck is being viewed has been included in query string
if (!isset($_GET['action'])) {
  header("location: home.php?error=unknownerror");
  exit();
} else {
  $action = $_GET['action'];
}

?>

<body class="fabric">
  <div>
    <a href="home.php">
      <img src="images/logo.jpg" alt="Flash Cartel logo" />
    </a>
    <br>
    <a class="home-link" href="includes/logout.inc.php">Logout</a>
  </div>
  <br>
  <div>
    <h1 class="home-title">First, choose a deck to view</h1>
  </div>
  <br>
  <div class="tab">
    <form action="includes/view_deck.inc.php" method="post">
      <!-- use a foreach loop to fetch data
        from the $deck['deck_name'] variable
        and individually display each deck as an option -->
      <select name="deck_name">
        <option value="">Choose a deck</option>
        <?php
        $decks = getDecks($_SESSION["user_id"]);
        foreach ($decks as $deck) {
        ?>
          <option value="<?php echo $deck['deck_name'] ?>">
            <?php echo $deck['deck_name'] ?>
          </option>
        <?php
        }
        ?>
      </select><br>
      <input type="hidden" name="action" value=<?php echo $action ?>>
      <input type="submit" name="submit" value="View Deck">
    </form>
  </div>

  <?php
  include "footer.php";
