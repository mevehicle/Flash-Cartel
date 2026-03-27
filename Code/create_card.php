<?php
require 'includes/functions.inc.php';
$pageTitle = "Create Card";
include "header.php";

// Check that user is signed in
if (!array_key_exists("user_id", $_SESSION)) {
  header("location: index.php");
  exit();
}

if (isset($_POST) && ((isset($_POST["question"])) || (isset($_POST["answer"])))) {
  header("location: create_card.inc.php");
  exit();
} else if (isset($_GET["error"])) {
  if ($_GET["error"] === "emptyinput") {
    echo "<p class=\"large\"> Please fill in the form carefully! </p>";
  }
}
?>

<body>
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
        from the $deck_name variable
        and individually display each deck as an option -->
      <select name="deck_name">
        <option value="">Choose a deck</option>
        <?php
        $decks = getDecks();
        foreach ($decks as $deck) {
        ?>
          <option value="<?php echo $deck['deck_name'] ?>">
            <?php echo $deck['deck_name'] ?>
          </option>
        <?php
        }
        ?>
      </select><br>
      <input class="card" type="text" name="question" placeholder="Question"><br>
      <input class="card" type="text" name="answer" placeholder="Answer"><br>
      <input type="submit" name="submit" value="Create Card" />
    </form>
  </div>
  <div>
    <br>
    <a class="home-link" href="includes/logout.inc.php">Logout</a>
  </div>

  <?php
  include "footer.php";
