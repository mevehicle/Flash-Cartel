<?php
require "includes/functions.inc.php";
$pageTitle = "View Cards";
include "header.php";

// Check that user is signed in
if (!array_key_exists("user_id", $_SESSION)) {
  header("location: index.php");
  exit();
}
if (isset($_GET)) {
  // Check that a deck has been selected to view
  if (isset($_GET["error"])) {
    if ($_GET["error"] === "nodeck") {
      header("Location: view_deck.php?error=emptyinput&action=view");
      exit;
    }
  } else {
    if (!isset($_GET['deck']) || (!isset($_GET['cardNumber']))) {
      header("location: home.php?error=unknownerror");
      exit();
    }

    // Get deck_id from URL query string
    $deck_id = $_GET["deck_id"];

    // Validate $deck_id in case of query string injection
    if (!checkDeckId($_SESSION['user_id'], $deck_id)) {
      header("location: home.php?error=unknownerror");
      exit();
    } else {

      // 'Download' deck from database into array $cards
      $cards = getCards($deck_id);

      // Work out which card to show, using cardNumber from URL query string
      $cardNumber = (int) $_GET["cardNumber"];

      // Check how many cards are in deck, in case user wants to see next card
      $numberOfCards = getCardsInDeck($deck_id);

      // Validate cardNumber in case of query string injection
      $cardNumber = max(0, min($cardNumber, $numberOfCards - 1));

      // Copy card number to use for previous and next card buttons
      $baseCardNumber = $cardNumber;
    }
  }
} else {
  header("location: home.php?error=unknownerror");
  exit();
}
?>

<body>
  <br>
  <div>
    <a href="home.php">
      <img src="images/logo.jpg" alt="Flash Cartel logo" />
    </a>
  </div>
  <div class="tab">
    <h1>View Cards</h1>
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
      <tr>
        <td class="card"><?php echo $cards[$cardNumber]["answer"] ?></td>
      </tr>
    </table>
  </div>
  <div>
    <!-- Submit button has hidden field to decrease value of cardNumber
     from baseCardNumber, and wrap round to bottom of deck if at 0 -->
    <form action="view_cards.php" method="get">
      <input type="hidden" name="deck_id" value="<?php echo $deck_id; ?>">
      <input type="hidden" name="cardNumber" value="
        <?php if ($baseCardNumber > 0) {
          $cardNumber = $baseCardNumber - 1;
        } else {
          $cardNumber = $numberOfCards - 1;
        }
        echo $cardNumber;
        ?>">
      <input type="submit" value="Previous Card">
    </form>
    <!-- Submit button has hidden field to increase value of cardNumber,
    from baseCardNumber, and return to top of deck if at bottom -->
    <form action="view_cards.php" method="get">
      <input type="hidden" name="deck_id" value="<?php echo $deck_id; ?>">
      <input type="hidden" name="cardNumber" value="
        <?php if ($baseCardNumber < ($numberOfCards - 1)) {
          $cardNumber = $baseCardNumber + 1;
        } else {
          $cardNumber = 0;
        }
        echo $cardNumber;
        ?>">
      <input type="submit" value="Next Card">
    </form>
  </div>
  <div>
    <form action="edit_card.php" method="get">
      <input type="hidden" name="deck_id" value="<?php echo $deck_id; ?>">
      <input type="hidden" name="cardNumber" value="<?php echo $baseCardNumber; ?>">
      <input type="submit" value="Edit this Card">
    </form>
    <form action="delete_card.php" method="get">
      <input type="hidden" name="deck_id" value="<?php echo $deck_id; ?>">
      <input type="hidden" name="cardNumber" value="<?php echo $baseCardNumber; ?>">
      <input type="submit" value="Delete this Card">
    </form>
  </div>
  <div>
    <a class="home-link" href="includes/logout.inc.php">Logout</a>
  </div>

  <?php
  include "footer.php";
