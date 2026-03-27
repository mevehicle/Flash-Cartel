<?php
session_start();
$pageTitle = "Create Card";

// Check that user is signed in
if (!array_key_exists("user_id", $_SESSION)) {
  header("location: index.php");
  exit();
}

if (isset($_POST["submit"])) {
  if (
    !isset($_POST["deck_name"]) ||
    ((!isset($_POST["question"])) && (!isset($_POST["answer"])))
  ) {
    header("location: ../create_card.php?error=emptyinput");
    exit();
  }


  $deck_name = $_POST["deck_name"];
  $question = htmlspecialchars($_POST["question"]);
  $answer = htmlspecialchars($_POST["answer"]);
  $user_id = $_SESSION["user_id"];

  require "functions.inc.php";

  $conn = getConnection();

  $stmt = $conn->prepare("INSERT INTO 'cards'(deck_id, question, answer) VALUES
  (:user_id, :question, :answer)");
  $stmt->bindValue(':user_id', $user_id);
  $stmt->bindValue(':question', $question);
  $stmt->bindValue(':answer', $answer);
  $stmt->execute();

  // Close connection
  $conn = null;

  header("location: ../home.php");
} else { // If user hasn't submitted form
  header("location: ../create_card.php");
  exit();
}
