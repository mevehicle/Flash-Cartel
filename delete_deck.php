<?php
include "includes/functions.inc.php";
$pageTitle = "Delete Deck";
include "header.php";

// Check that user is signed in
if (!array_key_exists("user_id", $_SESSION)) {
  header("location: index.php");
  exit();
}

if (isset($_GET["error"])) {
  if ($_GET["error"] === "emptyinput") {
    echo "<p class=\"large\"> You didn't select one ! </p>";
  } else if ($_GET["error"] === "doesntexist") {
    echo "<p class=\"large\"> You haven't created that deck yet ! </p>";
  }
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
    <h1 class="home-title">Choose a deck to delete</h1>
  </div>
  <br>
  <div class="tab">
    <form action="includes/delete_deck_1.inc.php" method="post">
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
      <input type="submit" name="submit" value="Delete Deck">
    </form>
  </div>

  <?php
  include "footer.php";
