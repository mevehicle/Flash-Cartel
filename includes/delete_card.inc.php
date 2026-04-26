<?php
require "functions.inc.php";
session_start();

// Check that user is signed in
if (!array_key_exists("user_id", $_SESSION)) {
  header("location: ../index.php");
  exit();
}

// Check that deck_id and cardNumber were present in query string
if (isset($_GET['deck_id']) && isset($_GET['cardNumber'])) {
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
    // Work out which card to delete, using cardNumber from URL query string
    $cardNumber = (int) $_GET["cardNumber"];

    /* Validate cardNumber in case of query string injection
        eg. is it higher than the number of cards in the deck */
    $numberOfCards = getCardsInDeck($deck_id);
    $cardNumber = max(0, min($cardNumber, $numberOfCards - 1));

    // Execute function to delete the card
    deleteCard($deck_id, $cardNumber);

    // Find next card number to view
    if ($cardNumber < ($numberOfCards - 2)) {
      header("location: ../view_cards.php?deck_id=$deck_id&cardNumber=$cardNumber");
      exit();
    } else {
      $cardNumber = 0;
      header("location: ../view_cards.php?deck_id=$deck_id&cardNumber=$cardNumber");
      exit();
    }
  }
} else { // If either deck_id or cardNumber are absent
  header("location: ../home.php?error=unknownerror");
  exit();
}
