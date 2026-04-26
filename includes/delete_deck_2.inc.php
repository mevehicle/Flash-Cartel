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
  if (!isset($_POST["deck_id"])) {
    $deck_id = $_POST['deck_id'];
    deleteDeck($deck_id);
    header("location: ../home.php");
    exit();
  }
} else { // If deck_id is absent
  header("location: ../home.php?error=unknownerror");
  exit();
}
