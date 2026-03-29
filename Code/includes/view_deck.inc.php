<?php
session_start();

// Check that user is signed in
if (!array_key_exists("user_id", $_SESSION)) {
  header("location: index.php");
  exit();
}

// Check that form has been submitted
if (isset($_POST["submit"])) {
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
    if (empty($cards)) {
      header("location: ../view_deck.php?error=emptydeck");
      exit();
    } else { // If the deck exists and is not empty, pass on its name to view_cards.php, to show first card
      header("location: ../view_cards.php?deck_id=$deck_id&cardNumber=0");
      exit();
    }
  }
} else { // If form hasn't been submitted
  header("location: ../view_deck.php");
  exit();
}
