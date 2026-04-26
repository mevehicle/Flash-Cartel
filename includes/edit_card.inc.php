<?php
require "functions.inc.php";
session_start();

// Check that user is signed in
if (!array_key_exists("user_id", $_SESSION)) {
  header("location: ../index.php");
  exit();
}

// Check that form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $deck_id = $_POST['deck_id'];
  $cardNumber = $_POST['cardNumber'];
  // Check whether user has entered any information
  if (empty($_POST["question"]) && empty($POST["answer"])) {
    header("location: ../edit_card.php?error=emptyinput&deck_id=$deck_id&cardNumber=$cardNumber");
    exit();
  } else {
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    // Insert new card info into database
    editCard($deck_id, $cardNumber, $question, $answer);
    header("location: ../edit_card.php?deck_id=$deck_id&cardNumber=$cardNumber");
    exit();
  }
} else { // If form hasn't been submitted
  header("location: ../home.php?error=unknownerror");
  exit();
}
