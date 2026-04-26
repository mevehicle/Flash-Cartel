<?php
session_start();

// Check that user is signed in
if (!array_key_exists("user_id", $_SESSION)) {
  header("location: index.php");
  exit();
}

// Check that form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Check whether user has submitted the name of a deck
  if (empty($_POST["deck_name"])) {
    header("location: ../create_deck.php?error=emptyinput");
    exit();
  }

  $deck_name = htmlspecialchars($_POST["deck_name"]);
  $user_id = $_SESSION["user_id"];

  require "functions.inc.php";

  // Check whether a deck with this name already exists
  $conn = getConnection();
  $stmt = $conn->prepare("SELECT * FROM decks WHERE deck_name = :deck_name 
                          AND user_id = :user_id");
  $stmt->bindValue(':deck_name', $deck_name);
  $stmt->bindValue(':user_id', $user_id);
  $stmt->execute();
  if ($row = $stmt->fetch()) {
    header("location: ../create_deck.php?error=existsalready");
    exit();
  }

  $stmt = $conn->prepare("INSERT INTO decks(deck_name, user_id) VALUES
  (:deck_name, :user_id)");
  $stmt->bindValue(':deck_name', $deck_name);
  $stmt->bindValue(':user_id', $user_id);
  $stmt->execute();

  // Close connection
  $conn = null;
  header("location: ../home.php");
} else { // If form hasn't been submitted
  header("location: ../create_deck.php");
  exit();
}
