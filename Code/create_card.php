<?php
require 'includes/functions.inc.php';
$pageTitle = "Create Card";
include "header.php";

// Check that user is signed in
if (!array_key_exists("user_id", $_SESSION)) {
  header("location: index.php");
  exit();
}

// Check if form is already submitted with at least either question or answer
if (isset($_POST) && ((isset($_POST["question"])) || (isset($_POST["answer"])))) {
  header("location: create_card.inc.php");
  exit();
  // check if URL contains an error message:
} else if (isset($_GET["error"])) {
  if ($_GET["error"] === "nodeck") {
    echo "<p class=\"large\"> You must select or create a deck for your new card.</p>";
  } elseif ($_GET["error"] === "emptyinput") {
    echo "<p class=\"large\"> Please fill in the form carefully! </p>";
  } elseif ($_GET["error"] === "none") {
    echo "<p class=\"large\"> Your card has been created! </p>";
  }
}

?>

<body>
  <br>
  <div>
    <a href="home.php">
      <img src="images/logo.jpg" alt="Flash Cartel logo" />
    </a>
  </div>
  <br>
  <div class="tab">
    <h1>Create a new card</h1>
    <form class="login" action="includes/create_card.inc.php" method="post">
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
      <textarea name="question" placeholder="Question"></textarea><br>
      <textarea name="answer" placeholder="Answer"></textarea><br>
      <input type="submit" name="submit" value="Create Card" />
    </form>
  </div>
  <div>
    <br>
    <a class="home-link" href="includes/logout.inc.php">Logout</a>
  </div>

  <?php
  include "footer.php";
