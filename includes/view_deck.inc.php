<?php
session_start();

// Check that user is signed in
if (!array_key_exists("user_id", $_SESSION)) {
  header("location: index.php");
  exit();
}

// Check that form has been submitted
if (isset($_POST["submit"])) {
  /*
   Check if the reason why the decks are being viewed is included,
   eg. to view the cards or to test yourself
  */
  if (!isset($_POST['action'])) {
    header("location: ../home.php?error=unknownerror");
    exit();
  }
  // Check whether user has submitted the name of a deck
  if (!isset($_POST["deck_name"])) {
    header("location: ../view_deck.php?error=emptyinput");
    exit();
  }

  $deck_name = htmlspecialchars($_POST["deck_name"]);
  $user_id = $_SESSION["user_id"];

  require "functions.inc.php";

  // Check whether a deck with this name exists
  $row = findDeckId($deck_name, $user_id);

  // If the deck doesn't exist, return an error to view_deck.php
  if (!$row) {
    header("location: ../view_deck.php?error=doesntexist");
    exit();
  } else { // If deck does exist, grab its ID to check if it's empty
    $deck_id = getDeckId($user_id, $deck_name);
    $cards = getCards($deck_id);
    // Check how many cards are in deck
    $numberOfCards = getCardsInDeck($deck_id);
    if ($numberOfCards = 0) {
      header("location: view_deck.php/error=emptydeck");
      exit();
    }
    if ($_POST['action'] == "view") {
      // If the deck exists and is not empty, pass on its name to view_cards.php, to show first card
      header("location: ../view_cards.php?deck_id=$deck_id&cardNumber=0");
      exit();
    } else { // if action = "test"
      // Randomly select card to show
      // $cardNumber = rand(0, max(0, $numberOfCards - 1));
      // Pass on deck_id and random cardNumber to test_yourself_question.php
      header("location: ../test_yourself.php?view=question&deck_id=$deck_id");
      exit();
    }
  }
} else { // If form hasn't been submitted
  header("location: ../view_deck.php");
  exit();
}
