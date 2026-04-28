<?php
$pageTitle = "Delete Card";
require "includes/functions.inc.php";
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
    <h1>Delete Card</h1>
  </div>
  <div>
    <table>
      <tr>
        <th>Question</th>
      </tr>
      <tr>
        <td class="card"><?php echo $cards[$cardNumber]["question"] ?></td>
      </tr>
      <tr>
        <th>Answer</th>
      </tr>
      <td class="card"><?php echo $cards[$cardNumber]["answer"] ?></td>
      </tr>
    </table>
  </div>
  <div>
    <p class="large">Delete this card?</p>
    <a href="includes/delete_card.inc.php?deck_id=<?php echo $deck_id ?>&cardNumber=<?php echo $cardNumber ?>">
      <p class="large">Yes</p>
    </a>
    <a href="view_cards.php?deck_id=<?php echo $deck_id ?>&cardNumber=<?php echo $cardNumber ?>">
      <p class="large">No, return to view cards</p>
    </a>
  </div>

  <a class="home-link" href="includes/logout.inc.php">Logout</a>

  <?php
  include "footer.php";
