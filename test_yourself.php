<?php
require "includes/functions.inc.php";
$pageTitle = "Test yourself";
include "header.php";

// Check that user is signed in
if (!array_key_exists("user_id", $_SESSION)) {
  header("location: index.php");
  exit();
}

// Check if page has been accessed properly
if (!isset($_GET['deck_id'])) {
  header("location: view_deck.php");
} else {
  if (!isset($_GET['view'])) {
    header("location: home.php?error=unknownerror");
  } else {
    $view = $_GET['view'];
    $deck_id = $_GET['deck_id'];
    if (!checkDeckId($_SESSION['user_id'], $deck_id)) {
      header("location: home.php?error=unknownerror");
    }
    $cards = getCards($deck_id);
    $numberOfCards = getCardsInDeck($deck_id);
    if (!isset($_GET['cardNumber'])) {
      $cardNumber = rand(0, $numberOfCards - 1);
    } else {
      $cardNumber = $_GET['cardNumber'];
    }
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
  <div class="tab">
    <h1>Test Yourself</h1>
  </div>
  <div>
    <table>
      <tr>
        <?php if ($view == "question") {
          echo "<th>Question</th>";
        } else {
          echo "<th>Answer</th>";
        }
        ?>
      </tr>
      <tr>
        <td class="card"><?php if ($view == "question") {
                            echo $cards[$cardNumber]['question'];
                          } else {
                            echo $cards[$cardNumber]['answer'];
                          }
                          ?>
        </td>
      </tr>
    </table>
  </div>
  <div>
    <?php if ($view == "question") {
      echo "<a class='home-link' href='test_yourself.php?view=answer&deck_id=$deck_id&cardNumber=$cardNumber'>
            View Answer</a>";
    } else {
      echo "<a class='home-link' href='test_yourself.php?view=question&deck_id=$deck_id&cardNumber=$cardNumber'>
        View Question</a>";
    }
    ?>
  </div>
  <div>
    <?php echo "<a class='home-link' href='test_yourself.php?view=question&deck_id=$deck_id'>Next Card</a>";
    ?>
  </div>

  <?php
  include "footer.php";
