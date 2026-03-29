<?php
session_start();
$pageTitle = "Create Card";

// Check that user is signed in
if (!array_key_exists("user_id", $_SESSION)) {
  header("location: index.php");
  exit();
}

if (isset($_POST["submit"])) {
  if ((!isset($_POST["question"])) && (!isset($_POST["answer"]))) {
    header("location: ../create_card.php?error=emptyinput");
  } elseif (empty($_POST["deck_name"])) {
    header("location: ../create_card.php?error=nodeck");
    exit();
  } else {
    // Retrieve values of variables from $_POST global array
    $deck_name = $_POST["deck_name"];
    $question = htmlspecialchars($_POST["question"]);
    $answer = htmlspecialchars($_POST["answer"]);
    $user_id = $_SESSION["user_id"];

    require "functions.inc.php";

    // Query database for deck_id
    $deck_id = getDeckId($user_id, $deck_name);

    $conn = getConnection();
    $stmt = $conn->prepare("INSERT INTO cards(deck_id, question, answer) VALUES
  (:deck_id, :question, :answer)");
    $stmt->bindValue(':deck_id', $deck_id);
    $stmt->bindValue(':question', $question);
    $stmt->bindValue(':answer', $answer);
    $stmt->execute();

    // Close connection
    $conn = null;

    header("location: ../create_card.php?error=none");
  }
} else { // If user hasn't submitted form
  header("location: ../create_card.php");
  exit();
}
