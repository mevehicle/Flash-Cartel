<?php
require "includes/functions.inc.php";
$pageTitle = "Edit Card";
include "header.php";

// Check that user is signed in
if (!array_key_exists("user_id", $_SESSION)) {
  header("location: index.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // Check that both deck_id and cardNumber are available in query string
  if (!isset($_GET['deck_id']) || !isset($_GET['cardNumber'])) {
    header("location: home.php");
    exit();
  } else {
    if (isset($_GET["error"])) {
      if ($_GET["error"] === "emptyinput") {
        echo "<p class=\"large\"> You cannot leave the card blank.</p>";
      }
    }
    // Get deck_id from URL query string
    $deck_id = $_GET["deck_id"];

    // Check how many decks user has, to ensure deck number won't be too high
    $numberOfDecks = getNumberOfDecks($_SESSION['user_id']);

    /* Validate $deck_id in case of query string injection,
      so make sure deck belongs to current user */
    if (!checkDeckId($_SESSION['user_id'], $deck_id)) {
      header("location: home.php");
      exit();
    } else {

      // 'Download' deck from database into array $cards
      $cards = getCards($deck_id);

      // Work out which card to show, using cardNumber from URL query string
      $cardNumber = (int) $_GET["cardNumber"];

      /* Validate cardNumber in case of query string injection
        eg. is it higher than the number of cards in the deck */
      $numberOfCards = getCardsInDeck($deck_id);
      $cardNumber = max(0, min($cardNumber, $numberOfCards - 1));
    }
  }
} else {
  header("location: home.php");
  exit();
}
?>

<body>
  <div>
    <a href="home.php">
      <img src="images/logo.jpg" alt="Flash Cartel logo" />
    </a>
  </div>
  <div class="tab">
    <h1>Edit Card</h1>
  </div>
  <div>
    <form action="includes/edit_card.inc.php" method="post">
      <textarea name="question" value="<?php $cards[$cardNumber]["question"] ?>"
        placeholder="<?php echo $cards[$cardNumber]["question"] ?>"></textarea><br>
      <textarea name="answer" value="<?php $cards[$cardNumber]["answer"] ?>"
        placeholder="<?php echo $cards[$cardNumber]["answer"] ?>"></textarea><br>
      <input type="hidden" name="deck_id" value="<?php echo $deck_id; ?>">
      <input type="hidden" name="cardNumber" value="<?php echo $cardNumber; ?>">
      <input type="submit" name="submit" value="Edit Card" />
    </form>
  </div>
  <div>
    <a class="home-link" href="view_cards.php?deck_id=<?php echo $deck_id ?>&cardNumber=<?php echo $cardNumber ?>">Return to View Cards</a>
  </div>
  </div>
  <div>
    <a class="home-link" href="includes/logout.inc.php">Logout</a>
  </div>
  <?php
  include "footer.php";
